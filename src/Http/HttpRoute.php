<?php

namespace Http;

class HttpRoute extends \AbstractHttpRoute
{
    public function __construct(string $resourceDir)
    {
        $this->resourceDir = $resourceDir;
    }

    /**
     * @throws \Exception
     */
    public function resolve(Request $request): void
    {
        $this->execute($request->method, $request->uri);
    }
}