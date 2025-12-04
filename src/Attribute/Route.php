<?php

namespace Attribute;

#[\Attribute]
final class Route
{
    /**
     * @var string $path le chemin de la route
     */
    private string $path;

    /**
     * @var string $name le nom de la route
     */
    private string $name;

    /**
     * @var string|array $methods les méthodes de la route
     */
    private string|array $methods;

    /**
     * @var array $action l'action de la route (le controller avec la méthode)
     */
    private array $action;

    /**
     * @param string $name le nom de la route
     * @param string $path le chemin de la route
     * @param string|array $methods les méthodes de la route
     */
    public function __construct(string $path,string $name="", string|array $methods="GET")
    {
        $this->name = $name;
        $this->path = $path;
        $this->methods = $methods;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $path
     */
    public function setPath(string $path): void
    {
        $this->path = !str_ends_with($path,"/") ? $path."/" : $path;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return array|string
     */
    public function getMethods(): array|string
    {
        return strtoupper($this->methods);
    }

    /**
     * @return array
     */
    public function getAction(): array
    {
        return $this->action;
    }

    /**
     * @param array $action
     */
    public function setAction(array $action): void
    {
        $this->action = $action;
    }
}