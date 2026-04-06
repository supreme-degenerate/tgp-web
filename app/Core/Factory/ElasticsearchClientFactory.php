<?php

namespace App\Core\Factory;

use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;

class ElasticsearchClientFactory
{
    public function create(): Client
    {
        return ClientBuilder::create()
            ->setHosts(['elasticsearch:9200'])
            ->build();
    }
}
