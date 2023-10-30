<?php

declare(strict_types=1);

require_once dirname(__DIR__).'/vendor/autoload.php';

use App\Config;
use App\DB;
use App\DBCH;
use Dotenv\Dotenv;
use App\Container;
use App\Producers\ProducerInterface;
use App\Producers\SimpleProducer;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$container = new Container();
$container->set(DB::class, fn(Container $c) => new DB($c->get(Config::class)), true);
$container->set(DBCH::class, fn(Container $c) => new DBCH($c->get(Config::class)), true);
$container->set(ProducerInterface::class, SimpleProducer::class);
$producer = $container->get(ProducerInterface::class);

$exchange = 'callmedia';
$queue = 'urls';
$routingKey = 'url';

$file = fopen(dirname(__DIR__).'/assets/urls.txt', 'r');

while (!feof($file)){
    $url = fgets($file);
    echo $url, ' - published',  PHP_EOL;
    $producer->publish($url, $exchange, $queue, $routingKey);
    sleep(random_int(3, 10));
}


//$producer->publish('quit', $exchange, $queue, $routingKey);

