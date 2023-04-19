<?php declare(strict_types=1);

namespace f7k\Controller;

use f7k\Service\ArticlesService;
use f7k\Sources\attributes\{Inject, Route};

class BlogController extends AppController {

    #[Inject(ArticlesService::class)]
    protected $articles;

    protected string $menuItem = 'Blog';
    protected string $templateFile = 'Article.partial.html';

    #[Route(path: '/Blog', method: 'get')]
    public function main(): void
    {
        $articles = $this->articles->readAll('/Blog');

        $this->template->assign([
            'title' => "Blog",
            'articles' => $articles
        ]);

        $this->template->parse('Blog.partial.html');
        $this->template->render($this->request, $this->response);
    }
}