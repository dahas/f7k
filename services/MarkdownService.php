<?php

namespace f7k\Service;

use f7k\Sources\attributes\Inject;
use f7k\Service\PurifyService;
use f7k\Sources\ServiceBase;
use \Parsedown;

class MarkdownService extends ServiceBase {

    #[Inject(PurifyService::class)]
    protected $purifier;

    private Parsedown $parsedown;

    public function __construct(private array|null $options = [])
    {
        parent::__construct();

        $escaped = $options['escaped'] ?? false;

        $this->parsedown = new Parsedown();
        $this->parsedown->setMarkupEscaped($escaped);
    }

    public function parse(string $value): string
    {
        $markdown = $this->parsedown->text($value); 
        return $this->purifier->purify($markdown);
    }
}