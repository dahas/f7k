<?php declare(strict_types=1);

namespace f7k\Controller;

use f7k\Service\{MenuService, TemplateService, CommentsService};
use f7k\Sources\attributes\{Inject, Route};
use f7k\Sources\{Request,Response};
use f7k\Sources\ControllerBase;

class AppController extends ControllerBase {

    #[Inject(TemplateService::class)]
    protected $template;

    #[Inject(MenuService::class)]
    protected $menu;

    #[Inject(CommentsService::class)]
    protected $comments;

    public function __construct()
    {
        parent::__construct();
        
        $this->template->assign([
            "nav" => $this->menu->getItems(),
            "currentPath" => $_SERVER['REQUEST_URI']
        ]);
    }

    #[Route(path: '/Comments/create', method: 'post')]
    public function createComment(Request $request, Response $response): void
    {
        $data = $request->getData();
        $this->comments->create($data);
        header("location: {$data['redirect']}#comments");
        exit();
    }

    #[Route(path: '/Comments/hide', method: 'get')]
    public function hideComment(Request $request, Response $response): void
    {
        $data = $request->getData();
        $this->comments->hide((int) $data['id']);
        header("location: {$data['redirect']}#comments");
        exit();
    }

    #[Route(path: '/Comments/delete', method: 'get')]
    public function deleteComment(Request $request, Response $response): void
    {
        $data = $request->getData();
        $this->comments->delete((int) $data['id']);
        header("location: {$data['redirect']}#comments");
        exit();
    }

    #[Route(path: '/Reply/create', method: 'post')]
    public function createReply(Request $request, Response $response): void
    {
        $data = $request->getData();
        $rID = $this->comments->createReply($data);
        header("location: {$data['redirect']}#R$rID");
        exit();
    }

    #[Route(path: '/Reply/hide', method: 'get')]
    public function hideReply(Request $request, Response $response): void
    {
        $data = $request->getData();
        $this->comments->hideReply((int) $data['id']);
        header("location: {$data['redirect']}#comments");
        exit();
    }

    #[Route(path: '/Reply/delete', method: 'get')]
    public function deleteReply(Request $request, Response $response): void
    {
        $data = $request->getData();
        $this->comments->deleteReply((int) $data['id']);
        header("location: {$data['redirect']}#comments");
        exit();
    }
}