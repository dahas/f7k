<?php

namespace PHPSkeleton\Sources\traits;

trait Utils 
{
    public function germanDecimalNumberFormat(int|float $input) : string {
        return number_format($input, 2, ',', '.');
    }
}