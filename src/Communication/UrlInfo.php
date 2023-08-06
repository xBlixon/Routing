<?php

namespace Velsym\Routing\Communication;

use function Velsym\Routing\preDump;

readonly class UrlInfo
{
    public string $path;
    public array  $query;

    public function __construct(string $URI)
    {
        $url = parse_url($URI);
        $this->path = $url['path'];
        parse_str($url['query'], $parsedQuery);
        $this->query = $parsedQuery ?? [];
    }
}