<?php

namespace YouRoute\Http;

use YouRoute\Http\Abstract\AbstractRequest;

class Request extends AbstractRequest
{
    public function getPathInfo():string
    {
        return $_SERVER['PATH_INFO'] ?: "/";
    }
}