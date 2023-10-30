<?php

declare(strict_types=1);

namespace App\Consumers;

use App\Config;
use App\Entities\UrlStatistics;
use App\Repositories\UrlsStatisticsRepository;
use App\ClickHouseRepositories\UrlsStatisticsRepository as CHUrlsStatisticsRepository;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;

class SimpleConsumer implements ConsumerInterface
{
    protected AMQPStreamConnection $connection;

    public function __construct(
        private UrlsStatisticsRepository $urlsStatisticsRepository,
        private CHUrlsStatisticsRepository $churlsStatisticsRepository,
        Config $config
    )
    {
        $this->connection = new AMQPStreamConnection(
            $config->amqp['host'],
            $config->amqp['port'],
            $config->amqp['user'],
            $config->amqp['password'],
        );
    }

    public function consume(string $exchange, string $routingKey, string $queue, string $consumerTag):void
    {
        $channel = $this->connection->channel();
        $channel->queue_declare($queue, false, true, false, false);
        $channel->exchange_declare($exchange, AMQPExchangeType::DIRECT, false, true, false);

        $channel->queue_bind($queue, $exchange, $routingKey);

        $channel->basic_consume($queue, $consumerTag, false, false, false, false, \Closure::fromCallable([$this, 'processMessage']));

        /**
         * @param \PhpAmqpLib\Channel\AMQPChannel $channel
         * @param \PhpAmqpLib\Connection\AbstractConnection $connection
         */
        $shutdown = function($channel, $connection)
        {
            $channel->close();
            $connection->close();
        };

        register_shutdown_function($shutdown, $channel, $this->connection);

        $channel->consume();
    }

    protected function processMessage(AMQPMessage $message):void
    {
        echo "\n--------\n";
        echo $message->body;
        echo "\n--------\n";

        $length = $this->getUrlContentLength($message->body);

        $urlStat = new UrlStatistics();
        $urlStat
            ->setUrl($message->body)
            ->setLength($length)
            ->setCreatedAt(new \DateTime());

        $this->urlsStatisticsRepository->insert($urlStat);
        $this->churlsStatisticsRepository->insert($urlStat);

        $message->ack();

        // Send a message with the string "quit" to cancel the consumer.
        if ($message->body === 'quit') {
            $message->getChannel()->basic_cancel($message->getConsumerTag());
        }
    }

    protected function getUrlContentLength(string $url): int
    {
        $context  = stream_context_create(['http' => [
            'method'=>'GET',
            'header' =>
                "Accept: text/html,application/xhtml+xml,application/xml\r\n"
                ."Accept-Language: en-US,en\r\n"
                ."User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36\r\n"
        ]]);

        set_error_handler(function() { });
        $fd = fopen('https://' . trim($url), 'rb', false, $context);
        restore_error_handler();


        if ($fd) {
            //$responseHeaders = stream_get_meta_data($fd);
            $content = stream_get_contents($fd); // not all servers return Content-Length header, so lets get content and find out it's length

            fclose($fd);
            return strlen($content);
        }
        return 0;
    }

    public function __destruct()
    {
        $this->connection->close();
    }
}