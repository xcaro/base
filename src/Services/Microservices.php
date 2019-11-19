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

    protected function baseUri()
    {
        $host = $this->getHost();

        return Str::finish($host, '/');
    }

    abstract protected function getHost();

    public function syncAuthorization()
    {
        $this->options['headers']['Authorization'] = request()->header('Authorization');

        return $this;
    }

    /**
     * @param $url
     * @param  array  $data
     * @param  array  $options
     * @return mixed|null
     * @throws GuzzleException
     * @throws MicroservicesException
     */
    protected function get($url, $data = [], $options = [])
    {
        return $this->query('GET', $url, $data, $options);
    }

    /**
     * @param $url
     * @param  array  $data
     * @param  array  $options
     * @return mixed|null
     * @throws GuzzleException
     * @throws MicroservicesException
     */
    protected function post($url, $data = [], $options = [])
    {
        return $this->json('POST', $url, $data, $options);
    }

    /**
     * @param $url
     * @param  array  $data
     * @param  array  $options
     * @return mixed|null
     * @throws GuzzleException
     * @throws MicroservicesException
     */
    protected function put($url, $data = [], $options = [])
    {
        return $this->json('PUT', $url, $data, $options);
    }

    /**
     * @param $url
     * @param  array  $data
     * @param  array  $options
     * @return mixed|null
     * @throws GuzzleException
     * @throws MicroservicesException
     */
    protected function patch($url, $data = [], $options = [])
    {
        return $this->json('PATCH', $url, $data, $options);
    }

    /**
     * @param $url
     * @param  array  $data
     * @param  array  $options
     * @return mixed|null
     * @throws GuzzleException
     * @throws MicroservicesException
     */
    protected function delete($url, $data = [], $options = [])
    {
        return $this->query('DELETE', $url, $data, $options);
    }

    /**
     * @param $method
     * @param $url
     * @param $data
     * @param $options
     * @return mixed|null
     * @throws GuzzleException
     * @throws MicroservicesException
     */
    protected function query($method, $url, $data, $options)
    {
        $options = array_merge($options, ['query' => $data]);

        return $this->request($method, $url, $options);
    }

    /**
     * @param $method
     * @param $url
     * @param $data
     * @param $options
     * @return mixed|null
     * @throws GuzzleException
     * @throws MicroservicesException
     */
    protected function json($method, $url, $data, $options)
    {
        $options = array_merge($options, ['json' => $data]);

        return $this->request($method, $url, $options);
    }

    /**
     * @param $method
     * @param $url
     * @param $options
     * @return mixed|null
     * @throws GuzzleException
     * @throws MicroservicesException
     */
    protected function request($method, $url, $options)
    {
        try {
            $url = $this->prepareUrl($url);
            $this->syncAuthorization();
            $options = array_merge($this->options, $options);

            $response = $this->client->request($method, $url, $options);
            if ($response->getStatusCode() !== Response::HTTP_OK) {
                $data = json_decode($response->getBody()->getContents());
                throw new MicroservicesException($data, $response->getStatusCode());
            }

            $data = json_decode($response->getBody()->getContents());
        } catch (RequestException $exception) {
            $this->syncException($exception);
            // TODO: write log disableSyncException case
            $data = null;
        }

        return $data;
    }

    /**
     * @param  RequestException  $exception
     * @throws MicroservicesException
     */
    protected function syncException(RequestException $exception)
    {
        if (!$this->syncException) {
            return;
        }

        $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        $message    = $exception->getMessage();
        $data       = null;

        if ($exception->hasResponse()) {
            $response   = $exception->getResponse();
            $statusCode = $response->getStatusCode();
            $data       = json_decode($response->getBody()->getContents());
        }

        throw new MicroservicesException($data, $statusCode, $message);
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

        return $this;
    }
}
