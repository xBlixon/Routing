<?php

namespace Velsym\Routing\Attributes;

use Attribute;
use Velsym\DependencyInjection\DependencyManager;
use Velsym\Routing\BaseMiddleware;

#[Attribute]
class Middleware
{
    public function __construct(string $middleware, array $params=[])
    {
        if(!is_subclass_of($middleware, BaseMiddleware::class)) return; // Must extend BaseMiddleware
        /** @var BaseMiddleware $middlewareInstance */
        $middlewareInstance = DependencyManager::resolveClassToInstance($middleware, $params);
        $middlewareInstance->_v_http_init();
        $middlewareInstance->handle();
    }
}