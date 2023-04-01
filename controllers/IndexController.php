<?php declare(strict_types=1);

namespace f7k\Controller;

use f7k\Sources\attributes\{Route};
use f7k\Sources\{Request, Response};

class IndexController extends AppController {

    public function __construct()
    {
        parent::__construct();

        $this->template->assign([
            'title' => 'f7k - The framework',
            'header' => 'f7k - Yet another framework',
            "subtitle" => "f7k is the numeronym of the word 'framework'. Use this lightweight framework to quickly build feature rich web applications with PHP. If you are unfamiliar or 
            inexperienced with developing secure and high-performance web applications, I strongly recommend using Symfony, Laravel, or a similar well tested product.",
            "href_reply" => "/Index/reply?id=",
            'controller' => 'Index',
            'redirect' => '/'
        ]);
    }

    #[Route(path: '/', method: 'get')]
    public function main(Request $request, Response $response): void
    {
        $this->template->assign([
            "comments" => $this->comments->readAll("Index")
        ]);
        $this->template->parse('Index.partial.html');
        $this->template->render($request, $response);
    }

    #[Route(path: '/Index/reply', method: 'get')]
    public function reply(Request $request, Response $response): void
    {
        $data = $request->getData();
        $this->template->assign([
            'reply' => true,
            'comments_header' => 'Reply to #' . $data['id'],
            'comment_id' => $data['id'],
            "href_cancel" => "/#comments",
            "comments" => [$this->comments->read((int) $data['id'])]
        ]);
        $this->template->parse('Index.partial.html');
        $this->template->render($request, $response);
    }
}