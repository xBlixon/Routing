<?php

namespace Velsym\Routing\Attributes;

use Attribute;

#[Attribute]
class Route
{
    private array $params;
    public function __construct(string $path, array $methods)
    {
        $this->params = [
            'path' => $path,
            'methods' => $methods
        ];
    }

    public function setName(string $name): void
    {
        $this->params['name'] ??= $name;
    }

    public function getParams(): array
    {
        return $this->params;
    }
}