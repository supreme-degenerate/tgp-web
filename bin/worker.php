<?php

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use PhpAmqpLib\Connection\AMQPStreamConnection;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$connection = new AMQPStreamConnection($_ENV['QUEUE_HOST'], $_ENV['QUEUE_PORT'], $_ENV['QUEUE_USER'], $_ENV['QUEUE_PASSWORD']);
$channel = $connection->channel();

$channel->queue_declare('invoice_queue', false, true, false, false);
// TODO prepare a more generic queue worker

echo "Waiting for messages...\n";

$callback = function ($msg) {
    try {
        $data = json_decode($msg->body, true);

        echo "Processing invoice...\n";

        // TODO process invoice queue
        // ...

        $msg->ack();
    } catch (Throwable $e) {
        echo "Error: " . $e->getMessage() . "\n";

        $msg->nack();
    }
};

$channel->basic_consume('invoice_queue', '', false, false, false, false, $callback);

while ($channel->is_consuming()) {
    $channel->wait();
}
