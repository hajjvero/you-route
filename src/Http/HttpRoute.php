<?php

namespace Http;

class HttpRoute extends \AbstractHttpRoute
{
    // ===========================================================================
    // Constructor
    // ===========================================================================
    public function __construct(string $resourceDir)
    {
        $this->resourceDir = $resourceDir;
    }

    // ===========================================================================
    // Methods
    // ===========================================================================
    public function resolve(Request $request): void
    {
        // TODO: Implement resolve() method.
    }
}