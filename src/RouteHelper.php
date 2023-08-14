<?php

namespace Velsym\Routing;

use Velsym\Routing\Communication\Request;
use Velsym\Routing\Communication\Response;

abstract class RouteHelper
{
    protected ?Request $request = NULL;
    protected ?Response $response = NULL;

    public function _v_http_init(): void
    {
        if($this->request === NULL) $this->request = Request::get();
        if($this->response === NULL) $this->response = new Response();
    }

    protected function render(string $output): Response
    {
        $this->response->setBody($output);
        return $this->response;
    }
}