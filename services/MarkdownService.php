<?php

namespace f7k\Service;

use \Parsedown;
use \HTMLPurifier_Config;
use \HTMLPurifier;

class MarkdownService {

    private Parsedown $parsedown;
    private HTMLPurifier $purifier;

    public function __construct(private array|null $options = [])
    {
        $escaped = $options['escaped'] ?? false;

        $this->parsedown = new Parsedown();
        $this->parsedown->setMarkupEscaped($escaped);

        $config = HTMLPurifier_Config::createDefault();
        $this->purifier = new HTMLPurifier($config);
    }

    public function parse(string $value): string
    {
        $markdown = $this->parsedown->text($value); 
        return $this->purifier->purify($markdown);
    }
}