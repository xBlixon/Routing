<?php

namespace Velsym\Routing;

use ReflectionClass;
use ReflectionMethod;
use Velsym\DependencyInjection\DependencyManager;
use Velsym\Routing\Attributes\Middleware;
use Velsym\Routing\Attributes\Route;
use Velsym\Communication\Request;
use Velsym\Communication\Response;

class Router
{
    private array $routes = [];
    private array $routeNameToPath = [];

    public function __construct(
        private readonly string $absoluteRoutesPath,
        private readonly string $routesNamespace
    )
    {
        $dir = scandir($this->absoluteRoutesPath);
        array_shift($dir);
        array_shift($dir);

        foreach ($dir as $routeClass) {
            $routeClass = pathinfo($routeClass, PATHINFO_FILENAME);
            $reflectionClass = new ReflectionClass($this->routesNamespace . $routeClass);
            $routes = $reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC);
            $this->registerManyRoutes($routes);
        }
    }

    /** @param ReflectionMethod[] $routes */
    private function registerManyRoutes(array $routes): void
    {
        foreach ($routes as $route) {
            $this->registerRoute($route);
        }
    }

    private function registerRoute(ReflectionMethod $route): void
    {
        if (!$routeAttribute = $route->getAttributes(Route::class)[0] ?? NULL) return;
        /** @var Route $routeAttributeObject */
        $routeAttributeObject = $routeAttribute->newInstance();
        $routeAttributeObject->setName($route->getName());
        $params = $routeAttributeObject->getParams();
        foreach ($params['methods'] as $method) {
            $this->routes[$method][$params['path']] =
                [
                    'class' => $route->class,
                    'name' => $route->name
                ];
            $this->routeNameToPath[$params['name']] = $params['path'];
        }
    }

    private function useMiddleware(ReflectionMethod $route)
    {
        $middlewares = $route->getAttributes(Middleware::class);
        foreach ($middlewares as $middleware) {
            $middleware->newInstance();
        }
    }

    public function handle(): void
    {
        $handler = $this->routes[Request::method()][Request::url()->path] ?? NULL;
        if (!$handler) {
            http_response_code(404);
            echo "No route matching url.";
            die;
        }
        $this->useMiddleware(new ReflectionMethod($handler['class'], $handler['name']));
        /** @var RouteHelper $class */
        $class = DependencyManager::resolveClassToInstance($handler['class']);
        $class->_v_http_init();
        /** @var Response $response */
        $response = DependencyManager::callMethodWithResolvedArguments($class, $handler['name']);

        if (!$response) {
            echo "Response class wasn't returned from '{$handler['name']}' route.";
        }
        http_response_code($response->getResponseCode());
        if ($routeName = $response->getRedirectRouteName()) {
            $routePath = $this->routeNameToPath[$routeName];
            header("Location: $routePath");
        }
        $this->useHeaders($response->getHeaders());
        echo $response->getBody();
    }

    public function dumpRoutes(): void
    {
        var_dump($this->routes);
        var_dump($this->routeNameToPath);
        exit;
    }

    private function useHeaders(array $headers): void
    {
        foreach ($headers as $header => $value) {
            header("$header: $value");
        }
    }
}