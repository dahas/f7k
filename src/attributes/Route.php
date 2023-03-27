<?php declare(strict_types=1);

namespace PHPSkeleton\Sources\attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]  // <-- Turns the class into an attribute that can be attached to a method
class Route
{
    public function __construct(
        public string $path = "/",
        public string $method = "get"
    )
    {
    }
}