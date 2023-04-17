<?php

namespace f7k\Service;

use f7k\Entities\CommentEntity;
use f7k\Entities\RepliesEntity;
use f7k\Service\DbalService;
use f7k\Service\AuthenticationService;
use f7k\Sources\attributes\Inject;
use f7k\Sources\ServiceBase;

class CommentsService extends ServiceBase {

    #[Inject(DbalService::class)]
    protected $dbal;

    #[Inject(AuthenticationService::class)]
    protected $auth;

    private string $tmplFile = 'Comments.partial.html';
    private $orm;

    public function __construct(private array|null $options = [])
    {
        parent::__construct();
        $this->orm = $this->dbal->getEntityManager();
    }

    public function readAll(string $page, int $article_id = 0): array
    {
        $query = $this->orm->query(CommentEntity::class);
        $query->where('page')->is($page);
        if ($article_id) {
            $query->andWhere('article_id')->is($article_id);
        }
        if (!$this->auth->isLoggedIn()) {
            $query->andWhere('hidden')->is(0);
        }
        $query->orderBy('created', 'desc');
        return $query->all();
    }

    public function read(int $id): ?CommentEntity
    {
        return $this->orm->query(CommentEntity::class)
            ->find($id);
    }

    public function create(array $data): int
    {
        if ($this->auth->isLoggedIn()) {
            $comment = $this->orm->create(CommentEntity::class)
                ->setName($_SESSION['user']['name'])
                ->setEmail($_SESSION['user']['email'])
                ->setArticleId((int) $data['article'])
                ->setTitle($data['title'] ?? "")
                ->setPage($data['page'])
                ->setComment($data['comment']);
            $this->orm->save($comment);
            return $comment->id();
        }
        return -1;
    }

    public function updateComment(array $data): int
    {
        if ($this->auth->isLoggedIn()) {
            $comment = $this->orm->query(CommentEntity::class)
                ->find($data['comment_id']);
            if ($this->auth->isAuthorized($comment->getEmail())) {
                $comment->setComment($data['comment']);
                $this->orm->save($comment);
                return $comment->id();
            }
            return 0;
        }
        return -1;
    }

    public function hide(int $id): int
    {
        if ($this->auth->isLoggedIn()) {
            $comment = $this->orm->query(CommentEntity::class)
                ->find($id);
            if ($this->auth->isAuthorized($comment->getEmail())) {
                $hidden = $comment->getHidden() === 1 ? 0 : 1;
                $comment->setHidden($hidden);
                $this->orm->save($comment);
                return $comment->id();
            }
            return 0;
        }
        return -1;
    }

    public function delete(int $id): int
    {
        if ($this->auth->isLoggedIn()) {
            $comment = $this->orm->query(CommentEntity::class)
                ->find($id);
            if ($this->auth->isAuthorized($comment->getEmail())) {
                $this->orm->delete($comment);
                return $id;
            }
            return 0;
        }
        return -1;
    }

    public function getReply(int $id): RepliesEntity
    {
        return $this->orm->query(RepliesEntity::class)
            ->find($id);
    }

    public function createReply(array $data): int
    {
        if ($this->auth->isLoggedIn()) {
            $reply = $this->orm->create(RepliesEntity::class)
                ->setName($_SESSION['user']['name'])
                ->setEmail($_SESSION['user']['email'])
                ->setTitle($data['title'] ?? "")
                ->setCommentID((int) $data['comment_id'])
                ->setReply($data['comment']);
            $this->orm->save($reply);
            return $reply->id();
        }
        return -1;
    }

    public function updateReply(array $data): int
    {
        if ($this->auth->isLoggedIn()) {
            $reply = $this->orm->query(RepliesEntity::class)
                ->find($data['id']);
            if ($this->auth->isAuthorized($reply->getEmail())) {
                $reply->setReply($data['comment']);
                $this->orm->save($reply);
                return $reply->id();
            }
            return 0;
        }
        return -1;
    }

    public function hideReply(int $id): int
    {
        if ($this->auth->isLoggedIn()) {
            $reply = $this->orm->query(RepliesEntity::class)
                ->find($id);
            if ($this->auth->isAuthorized($reply->getEmail())) {
                $hidden = $reply->getHidden() === 1 ? 0 : 1;
                $reply->setHidden($hidden);
                $this->orm->save($reply);
                return $reply->id();
            }
            return 0;
        }
        return -1;
    }

    public function deleteReply(int $id): int
    {
        if ($this->auth->isLoggedIn()) {
            $reply = $this->orm->query(RepliesEntity::class)
                ->find($id);
            if ($this->auth->isAuthorized($reply->getEmail())) {
                $this->orm->delete($reply);
                return $id;
            }
            return 0;
        }
        return -1;
    }
}