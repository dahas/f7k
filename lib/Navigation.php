<?php

namespace PHPSkeleton\Library;

class Navigation {

    private static array $items = [
        "/" => "Home",
        "/Blog" => "Blog",
        "/Error" => "No Controller"
    ];

    public static function items(): array
    {
        return self::$items;
    }
}