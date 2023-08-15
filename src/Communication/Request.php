<?php

namespace Velsym\Routing\Communication;

class Request
{
    private function __construct(){}

    public static function method(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public static function url(): UrlInfo
    {
        return new UrlInfo($_SERVER['REQUEST_URI']);
    }
}