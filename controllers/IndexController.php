<?php declare(strict_types=1);

namespace f7k\Controller;

use f7k\Component\CommentsComponent;
use f7k\Sources\attributes\Route;
use f7k\Sources\{Request, Response};

class IndexController extends AppController {

    private CommentsComponent $comments;

    public function __construct(protected Request $request, protected Response $response)
    {
        parent::__construct($request, $response);

        $this->template->assign([
            'title' => 'f7k - The framework',
            'header' => 'f7k - Yet another framework',
            "subtitle" => "f7k is the numeronym of the word 'framework'. Use this lightweight framework to quickly build feature rich web applications with PHP. If you are unfamiliar or 
            inexperienced with developing secure and high-performance web applications, I strongly recommend using Symfony, Laravel, or a similar well tested product."
        ]);

        $this->comments = new CommentsComponent($this->request, $this->response);
        $this->comments->setRoute("/Index");
    }

    #[Route(path: ['/', '/Index'], method: 'get')]
    public function main(): void
    {
        $this->template->assign([
            "comments" => $this->comments->fetchAll()
        ]);
        $this->template->parse('Index.partial.html');
        $this->template->render($this->request, $this->response);
    }

    #[Route(path: '/Index/reply', method: 'get')]
    public function reply(): void
    {
        $this->template->assign([
            "comments" => $this->comments->fetchItem((int) $this->data['id'])
        ]);
        $this->template->parse('Index.partial.html');
        $this->template->render($this->request, $this->response);
    }
    
    #[Route(path: '/Index/Comments/create', method: 'post')]
    public function createComment(): void
    {
        $this->comments->create($this->data);
    }

    #[Route(path: '/Index/Comments/hide', method: 'get')]
    public function hideComment(): void
    {
        $this->comments->hide((int) $this->data['id']);
    }

    #[Route(path: '/Index/Comments/delete', method: 'get')]
    public function deleteComment(): void
    {
        $this->comments->delete((int) $this->data['id']);
    }

    #[Route(path: '/Index/Reply/create', method: 'post')]
    public function createReply(): void
    {
        $this->comments->createReply($this->data);
    }

    #[Route(path: '/Index/Reply/hide', method: 'get')]
    public function hideReply(): void
    {
        $this->comments->hideReply((int) $this->data['id']);
    }

    #[Route(path: '/Index/Reply/delete', method: 'get')]
    public function deleteReply(): void
    {
        $this->comments->deleteReply((int) $this->data['id']);
    }
}