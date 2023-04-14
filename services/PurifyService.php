<?php

namespace f7k\Service;

use \HTMLPurifier_Config;
use \HTMLPurifier;

class PurifyService {

    private HTMLPurifier $purifier;

    public function __construct(private array|null $options = [])
    {
        $config = HTMLPurifier_Config::createDefault();
        $config->set('HTML.Trusted', true);
        $config->set('Filter.YouTube', true);
        $this->purifier = new HTMLPurifier($config);
    }

    public function purify(string $value): string
    {
        return $this->purifier->purify($value);
    }
}