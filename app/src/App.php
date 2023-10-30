<?php

declare(strict_types=1);

namespace App;

use App\Controllers\HomeController;
use App\Exceptions\RouteNotFoundException;

class App
{
    public function __construct(
        protected Container $container
    )
    {
    }

    public function bootstrap(): self
    {
        $this->container->set(Router::class, fn(Container $c) => new Router($c), true);
        $this->container->set(DB::class, fn(Container $c) => new DB($c->get(Config::class)), true);
        $this->container->set(DBCH::class, fn(Container $c) => new DBCH($c->get(Config::class)), true);

        $router = $this->container->get(Router::class);

        $router
            ->get('/', [HomeController::class, 'index']);

        return $this;
    }

    public function run(string $requestUri, string $requestMethod): Response
    {
        try {
            $response = $this->container->get(Router::class)->resolve($requestUri, $requestMethod);
            $response->send();
        } catch (RouteNotFoundException){
            (new Response('Not Found', 404))->send();
        }

        exit;
    }
}