<?php

namespace f7k\Service;

use f7k\Sources\attributes\Inject;
use f7k\Service\PurifyService;
use f7k\Sources\{ServiceBase, Request, Response, Session};
use \Parsedown;

class MarkdownService extends ServiceBase  {

    #[Inject(PurifyService::class)]
    protected $purifier;

    private Parsedown $parsedown;

    public function __construct(
        protected Request $request, 
        protected Response $response, 
        protected Session $session
    ) {
        parent::__construct($this->request, $this->response, $this->session);

        $this->parsedown = new Parsedown();
        $this->parsedown->setMarkupEscaped(false);
    }

    public function parse(string $value): string
    {
        $markdown = $this->parsedown->text($value); 
        return $this->purifier->purify($markdown);
    }
}