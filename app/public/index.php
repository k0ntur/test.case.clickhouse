<?php

declare(strict_types=1);

require_once '../vendor/autoload.php';

use App\Container;
use App\App;
use App\Router;
use App\Controllers\HomeController;

$container = new Container();
$container->set(Router::class, fn(Container $c) => new Router($c), true);

$router = $container->get(Router::class);

$router
    ->get('/', [HomeController::class, 'index']);

$app = new App($container);

$app
    ->bootstrap()
    ->run($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);