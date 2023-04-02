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

    public function __construct(protected Request $request, protected Response $response)
    {
        parent::__construct();
        
        $this->template->assign([
            "nav" => $this->menu->getItems(),
            "currentPath" => $_SERVER['REQUEST_URI']
        ]);
    }

    #[Route(path: '/Comments/create', method: 'post')]
    public function createComment(): void
    {
        $data = $this->request->getData();
        $this->comments->create($data);
        header("location: {$data['redirect']}#comments");
        exit();
    }

    #[Route(path: '/Comments/hide', method: 'get')]
    public function hideComment(): void
    {
        $data = $this->request->getData();
        $this->comments->hide((int) $data['id']);
        header("location: {$data['redirect']}#comments");
        exit();
    }

    #[Route(path: '/Comments/delete', method: 'get')]
    public function deleteComment(): void
    {
        $data = $this->request->getData();
        $this->comments->delete((int) $data['id']);
        header("location: {$data['redirect']}#comments");
        exit();
    }

    #[Route(path: '/Reply/create', method: 'post')]
    public function createReply(): void
    {
        $data = $this->request->getData();
        $rID = $this->comments->createReply($data);
        header("location: {$data['redirect']}#R$rID");
        exit();
    }

    #[Route(path: '/Reply/hide', method: 'get')]
    public function hideReply(): void
    {
        $data = $this->request->getData();
        $this->comments->hideReply((int) $data['id']);
        header("location: {$data['redirect']}#comments");
        exit();
    }

    #[Route(path: '/Reply/delete', method: 'get')]
    public function deleteReply(): void
    {
        $data = $this->request->getData();
        $this->comments->deleteReply((int) $data['id']);
        header("location: {$data['redirect']}#comments");
        exit();
    }
}