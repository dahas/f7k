<?php

namespace PHPSkeleton\Library;

class Navigation {

    public function items(): object
    {
        $json = file_get_contents(ROOT . '/menu.json');
        return (object) json_decode($json);
    }
}