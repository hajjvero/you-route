<?php

namespace YouRoute;
use Exception;
use YouRoute\Http\Request;
use ReflectionException;
use YouRoute\Router\RouteCollection;
use YouRoute\Router\RouteDispatcher;
use YouRoute\Router\RouteResolver;

final class YouRouteKernel
{
    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function run(string $resourceDir, Request $request): void
    {
        $routeCollection = new RouteCollection();
        $routeResolver  = new RouteResolver($routeCollection);
        $routeDispatcher = new RouteDispatcher($routeCollection);

        $routeResolver->loadRoutesFromDirectory($resourceDir);

        $routeDispatcher->dispatch($request->method, $request->uri);
    }
}