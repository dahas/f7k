<?php declare(strict_types=1);

namespace f7k\Controller;

use f7k\Component\CommentsComponent;
use f7k\Sources\attributes\{Route};
use f7k\Sources\{Request, Response};

class BlogController extends AppController {

    private CommentsComponent $comments;

    public function __construct(protected Request $request, protected Response $response)
    {
        parent::__construct($request, $response);

        $this->template->assign([
            'title' => 'Blog'
        ]);

        $this->comments = new CommentsComponent('/Blog', $this->request, $this->response);
    }

    #[Route(path: '/Blog', method: 'get')]
    public function main(): void
    {
        $this->template->assign([
            "comments" => $this->comments->fetchAll()
        ]);
        $this->template->parse('Blog.partial.html');
        $this->template->render($this->request, $this->response);
    }

    #[Route(path: '/Blog/reply', method: 'get')]
    public function reply(): void
    {
        $this->template->assign([
            "comments" => $this->comments->fetchItem((int) $this->data['id'])
        ]);
        $this->template->parse('Blog.partial.html');
        $this->template->render($this->request, $this->response);
    }

    #[Route(path: '/Blog/Comments/create', method: 'post')]
    public function createComment(): void
    {
        $this->comments->create($this->data);
    }

    #[Route(path: '/Blog/Comments/hide', method: 'get')]
    public function hideComment(): void
    {
        $this->comments->hide((int) $this->data['id']);
    }

    #[Route(path: '/Blog/Comments/delete', method: 'get')]
    public function deleteComment(): void
    {
        $this->comments->delete((int) $this->data['id']);
    }

    #[Route(path: '/Blog/Reply/create', method: 'post')]
    public function createReply(): void
    {
        $this->comments->createReply($this->data);
    }

    #[Route(path: '/Blog/Reply/hide', method: 'get')]
    public function hideReply(): void
    {
        $this->comments->hideReply((int) $this->data['id']);
    }

    #[Route(path: '/Blog/Reply/delete', method: 'get')]
    public function deleteReply(): void
    {
        $this->comments->deleteReply((int) $this->data['id']);
    }
}