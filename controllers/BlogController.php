<?php declare(strict_types=1);

namespace f7k\Controller;

use f7k\Sources\attributes\{Route};
use f7k\Sources\{Request, Response};

class BlogController extends AppController {

    public function __construct()
    {
        parent::__construct();

        $this->template->assign([
            'title' => 'Blog',
            "href_reply" => "/Blog/reply?id=",
            'route' => '/Blog'
        ]);
    }

    #[Route(path: '/Blog', method: 'get')]
    public function main(Request $request, Response $response): void
    {
        $this->template->assign([
            "comments" => $this->comments->readAll("/Blog")
        ]);
        $this->template->parse('Blog.partial.html');
        $this->template->render($request, $response);
    }

    #[Route(path: '/Blog/reply', method: 'get')]
    public function reply(Request $request, Response $response): void
    {
        $data = $request->getData();
        $this->template->assign([
            'reply' => true,
            'comments_header' => 'Reply to #' . $data['id'],
            'comment_id' => $data['id'],
            "href_cancel" => "/Blog#comments",
            "comments" => [$this->comments->read((int) $data['id'])]
        ]);
        $this->template->parse('Blog.partial.html');
        $this->template->render($request, $response);
    }
}