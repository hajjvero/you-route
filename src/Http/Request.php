<?php

namespace Http;

class Request extends \AbstractRequest
{
    public function getPathInfo():string
    {
        return $_SERVER['PATH_INFO'] ?: "/";
    }
}