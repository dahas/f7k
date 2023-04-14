<?php declare(strict_types=1);

namespace f7k\Controller;

use f7k\Service\ArticlesService;
use f7k\Sources\attributes\{Inject, Route};
use f7k\Sources\{Request, Response};

class ArticleController extends CommentsController {

    #[Inject(ArticlesService::class)]
    protected $articles;

    protected string $page = 'Blog';
    protected string $route = '/Blog/Article';
    protected string $templateFile = 'Article.partial.html';

    public function __construct(protected Request $request, protected Response $response)
    {
        parent::__construct($request, $response);

        $articles = $this->articles->readAll('/Blog');

        if (isset($this->data['article']) && $this->data['article']) {
            $article = $this->articles->read((int) $this->data['article']);
        } else {
            $article = $articles[0];
        }

        $this->articleId = $article->id();

        $currentArticle = $article->id();

        $this->template->assign([
            'title' => "My Blog",
            'article' => $article,
            'articles' => $articles,
            'currentArticle' => $currentArticle,
        ]);
    }

    #[Route(path: '/Blog/Article', method: 'get')]
    public function read(): void
    {
        parent::renderComments();
    }

    #[Route(path: '/Blog/Article/Reply', method: 'get')]
    public function reply(): void
    {
        parent::reply();
    }

    #[Route(path: '/Blog/Article/Comments/edit', method: 'get')]
    public function editComment(): void
    {
        parent::editComment();
    }

    #[Route(path: '/Blog/Article/Reply/edit', method: 'get')]
    public function editReply(): void
    {
        parent::editReply();
    }

    #[Route(path: '/Blog/Article/Comments/create', method: 'post')]
    public function createComment(): void
    {
        parent::createComment();
    }

    #[Route(path: '/Blog/Article/Comments/update', method: 'post')]
    public function updateComment(): void
    {
        parent::updateComment();
    }

    #[Route(path: '/Blog/Article/Comments/hide', method: 'get')]
    public function hideComment(): void
    {
        parent::hideComment();
    }

    #[Route(path: '/Blog/Article/Comments/delete', method: 'get')]
    public function deleteComment(): void
    {
        parent::deleteComment();
    }

    #[Route(path: '/Blog/Article/Reply/create', method: 'post')]
    public function createReply(): void
    {
        parent::createReply();
    }

    #[Route(path: '/Blog/Article/Reply/update', method: 'post')]
    public function updateReply(): void
    {
        parent::updateReply();
    }

    #[Route(path: '/Blog/Article/Reply/hide', method: 'get')]
    public function hideReply(): void
    {
        parent::hideReply();
    }

    #[Route(path: '/Blog/Article/Reply/delete', method: 'get')]
    public function deleteReply(): void
    {
        parent::deleteReply();
    }
}