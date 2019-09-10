<?php

namespace Si6\Base\Services;

use Exception;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Str;
use Psr\Http\Message\ResponseInterface;
use Si6\Base\Exceptions\MicroservicesException;
use Symfony\Component\HttpFoundation\Response;

abstract class Microservices
{
    protected static $instance = null;

    /** @var ClientInterface $client */
    protected $client;

    protected $options = [];

    protected $syncException = true;

    public function __construct()
    {
        $this->setupClient();
    }

    protected function setupClient()
    {
        $this->setBaseUri();
        $this->setDefaultHeaders();

        $this->client = app(ClientInterface::class, $this->options);
    }

    protected function setBaseUri()
    {
        $this->options['base_uri'] = $this->baseUri();
    }

    protected function setDefaultHeaders()
    {
        $this->options['headers'] = [
            'Content-type' => 'application/json',
            'Accept'       => 'application/json',
        ];
    }

    public static function getInstance()
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    protected function baseUri()
    {
        $host = $this->getHost();

        return Str::finish($host, '/');
    }

    abstract protected function getHost();

    protected function get($url, $data = [], $options = [])
    {
        return $this->query('GET', $url, $data, $options);
    }

    protected function post($url, $data = [], $options = [])
    {
        return $this->json('POST', $url, $data, $options);
    }

    protected function put($url, $data = [], $options = [])
    {
        return $this->json('PUT', $url, $data, $options);
    }

    protected function patch($url, $data = [], $options = [])
    {
        return $this->json('PATCH', $url, $data, $options);
    }

    protected function delete($url, $data = [], $options = [])
    {
        return $this->query('DELETE', $url, $data, $options);
    }

    protected function query($method, $url, $data, $options)
    {
        $options = array_merge($options, ['query' => $data]);

        return $this->request($method, $url, $data ? $options : []);
    }

    protected function json($method, $url, $data, $options)
    {
        $options = array_merge($options, ['json' => $data]);

        return $this->request($method, $url, $options);
    }

    /**
     * @param $method
     * @param $url
     * @param $options
     * @return Exception|RequestException|ResponseInterface|null
     * @throws MicroservicesException
     * @throws GuzzleException
     */
    protected function request($method, $url, $options)
    {
        try {
            $url = $this->prepareUrl($url);

            $response = $this->client->request($method, $url, $options);

            if ($response->getStatusCode() !== Response::HTTP_OK) {
                $this->syncException($response);
            }

            $data = json_decode($response->getBody()->getContents());
        } catch (RequestException $exception) {
            if ($exception->hasResponse()) {
                $response = $exception->getResponse();
                $this->syncException($response);
            }
            // TODO: write log disableSyncException case
            $data = null;
        }

        return $data;
    }

    /**
     * @param  ResponseInterface  $response
     * @throws MicroservicesException
     */
    protected function syncException(ResponseInterface $response)
    {
        if (!$this->syncException) {
            return;
        }

        $statusCode = $response->getStatusCode();
        $data = json_decode($response->getBody()->getContents());

        throw new MicroservicesException($data, $statusCode);
    }

    protected function prepareUrl($url)
    {
        if (Str::startsWith($url, 'http')) {
            return $url;
        }

        $url = trim($url, '/');

        // append default version v1
        if (!preg_match('/^v[2-9]/', Str::substr($url, 0, 2))) {
            $url = 'v1/' . $url;
        }

        return $url;
    }

    public function disableSyncException()
    {
        $this->syncException = false;
    }
}
