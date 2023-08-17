<?php

namespace Velsym\Routing;

use Velsym\Communication\Response;

abstract class RouteHelper
{
    use HttpInitiable;

    protected function render(string $output): Response
    {
        $this->response->setBody($output);
        return $this->response;
    }

    protected function redirectToPath(string $path): Response
    {
        return $this->response->addHeader('Location', $path);
    }

    protected function redirectToRoute(string $routeName): Response
    {
        return $this->response->setRedirectRouteName($routeName);
    }
}