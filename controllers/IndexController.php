<?php declare(strict_types=1);

namespace f7k\Controller;

use f7k\Service\CommentsService;
use f7k\Sources\attributes\{Inject, Route};
use f7k\Sources\{Request,Response};

class IndexController extends AppController {

    #[Inject(CommentsService::class, options: ['page' => 'Index'])]
    protected $comments;

    public function __construct()
    {
        parent::__construct();
        
        $this->template->assign([
            'title' => 'f7k - The framework',
            'header' => 'f7k - Yet another framework',
            "subtitle" => "f7k is the numeronym of the word 'framework'. Use this lightweight framework to quickly build feature rich web applications with PHP. If you are unfamiliar or 
            inexperienced with developing secure and high-performance web applications, I strongly recommend using Symfony, Laravel, or a similar well tested product.",
            'comments_header' => 'Add a Comment',
            "action" => "/Index/Comment/create",
            "href_cancel" => "/#comments",
            "href_hide_comment" => "/Index/Comment/hide?id=",
            "href_del_comment" => "/Index/Comment/delete?id=",
            "href_hide_reply" => "/Index/Reply/hide?id=",
            "href_del_reply" => "/Index/Reply/delete?id=",
            "href_reply" => "/Index/Reply?id=",
            "comments" => $this->comments->readAll()
        ]);
        $this->template->parse('Index.partial.html');
    }

    #[Route(path: '/', method: 'get')]
    public function main(Request $request, Response $response): void
    {
        $this->template->render($request, $response);
    }

    #[Route(path: '/Index/Comment/create', method: 'post')]
    public function create(Request $request, Response $response): void
    {
        $data = $request->getData();
        $this->comments->create($data);
        header('location: /#comments');
        exit();
    }

    #[Route(path: '/Index/Comment/hide', method: 'get')]
    public function hide(Request $request, Response $response): void
    {
        $data = $request->getData();
        $this->comments->hide((int) $data['id']);
        header('location: /#comments');
        exit();
    }

    #[Route(path: '/Index/Comment/delete', method: 'get')]
    public function delete(Request $request, Response $response): void
    {
        $data = $request->getData();
        $this->comments->delete((int) $data['id']);
        header('location: /#comments');
        exit();
    }

    #[Route(path: '/Index/Reply', method: 'get')]
    public function reply(Request $request, Response $response): void
    {
        $data = $request->getData();
        $this->template->assign([
            'reply' => true,
            "action" => "/Index/Reply/create",
            'comments_header' => 'Reply to #' . $data['id'],
            'comment_id' => $data['id'],
            "comments" => [$this->comments->read((int) $data['id'])]
        ]);
        $this->template->parse('Index.partial.html');
        $this->template->render($request, $response);
    }

    #[Route(path: '/Index/Reply/create', method: 'post')]
    public function createReply(Request $request, Response $response): void
    {
        $data = $request->getData();
        $rID = $this->comments->createReply($data);
        header('location: /#R' . $rID);
        exit();
    }

    #[Route(path: '/Index/Reply/hide', method: 'get')]
    public function hideReply(Request $request, Response $response): void
    {
        $data = $request->getData();
        $this->comments->hideReply((int) $data['id']);
        header('location: /#comments');
        exit();
    }

    #[Route(path: '/Index/Reply/delete', method: 'get')]
    public function deleteReply(Request $request, Response $response): void
    {
        $data = $request->getData();
        $this->comments->deleteReply((int) $data['id']);
        header('location: /#comments');
        exit();
    }
}