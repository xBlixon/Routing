<?php

namespace Velsym\Routing\Communication;

class Request
{
    private static ?Request $instance = NULL;
    public string $method;
    public UrlInfo $url;

    private function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->url = new UrlInfo($_SERVER['REQUEST_URI']);
    }

    public static function getInstance(): self
    {
        if (self::$instance === NULL) {
            self::$instance = new static();
        }
        return self::$instance;
    }
}