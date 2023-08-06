<?php

namespace Velsym\Routing;
use ReflectionClass;
use ReflectionMethod;
use Velsym\Routing\Attributes\Route;
use Velsym\Routing\Communication\Request;
use Velsym\Routing\Communication\Response;

class Router
{
    private array $routes = [];
    private array $routeNameToPath = [];
    private Request $request;

    public function __construct(
        private readonly string $absoluteRoutesPath,
        private readonly string $routesNamespace
    ){
        $this->request = Request::get();
        $dir = scandir($this->absoluteRoutesPath);
        array_shift($dir);
        array_shift($dir);

        foreach ($dir as $routeClass)
        {
            $routeClass = str_replace(".php", "", $routeClass);
            $reflectionClass = new ReflectionClass($this->routesNamespace . $routeClass);
            $routes = $reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC);
            $this->registerManyRoutes($routes);
        }
    }

    /** @param ReflectionMethod[] $routes */
    private function registerManyRoutes(array $routes): void
    {
        foreach ($routes as $route)
        {
            $this->registerRoute($route);
        }
    }

    private function registerRoute(ReflectionMethod $route): void
    {
        if(!$routeAttribute = $route->getAttributes("Velsym\\Routing\\Attributes\\Route")[0])
        {
            return;
        }
        /**
         * @var Route $routeAttribute
         */
        $routeAttribute = $routeAttribute->newInstance();
        $routeAttribute->setName($route->getName());
        $params = $routeAttribute->getParams();
        foreach ($params['methods'] as $method) {
            $this->routes[$method][$params['path']] = [
                'class' => $route->class,
                'name' => $route->name
            ];
            $this->routeNameToPath[$params['name']] = $params['path'];
        }
    }

    public function handle(): void
    {
        $handler = $this->routes[$this->request->method][$this->request->url->path] ?? NULL;
        if(!$handler)
        {
            http_response_code(404);
            echo "No route matching url.";
            die;
        }
        $class = new $handler['class']();
        /** @var Response $response */
        $response = $class->{$handler['name']}();

        if(!$response)
        {
            echo "Response class wasn't returned from '{$handler['name']}' route.";
        }
        http_response_code($response->getResposeCode());
        $this->useHeaders($response->getHeaders());
        echo $response->getBody();
    }

    public function dumpRoutes(): void
    {
        var_dump($this->routes);
        var_dump($this->routeNameToPath);
        exit;
    }

    public function useHeaders(array $headers): void
    {
        foreach ($headers as $header => $value)
        {
            header("$header $value");
        }
    }
}