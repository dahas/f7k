<?php declare(strict_types=1);

namespace f7k\Controller;

use f7k\Service\CommentsService;
use f7k\Sources\attributes\{Inject, Route};
use f7k\Sources\{Request, Response};

class BlogController extends AppController {

    #[Inject(CommentsService::class, options: ['page' => 'Blog'])]
    protected $comments;

    public function __construct()
    {
        parent::__construct();
        
        $this->template->assign([
            'title' => 'Blog',
            'comments_header' => 'Add a Comment',
            "action" => "/Blog/Comment/create",
            "href_cancel" => "/Blog#comments",
            "href_hide_comment" => "/Blog/Comment/hide?id=",
            "href_del_comment" => "/Blog/Comment/delete?id=",
            "href_hide_reply" => "/Blog/Reply/hide?id=",
            "href_del_reply" => "/Blog/Reply/delete?id=",
            "href_reply" => "/Blog/Reply?id=",
            "comments" => $this->comments->readAll()
        ]);
        $this->template->parse('Blog.partial.html');
    }

    #[Route(path: '/Blog', method: 'get')]
    public function main(Request $request, Response $response): void
    {
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
            'reply' => true,
            "action" => "/Blog/Reply/create",
            'comments_header' => 'Reply to #' . $data['id'],
            'comment_id' => $data['id'],
            "comments" => [$this->comments->read((int) $data['id'])]
        ]);
        $this->template->parse('Blog.partial.html');
        $this->template->render($request, $response);
    }

    #[Route(path: '/Blog/Reply/create', method: 'post')]
    public function createReply(Request $request, Response $response): void
    {
        $data = $request->getData();
        $rID = $this->comments->createReply($data);
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