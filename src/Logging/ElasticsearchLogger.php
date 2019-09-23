<?php

namespace Si6\Base\Logging;

use Monolog\Logger;
use Monolog\Handler\ElasticsearchHandler;
use Monolog\Formatter\ElasticsearchFormatter;
use Elasticsearch\ClientBuilder;

class ElasticsearchLogger
{
    public function __invoke(array $config)
    {
        dd('test');

        $elasticsearchClient = ClientBuilder::create()
            ->setHosts($config['hosts'])
            ->setSSLVerification($config['ssl_verification'])
            ->build();

        $elasticsearchHandler = new ElasticsearchHandler(
            $elasticsearchClient,
            $config['options']
        );

        $elasticsearchHandler->setFormatter(new ElasticsearchFormatter(
            $config['index'],
            $config['type']
        ));

        return new Logger(
            $config['name'],
            [$elasticsearchHandler]
        );
    }
}