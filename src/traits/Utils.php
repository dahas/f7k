<?php

namespace PHPSkeleton\Sources\traits;

trait Utils {

    public function germanDecimalNumberFormat(int|float $input): string
    {
        return number_format($input, 2, ',', '.');
    }

    public function sanitizeRequest(array $request): array
    {
        return array_map(function ($var) {
            $allowedTags = '<div><a><br><p><span><img><b><hr>';
            return strip_tags($var, $allowedTags);
        }, $request);
    }
}