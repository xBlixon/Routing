<?php

namespace Velsym\Routing;

use Velsym\Routing\Communication\Request;

abstract class RouteHelper
{
    protected Request $request;

    public function __construct()
    {
        $this->request = Request::get();
    }

    protected function render(string $output)
    {
        echo $output;
    }
}