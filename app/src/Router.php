<?php

declare(strict_types=1);

namespace App;

use App\Exceptions\RouteNotFoundException;

class Router
{
    protected array $routes;

    public function __construct(
        protected Container $container
    )
    {

    }

    public function get(string $route, callable|array $action): self
    {
        $this->register($route, 'GET', $action);

        return $this;
    }

    public function post(string $route, callable|array $action): self
    {
        $this->register($route, 'POST', $action);

        return $this;
    }

    public function register(string $route, string $method, callable|array $action)
    {
        $this->routes[$method][$route] = $action;
    }

    public function routes(): array
    {
        return $this->routes;
    }

    public function resolve(string $requestUri, string $requestMethod): Response
    {
        [$route] = explode('?', $requestUri);

        $action = $this->routes[$requestMethod][$route] ?? null;

        if (null == $action){
            throw new RouteNotFoundException();
        }

        if (is_callable($action)){
            return call_user_func($action);
        }

        [$class, $method] = $action;

        if (class_exists($class)){
            $controller = $this->container->get($class);

            if (method_exists($controller, $method)) {
                return call_user_func_array([$controller, $method], []);
            }
        }

        throw new RouteNotFoundException();
    }
}