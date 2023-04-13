<?php declare(strict_types=1);

namespace f7k\Controller;

use f7k\Service\ArticlesService;
use f7k\Sources\attributes\{Inject, Route};

class EditorController extends AppController {

    #[Inject(ArticlesService::class)]
    protected $articles;

    #[Route(path: '/Editor', method: 'get')]
    public function main(): void
    {
        $this->template->assign([
            'title' => "Create an Article",
            'articlePage' => $this->data['page']
        ]);

        $this->template->parse('Editor.partial.html');
        $this->template->render($this->request, $this->response);
    }

    #[Route(path: '/Editor/edit', method: 'get')]
    public function edit(): void
    {
        $article = $this->articles->read((int) $this->data['articleId']);

        $this->template->assign([
            'title' => "Edit an Article",
            'articleId' => $article->id(),
            'articleTitle' => $article->getTitle(),
            'articleDescription' => $article->getDescription(),
            'articleText' => $article->getArticle(),
            'articlePage' => $article->getPage(),
        ]);

        $this->template->parse('Editor.partial.html');
        $this->template->render($this->request, $this->response);
    }

    #[Route(path: '/Editor/create', method: 'post')]
    public function createArticle(): void
    {
        $this->articles->create($this->data);
        
        header("location: " . $this->data['page']);
        exit();
    }

    #[Route(path: '/Editor/update', method: 'post')]
    public function updateArticle(): void
    {
        $this->articles->update($this->data);
        
        header("location: " . $this->data['page']);
        exit();
    }

    #[Route(path: '/Editor/hide', method: 'get')]
    public function hideArticle(): void
    {
        $this->articles->hide((int) $this->data['articleId']);
        
        header("location: " . $this->data['page']);
        exit();
    }

    #[Route(path: '/Editor/delete', method: 'get')]
    public function deleteArticle(): void
    {
        $this->articles->delete((int) $this->data['articleId']);
        
        header("location: " . $this->data['page']);
        exit();
    }
}