<?php

declare(strict_types=1);

require_once dirname(__DIR__).'/vendor/autoload.php';

use Dotenv\Dotenv;
use App\Container;
use App\Consumers\ConsumerInterface;
use App\Consumers\SimpleConsumer;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$container = new Container();
$container->set(ConsumerInterface::class, SimpleConsumer::class);
$consumer = $container->get(ConsumerInterface::class);

$exchange = 'callmedia';
$queue = 'urls';
$routingKey = 'url';
$consumerTag = 'callmedia_consumer';

$consumer->consume($exchange, $routingKey, $queue, $consumerTag);
