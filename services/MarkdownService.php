<?php

namespace f7k\Service;

use f7k\Sources\attributes\Inject;
use f7k\Service\PurifyService;
use \Parsedown;

class MarkdownService  {

    #[Inject(PurifyService::class, [
        'HTML.ForbiddenElements' => ['img', 'iframe', 'a', 'script']
    ])]
    protected $purifier;

    private Parsedown $parsedown;

    public function __construct(private array|null $options = [])
    {
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