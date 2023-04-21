<?php

namespace f7k\Service;

use f7k\Entities\ArticleEntity;
use f7k\Service\{DbalService, AuthenticationService, PurifyService};
use f7k\Sources\attributes\Inject;
use f7k\Sources\ServiceBase;

class ArticlesService extends ServiceBase {

    #[Inject(DbalService::class)]
    protected $dbal;

    #[Inject(AuthenticationService::class)]
    protected $auth;

    #[Inject(PurifyService::class, [
        'HTML.Trusted' => true,
        'Filter.YouTube' => true,
    ])]
    protected $purifier;

    private $orm;

    public function __construct(private array|null $options = [])
    {
        parent::__construct();

        $this->orm = $this->dbal->getEntityManager();
    }

    public function readAll(string $page, int $offset = 1, int $limit = 6, int &$count = 0): array
    {
        // Get totalcount for Pagination:
        $db = $this->dbal->getDatabase();
        $cQuery = $db->from('blog_articles');
        $cQuery->where('page')->is($page);
        if (!$this->auth->isLoggedIn() || ($this->auth->isLoggedIn() && !$this->auth->isAdmin())) {
            $cQuery->andWhere('hidden')->is(0);
        }
        $count = $cQuery->count();

        // Get Blog articles:
        $query = $this->orm->query(ArticleEntity::class);
        $query->where('page')->is($page);
        if (!$this->auth->isLoggedIn() || ($this->auth->isLoggedIn() && !$this->auth->isAdmin())) {
            $query->andWhere('hidden')->is(0);
        }
        $query->orderBy('created', 'desc')->offset($offset*$limit-$limit)->limit($limit);
        return $query->all();
    }

    public function read(int $id): ?ArticleEntity
    {
        return $this->orm->query(ArticleEntity::class)
            ->find($id);
    }

    public function create(array $data): int
    {
        if ($this->auth->isLoggedIn()) {
            if ($this->auth->isAdmin()) {
                $articleText = $this->purifier->purify($data['articleText']);
                $article = $this->orm->create(ArticleEntity::class)
                    ->setTitle($data['title'] ?? "")
                    ->setDescription($data['description'] ?? "")
                    ->setImage($data['image'] ?? "")
                    ->setArticle($articleText)
                    ->setPage($data['page'])
                    ->setHidden($data['hidden']);
                $this->orm->save($article);
                return $article->id();
            }
            return 0;
        }
        return -1;
    }

    public function update(array $data): int
    {
        if ($this->auth->isLoggedIn()) {
            if ($this->auth->isAdmin()) {
                $articleText = $this->purifier->purify($data['articleText']);
                $article = $this->orm->query(ArticleEntity::class)
                    ->find($data['articleId'])
                    ->setTitle($data['title'] ?? "")
                    ->setDescription($data['description'] ?? "")
                    ->setImage($data['image'] ?? "")
                    ->setPage($data['page'] ?? "")
                    ->setHidden($data['hidden'] ?? "")
                    ->setArticle($articleText);
                $this->orm->save($article);
                return $article->id();
            }
            return 0;
        }
        return -1;
    }

    public function hide(int $id): int
    {
        if ($this->auth->isLoggedIn()) {
            if ($this->auth->isAdmin()) {
                $article = $this->orm->query(ArticleEntity::class)
                    ->find($id);
                $hidden = $article->getHidden() === 1 ? 0 : 1;
                $article->setHidden($hidden);
                $this->orm->save($article);
                return $article->id();
            }
            return 0;
        }
        return -1;
    }

    public function delete(int $id): int
    {
        if ($this->auth->isLoggedIn()) {
            if ($this->auth->isAdmin()) {
                $this->orm->query(ArticleEntity::class)
                    ->where('id')->is($id)
                    ->delete();
                return $id;
            }
            return 0;
        }
        return -1;
    }
}