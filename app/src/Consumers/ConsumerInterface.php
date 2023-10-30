<?php

declare(strict_types=1);

namespace App\Consumers;

interface ConsumerInterface
{
    public function consume(string $exchange, string $routingKey, string $queue, string $consumerTag):void;
}