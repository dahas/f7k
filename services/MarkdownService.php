<?php

namespace f7k\Service;

use f7k\Sources\attributes\Inject;
use f7k\Service\PurifyService;
use \Parsedown;

class MarkdownService  {

    #[Inject(PurifyService::class)]
    protected $purifier;

    private Parsedown $parsedown;

    public function __construct()
    {
        $this->parsedown = new Parsedown();
        $this->parsedown->setMarkupEscaped(false);
    }

    public function parse(string $value): string
    {
        $markdown = $this->parsedown->text($value); 
        return $this->purifier->purify($markdown);
    }
}