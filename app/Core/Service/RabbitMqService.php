<?php

namespace App\Core\Service;

use Nette\Utils\Json;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMqService
{
    public function __construct(
        private string $host,
        private int $port,
        private string $user,
        private string $password
    ) {
    }

    public function publish(string $queue, array $data): void
    {
        $connection = new AMQPStreamConnection(
            $this->host,
            $this->port,
            $this->user,
            $this->password
        );

        $channel = $connection->channel();
        $channel->queue_declare($queue, false, true, false, false);

        $msg = new AMQPMessage(
            Json::encode($data),
            ['delivery_mode' => 2]
        );

        $channel->basic_publish($msg, '', $queue);
        $channel->close();
        $connection->close();
    }
}
