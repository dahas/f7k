<?php declare(strict_types=1);

namespace f7k\Controller;

use f7k\Library\CommentsService;
use f7k\Sources\attributes\{Inject, Route};
use f7k\Sources\{Request, Response};

class BlogController extends AppController {

    #[Inject(service: CommentsService::class, options: ['page' => 'Blog'])]
    protected $comments;

    #[Route(path: '/Blog', method: 'get')]
    public function main(Request $request, Response $response): void
    {
        $this->template->assign([
            'title' => 'Blog',
            'comments_header' => 'Add a Comment',
            "action" => "/Blog/Comment/create",
            "comments" => $this->comments->readAll()
        ]);
        $this->template->parse('Blog.partial.html');
        $this->template->render($request, $response);
    }

    #[Route(path: '/Blog/Comment/create', method: 'post')]
    public function create(Request $request, Response $response): void
    {
        $data = $request->getData();
        $this->comments->create($data);
        header('location: /Blog#comments');
        exit();
    }

    #[Route(path: '/Blog/Comment/hide', method: 'get')]
    public function hide(Request $request, Response $response): void
    {
        $data = $request->getData();
        $this->comments->hide((int) $data['id']);
        header('location: /Blog#comments');
        exit();
    }

    #[Route(path: '/Blog/Comment/delete', method: 'get')]
    public function delete(Request $request, Response $response): void
    {
        $data = $request->getData();
        $this->comments->delete((int) $data['id']);
        header('location: /Blog#comments');
        exit();
    }

    #[Route(path: '/Blog/Reply', method: 'get')]
    public function reply(Request $request, Response $response): void
    {
        $data = $request->getData();
        $this->template->assign([
            'title' => 'Blog',
            'reply' => true,
            'comments_header' => 'Reply to #' . $data['id'],
            "action" => "/Blog/Reply/create?id=" . $data['id'],
            "comments" => [$this->comments->read((int) $data['id'])]
        ]);
        $this->template->parse('Blog.partial.html');
        $this->template->render($request, $response);
    }

    #[Route(path: '/Blog/Reply/create', method: 'post')]
    public function createReply(Request $request, Response $response): void
    {
        $data = $request->getData();
        $rID = $this->comments->reply($data);
        header('location: /Blog#R' . $rID);
        exit();
    }

    #[Route(path: '/Blog/Reply/hide', method: 'get')]
    public function hideReply(Request $request, Response $response): void
    {
        $data = $request->getData();
        $this->comments->hideReply((int) $data['id']);
        header('location: /Blog#comments');
        exit();
    }

    #[Route(path: '/Blog/Reply/delete', method: 'get')]
    public function deleteReply(Request $request, Response $response): void
    {
        $data = $request->getData();
        $this->comments->deleteReply((int) $data['id']);
        header('location: /Blog#comments');
        exit();
    }
}