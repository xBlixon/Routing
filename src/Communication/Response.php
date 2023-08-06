<?php

namespace Velsym\Routing\Communication;

class Response
{
    private array $headers = [];
    private int $resposeCode = 200;
    private string $body = "";

    public function addHeader(string $header, string $value): self
    {
        $this->headers[$header] = $value;
        return $this;
    }

    public function addHeaders(array $headers): self
    {
        $this->headers = array_merge($this->headers, $headers);
        return $this;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getResposeCode(): int
    {
        return $this->resposeCode;
    }

    public function setResposeCode(int $resposeCode): void
    {
        $this->resposeCode = $resposeCode;
    }
}