<?php

namespace Velsym\Routing;

function preDump(mixed $obj): void
{
    echo "<pre>";
    var_dump($obj);
    echo "</pre>";
}