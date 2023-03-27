<?php

namespace PHPSkeleton\Sources\attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]  // <-- Turns the class into an attribute that can be attached to a property
class Inject
{
    public function __construct(
        public $service = ""
    )
    {
    }
}
