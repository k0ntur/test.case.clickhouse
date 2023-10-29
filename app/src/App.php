<?php

declare(strict_types=1);

namespace App;

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