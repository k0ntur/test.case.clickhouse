<?php

declare(strict_types=1);

require_once '../vendor/autoload.php';

use App\Container;
use App\App;
use App\Router;
use App\Controllers\HomeController;
use Dotenv\Dotenv;
use App\Config;
use App\DB;
use App\DBCH;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$container = new Container();
$container->set(Router::class, fn(Container $c) => new Router($c), true);
$container->set(DB::class, fn(Container $c) => new DB($c->get(Config::class)), true);
$container->set(DBCH::class, fn(Container $c) => new DBCH($c->get(Config::class)), true);

$router = $container->get(Router::class);

$router
    ->get('/', [HomeController::class, 'index']);

$app = new App($container);

$app
    ->bootstrap()
    ->run($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);