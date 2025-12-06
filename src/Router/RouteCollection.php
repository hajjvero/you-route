<?php

namespace YouRoute\Router;

use YouRoute\Attribute\Route;

class RouteCollection
{
    private static array $routes = [];

    public function add(Route $route): void
    {
        // check si plusieurs method
        if (is_array($route->getMethods()))
        {
            foreach ($route->getMethods() as $method) {
                self::$routes[$method][] = $route;
            }
            return;
        }

        self::$routes[$route->getMethods()][] = $route;
    }

    public function all(): array
    {
        return self::$routes;
    }

    public function prefix(?Route $routeClass, Route $routeMethod): Route
    {
        // check si prefix route
        if ($routeClass)
        {
            $routeMethod->setPath($routeClass->getPath().$routeMethod->getPath());
        }

        return $routeMethod;
    }
}