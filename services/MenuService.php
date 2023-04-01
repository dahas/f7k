<?php

namespace f7k\Service;

class MenuService {

    public function __construct(private array|null $options = [])
    {

    }

    public function getItems(): object
    {
        $json = file_get_contents(ROOT . '/menu.json');
        return (object) json_decode($json);
    }
}