<?php
use Velsym\Routing\Router;

require "vendor/autoload.php";

$router = new Router(__DIR__. "/test-routes", "Velsym\\TestRoutes\\");

if(isset($argv) && in_array("--dump-routes", $argv))
{
    $router->dumpRoutes();
}

$router->handle();
