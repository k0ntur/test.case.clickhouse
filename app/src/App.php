<?php

declare(strict_types=1);

namespace App;

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
        $response = $this->container->get(Router::class)->resolve($requestUri, $requestMethod);
        $response->send();
        exit;
    }
}