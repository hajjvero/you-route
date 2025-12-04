<?php

namespace Trait;

use Attribute\Route;
use Http\Response;
use ReflectionException;
use ReflectionMethod;

trait TraitRoute
{
    /**
     * Prepare route
     * @throws ReflectionException
     */
    protected function prepareRoute():void
    {
        //  prepare route pour check class
        foreach ($this->prepareReflections() as $reflection) {
            // check si class est abstract
            if($reflection->getModifiers() === \ReflectionClass::IS_EXPLICIT_ABSTRACT) {
                throw new ReflectionException("The abstract class {$reflection->getName()} not access in system route");
            }

            // Créer instance attribute route du class s'il existe
            $routeController = array_find($reflection->getAttributes(), static fn($attribute) => $attribute->getName() === Route::class)?->newInstance();

            // prepare route method
            foreach ($reflection->getMethods() as $method) {
                /**
                 * Créer instance attribute route du method s'il existe
                 * @var ?Route $routeMethod
                 */
                $routeMethod = array_find($method->getAttributes(), static fn($attribute) => $attribute->getName() === Route::class)?->newInstance();

                if (!$routeMethod) {
                    continue;
                }

                // Ajouter action dans instance route avec class et method
                $routeMethod->setAction([$method->class, $method->name]);

                // check modifier
                if ($method->getModifiers() !== ReflectionMethod::IS_PUBLIC) {
                    throw new ReflectionException("The modifier of {$method->class}::{$method->getName()} not access in system route");
                }

                // check return type
                if ($method->getReturnType()?->getName() !== Response::class) {
                    throw new ReflectionException("The return type of {$method->class}::{$method->getName()} not response in system route");
                }

                // Ajouter route avec prefix si existe
                $this->addRoute($this->prefix($routeController, $routeMethod));
            }
        }
    }

    /**
     * Ajouter route dans la liste des routes
     * @param Route $route
     * @return void
     */
    protected function addRoute(Route $route):void
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

    /**
     * Function pour ajouter prefix route
     * @param Route|null $routeController
     * @param Route $routeMethod
     * @return Route
     */
    private function prefix(?Route $routeController, Route $routeMethod): Route
    {
        // check si prefix route
        if ($routeController)
        {
            $routeMethod->setPath($routeController->getPath().$routeMethod->getPath());
        }

        return $routeMethod;
    }
}