<?php

namespace Velsym\Routing;

use Velsym\Routing\Communication\Request;
use Velsym\Routing\Communication\Response;

abstract class RouteHelper
{
    protected Request $request;
    protected Response $response;

    public function __construct()
    {
        $this->request = Request::get();
        $this->response = new Response();
    }

    protected function render(string $output): Response
    {
        $this->response->setBody($output);
        return $this->response;
    }
}