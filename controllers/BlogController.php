<?php declare(strict_types=1);

namespace f7k\Controller;

use f7k\Sources\attributes\{Route};
use f7k\Sources\{Request, Response};

class BlogController extends AppController {

    public function __construct(protected Request $request, protected Response $response)
    {
        parent::__construct($request, $response);

        $this->template->assign([
            'title' => 'Blog',
            "href_reply" => "/Blog/reply?id=",
            'route' => '/Blog'
        ]);
    }

    #[Route(path: '/Blog', method: 'get')]
    public function main(): void
    {
        $this->template->assign([
            "comments" => $this->comments->readAll("/Blog")
        ]);
        $this->template->parse('Blog.partial.html');
        $this->template->render($this->request, $this->response);
    }

    #[Route(path: '/Blog/reply', method: 'get')]
    public function reply(): void
    {
        $this->template->assign([
            'reply' => true,
            'comments_header' => 'Reply to #' . $this->data['id'],
            'comment_id' => $this->data['id'],
            "href_cancel" => "/Blog#comments",
            "comments" => [$this->comments->read((int) $this->data['id'])]
        ]);
        $this->template->parse('Blog.partial.html');
        $this->template->render($this->request, $this->response);
    }
}