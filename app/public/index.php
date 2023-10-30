<?php

declare(strict_types=1);

require_once dirname(__DIR__).'/vendor/autoload.php';

use App\Container;
use App\App;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$app = new App(new Container());

$app
    ->bootstrap()
    ->run($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);