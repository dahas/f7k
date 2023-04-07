<?php

namespace f7k\Service;

class MenuService {

    private string $menuFile = 'menu.json';

    public function getItems(): object
    {
        $json = file_get_contents(sprintf(ROOT . '/%s', $this->menuFile));
        if (!$json) {
            throw new \f7k\Sources\exceptions\FileNotFoundException(
                "File not found! '{$this->menuFile}' file must exist in the root directory."
            );
        }
        return (object) json_decode($json);
    }
}