<?php declare(strict_types=1);

namespace PHPSkeleton\Controller;

use PHPSkeleton\Sources\attributes\Inject;
use PHPSkeleton\Library\Comments;
use PHPSkeleton\Sources\attributes\Route;
use PHPSkeleton\Sources\{Request, Response};

class BlogController extends AppController {

    #[Inject(Comments::class)]
    protected $comments;

    public function __construct()
    {
        parent::__construct();
    }

    #[Route(path: '/Blog', method: 'get')]
    public function main(Request $request, Response $response): void
    {
         $this->template->assign([
            'title' => 'Blog',
            'header' => 'Why you\'ll be amazed',
            "subtitle" => "Read on ...",
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
}