<?php

namespace App\Core\Service;

use Elastic\Elasticsearch\Client;

class ElasticsearchService
{
    public function __construct(
        private Client $client
    ) {
    }

    public function index(string $index, string $id, array $data): void
    {
        $this->client->index([
            'index' => $index,
            'id' => $id,
            'body' => $data,
        ]);
    }

    public function delete(string $index, string $id): void
    {
        $this->client->delete([
            'index' => $index,
            'id' => $id,
        ]);
    }
}
