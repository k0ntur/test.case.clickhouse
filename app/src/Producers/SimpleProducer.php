<?php

declare(strict_types=1);

namespace App\Producers;

use App\Config;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;

class SimpleProducer implements ProducerInterface
{
    protected AMQPStreamConnection $connection;

    public function __construct(Config $config)
    {
        $this->connection = new AMQPStreamConnection(
            $config->amqp['host'],
            $config->amqp['port'],
            $config->amqp['user'],
            $config->amqp['password'],
        );
    }

    public function publish(string $message, string $exchange, string $queue, string $routingKey): void
    {
        $channel = $this->connection->channel();
        $channel->queue_declare($queue, false, true, false, false);
        $channel->exchange_declare($exchange, AMQPExchangeType::DIRECT, false, true, false);
        $channel->queue_bind($queue, $exchange, $routingKey);

        $message = new AMQPMessage($message, array('content_type' => 'text/plain', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT));

        $channel->basic_publish($message, $exchange, $routingKey);

        $channel->close();
    }

    public function __destruct()
    {
        $this->connection->close();
    }
}