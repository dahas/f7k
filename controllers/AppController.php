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

    protected array $data;

    public function __construct(protected Request $request, protected Response $response)
    {
        parent::__construct();

        $this->data = $this->request->getData();
        
        $this->template->assign([
            "nav" => $this->menu->getItems(),
            "currentPath" => $_SERVER['REQUEST_URI']
        ]);
    }

    #[Route(path: '/Comments/create', method: 'post')]
    public function createComment(): void
    {
        
        $this->comments->create($this->data);
        header("location: {$this->data['redirect']}#comments");
        exit();
    }

    #[Route(path: '/Comments/hide', method: 'get')]
    public function hideComment(): void
    {
        $this->comments->hide((int) $this->data['id']);
        header("location: {$this->data['redirect']}#comments");
        exit();
    }

    #[Route(path: '/Comments/delete', method: 'get')]
    public function deleteComment(): void
    {
        $this->comments->delete((int) $this->data['id']);
        header("location: {$this->data['redirect']}#comments");
        exit();
    }

    #[Route(path: '/Reply/create', method: 'post')]
    public function createReply(): void
    {
        $rID = $this->comments->createReply($this->data);
        header("location: {$this->data['redirect']}#R$rID");
        exit();
    }

    #[Route(path: '/Reply/hide', method: 'get')]
    public function hideReply(): void
    {
        $this->comments->hideReply((int) $this->data['id']);
        header("location: {$this->data['redirect']}#comments");
        exit();
    }

    #[Route(path: '/Reply/delete', method: 'get')]
    public function deleteReply(): void
    {
        $this->comments->deleteReply((int) $this->data['id']);
        header("location: {$this->data['redirect']}#comments");
        exit();
    }
}