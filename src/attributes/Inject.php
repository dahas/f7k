<?php

namespace f7k\Sources\attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Inject {
    
    public function __construct(
        public string $service = ""
    ) {
    }
}