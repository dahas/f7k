<?php declare(strict_types=1);

namespace f7k\Sources\attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]  // <-- Turns the class into an attribute that can be attached to a method
class Route
{
    public function __construct(
        public string|array $path = "/",
        public string|array $method = "get"
    )
    {
    }

    public function getPaths(): array
    {
        return is_string($this->path) ? [$this->path] : $this->path;
    }

    public function getMethods(): array
    {
        return is_string($this->method) ? [$this->method] : $this->method;
    }
}