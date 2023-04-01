<?php declare(strict_types=1);

namespace f7k\Controller;

use f7k\Service\CommentsService;
use f7k\Sources\attributes\{Inject, Route};
use f7k\Sources\{Request, Response};

class BlogController extends AppController {

    #[Inject(CommentsService::class)]
    protected $comments;

    public function __construct()
    {
        parent::__construct();
        
        $this->template->assign([
            'title' => 'Blog',
            'comments_header' => 'Add a Comment',
            "action" => "/Blog/createComment",
            "href_cancel" => "/Blog#comments",
            "href_hide_comment" => "/Blog/hideComment?id=",
            "href_del_comment" => "/Blog/deleteComment?id=",
            "href_hide_reply" => "/Blog/hideReply?id=",
            "href_del_reply" => "/Blog/deleteReply?id=",
            "href_reply" => "/Blog/reply?id=",
            "comments" => $this->comments->readAll()
        ]);
        $this->template->parse('Blog.partial.html');
    }

    #[Route(path: '/Blog', method: 'get')]
    public function main(Request $request, Response $response): void
    {
        $this->template->render($request, $response);
    }

    #[Route(path: '/Blog/createComment', method: 'post')]
    public function createComment(Request $request, Response $response): void
    {
        $data = $request->getData();
        $this->comments->create($data);
        header('location: /Blog#comments');
        exit();
    }

    #[Route(path: '/Blog/hideComment', method: 'get')]
    public function hideComment(Request $request, Response $response): void
    {
        $data = $request->getData();
        $this->comments->hide((int) $data['id']);
        header('location: /Blog#comments');
        exit();
    }

    #[Route(path: '/Blog/deleteComment', method: 'get')]
    public function deleteComment(Request $request, Response $response): void
    {
        $data = $request->getData();
        $this->comments->delete((int) $data['id']);
        header('location: /Blog#comments');
        exit();
    }

    #[Route(path: '/Blog/reply', method: 'get')]
    public function reply(Request $request, Response $response): void
    {
        $data = $request->getData();
        $this->template->assign([
            'reply' => true,
            "action" => "/Blog/createReply",
            'comments_header' => 'Reply to #' . $data['id'],
            'comment_id' => $data['id'],
            "comments" => [$this->comments->read((int) $data['id'])]
        ]);
        $this->template->parse('Blog.partial.html');
        $this->template->render($request, $response);
    }

    #[Route(path: '/Blog/createReply', method: 'post')]
    public function createReply(Request $request, Response $response): void
    {
        $data = $request->getData();
        $rID = $this->comments->createReply($data);
        header('location: /Blog#R' . $rID);
        exit();
    }

    #[Route(path: '/Blog/hideReply', method: 'get')]
    public function hideReply(Request $request, Response $response): void
    {
        $data = $request->getData();
        $this->comments->hideReply((int) $data['id']);
        header('location: /Blog#comments');
        exit();
    }

    #[Route(path: '/Blog/deleteReply', method: 'get')]
    public function deleteReply(Request $request, Response $response): void
    {
        $data = $request->getData();
        $this->comments->deleteReply((int) $data['id']);
        header('location: /Blog#comments');
        exit();
    }
}