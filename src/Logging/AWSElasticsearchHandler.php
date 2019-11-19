<?php

namespace Si6\Base\Logging;

use Aws\Credentials\CredentialProvider;
use Aws\Signature\SignatureV4;
use Elasticsearch\ClientBuilder;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\RequestInterface;

class AWSElasticsearchHandler
{
    private $signer;
    private $credentialProvider;
    private $wrappedHandler;

    public function __construct(
        $region,
        callable $credentialProvider = null,
        callable $wrappedHandler = null
    ) {
        $this->signer = new SignatureV4('es', $region);
        $this->wrappedHandler = $wrappedHandler
            ?: ClientBuilder::defaultHandler();
        $this->credentialProvider = $credentialProvider
            ?: CredentialProvider::defaultProvider();
    }

    public function __invoke(array $request)
    {
        $creds = call_user_func($this->credentialProvider)->wait();
        $psr7Request = $this->createPsr7Request($request);
        $signedRequest = $this->signer
            ->signRequest($psr7Request, $creds);

        return call_user_func($this->wrappedHandler, $this->createRingRequest($signedRequest));
    }

    private function createPsr7Request(array $ringPhpRequest)
    {
        $hostKey = isset($ringPhpRequest['headers']['Host'])? 'Host' : 'host';

        $parsedUrl = parse_url($ringPhpRequest['headers'][$hostKey][0]);
        if (isset($parsedUrl['host'])) {
            $ringPhpRequest['headers'][$hostKey][0] = $parsedUrl['host'];
        }

        $uri = (new Uri($ringPhpRequest['uri']))
            ->withScheme($ringPhpRequest['scheme'])
            ->withHost($ringPhpRequest['headers'][$hostKey][0]);
        if (isset($ringPhpRequest['query_string'])) {
            $uri = $uri->withQuery($ringPhpRequest['query_string']);
        }

        return new Request(
            $ringPhpRequest['http_method'],
            $uri,
            $ringPhpRequest['headers'],
            $ringPhpRequest['body']
        );
    }

    private function createRingRequest(RequestInterface $request)
    {
        $uri = $request->getUri();
        $body = (string) $request->getBody();

        if (empty($body)) {
            $body = null;
        }

        $ringRequest = [
            'http_method' => $request->getMethod(),
            'scheme' => $uri->getScheme(),
            'uri' => $uri->getPath(),
            'body' => $body,
            'headers' => $request->getHeaders(),
        ];

        if ($uri->getQuery()) {
            $ringRequest['query_string'] = $uri->getQuery();
        }

        return $ringRequest;
    }
}