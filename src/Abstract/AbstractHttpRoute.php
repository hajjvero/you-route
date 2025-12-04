<?php

use Http\Request;
use Trait\TraitHttpReflection;
use Trait\TraitRoute;

abstract class AbstractHttpRoute
{
    use TraitHttpReflection, TraitRoute;

    /**
     * @var string $resourceDir Le répertoire des ressources
     */
    protected string $resourceDir;
    /**
     * @var array $routes La list des routes
     */
    protected static array $routes = [];

    /**
     * @param string $resourceDir Le répertoire des ressources
     */
    abstract public function __construct(string $resourceDir);

    /**
     * @param Request $request La requête
     */
    abstract public function resolve(Request $request): void;
}