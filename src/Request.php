<?php

namespace PHPSkeleton\Sources;

final class Request {

    private string $method;
    private array $data = [];

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setMethod(string $method): void
    {
        $this->method = $method;
    }

    public function setData(array $data): void
    {
        $this->data = $data;
    }
}