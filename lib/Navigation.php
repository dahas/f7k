<?php

namespace PHPSkeleton\Library;

class Navigation {

    public function __construct(private array|null $options = [])
    {

    }

    public function items(): object
    {
        $json = file_get_contents(ROOT . '/menu.json');
        return (object) json_decode($json);
    }
}