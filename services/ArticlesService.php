<?php

namespace f7k\Service;

use f7k\Entities\ArticleEntity;
use f7k\Service\DbalService;
use f7k\Service\AuthenticationService;
use f7k\Sources\attributes\Inject;
use f7k\Sources\ServiceBase;

class ArticlesService extends ServiceBase {

    #[Inject(DbalService::class)]
    protected $dbal;

    #[Inject(AuthenticationService::class)]
    protected $auth;

    private $orm;

    public function __construct(private array|null $options = []) 
    {
        parent::__construct();
        $this->orm = $this->dbal->getEntityManager();
    }

    public function readAll(string $page): array
    {
        $query = $this->orm->query(ArticleEntity::class);
        $query->where('page')->is($page);
        if (!$this->auth->isLoggedIn()) {
            $query->andWhere('hidden')->is(0);
        }
        $query->orderBy('created', 'desc');
        return $query->all();
    }

    public function read(int $id): ArticleEntity
    {
        return $this->orm->query(ArticleEntity::class)
            ->find($id);
    }

    public function create(array $data): void
    {
        $article = $this->orm->create(ArticleEntity::class)
            ->setArticle($data['article'] ?? "")
            ->setPage($data['page']);
        $this->orm->save($article);
    }

    public function update(array $data): int
    {
        $article = $this->orm->query(ArticleEntity::class)
            ->find($data['id'])
            ->setArticle($data['article']);
        $this->orm->save($article);
        return $article->id();
    }

    public function hide(int $id): int
    {
        $article = $this->orm->query(ArticleEntity::class)
            ->find($id);
        $hidden = $article->getHidden() === 1 ? 0 : 1;
        $article->setHidden($hidden);
        $this->orm->save($article);
        return $article->id();
    }

    public function delete(int $id): void
    {
        $this->orm->query(ArticleEntity::class)
            ->where('id')->is($id)
            ->delete();
    }
}