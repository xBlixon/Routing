<?php

namespace Velsym\Routing;

use Velsym\Routing\Communication\Response;
use Velsym\Routing\Communication\Session;

abstract class RouteHelper
{
    protected ?Response $response = NULL;
    protected ?Session $session = NULL;

    public function _v_http_init(): void
    {
        if($this->session === NULL) $this->session = Session::getInstance();
        if($this->response === NULL) $this->response = new Response();
    }

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