<?php declare(strict_types=1);

namespace PHPSkeleton\Controller;

use PHPSkeleton\Library\Comments;
use PHPSkeleton\Sources\attributes\{Inject, Route};
use PHPSkeleton\Sources\{Request, Response};

class BlogController extends AppController {

    #[Inject(Comments::class)]
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
        header('location: /Blog');
        exit();
    }

    #[Route(path: '/Blog/Comment/delete', method: 'get')]
    public function delete(Request $request, Response $response): void
    {
        $data = $request->getData();
        $this->comments->delete((int) $data['id']);
        header('location: /Blog');
        exit();
    }

    #[Route(path: '/Blog/Comment/hide', method: 'get')]
    public function hide(Request $request, Response $response): void
    {
        $data = $request->getData();
        $this->comments->hide((int) $data['id']);
        header('location: /Blog');
        exit();
    }

    #[Route(path: '/Blog/Reply', method: 'get')]
    public function reply(Request $request, Response $response): void
    {
        $data = $request->getData();
        $this->template->assign([
            'title' => 'Blog',
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
        header('location: /Blog#R' . $data['id'] . "." . $rID);
        exit();
    }
}