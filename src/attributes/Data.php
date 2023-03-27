<?php

namespace PHPSkeleton\Sources\attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]  // <-- Turns the class into an attribute
class Data
{
    public function __construct(
        public string $table,
        public string $key,
        public string $fields,
    ) {
    }
}
