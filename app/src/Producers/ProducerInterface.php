<?php

declare(strict_types=1);

namespace App\Producers;

interface ProducerInterface
{
    public function publish(string $message, string $exchange, string $queue, string $routingKey):void;
}