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

    public function readAll(string $controller, int $article_id = 0): array
    {
        $query = $this->orm->query(CommentEntity::class);
        $query->where('controller')->is($controller);
        if ($article_id) {
            $query->andWhere('article_id')->is($article_id);
        }
        if (!$this->auth->isLoggedIn()) {
            $query->andWhere('hidden')->is(0);
        }
        $query->orderBy('created', 'desc');
        return $query->all();
    }

    public function read(int $id): CommentEntity
    {
        return $this->orm->query(CommentEntity::class)
            ->find($id);
    }

    public function create(array $data): void
    {
        $comment = $this->orm->create(CommentEntity::class)
            ->setName($_SESSION['user']['name'])
            ->setEmail($_SESSION['user']['email'])
            ->setArticleID((int) $data['article_id'])
            ->setTitle($data['title'] ?? "")
            ->setController($data['controller'])
            ->setComment($data['comment']);
        $this->orm->save($comment);
    }

    public function updateComment(array $data): int
    {
        $comment = $this->orm->query(CommentEntity::class)
            ->find($data['comment_id'])
            ->setName($_SESSION['user']['name'])
            ->setEmail($_SESSION['user']['email'])
            ->setComment($data['comment']);
        $this->orm->save($comment);
        return $comment->id();
    }

    public function hide(int $id): int
    {
        $comment = $this->orm->query(CommentEntity::class)
            ->find($id);
        $hidden = $comment->getHidden() === 1 ? 0 : 1;
        $comment->setHidden($hidden);
        $this->orm->save($comment);
        return $comment->id();
    }

    public function delete(int $id): void
    {
        $this->orm->query(CommentEntity::class)
            ->where('id')->is($id)
            ->delete();
    }

    public function getReply(int $id): RepliesEntity
    {
        return $this->orm->query(RepliesEntity::class)
            ->find($id);
    }

    public function createReply(array $data): int
    {
        $reply = $this->orm->create(RepliesEntity::class)
            ->setName($_SESSION['user']['name'])
            ->setEmail($_SESSION['user']['email'])
            ->setTitle($data['title'] ?? "")
            ->setCommentID((int) $data['comment_id'])
            ->setReply($data['comment']);
        $this->orm->save($reply);
        return $reply->id();
    }

    public function updateReply(array $data): int
    {
        $reply = $this->orm->query(RepliesEntity::class)
            ->find($data['id'])
            ->setName($_SESSION['user']['name'])
            ->setEmail($_SESSION['user']['email'])
            ->setTitle($data['title'] ?? "")
            ->setReply($data['comment']);
        $this->orm->save($reply);
        return $reply->id();
    }

    public function hideReply(int $id): int
    {
        $reply = $this->orm->query(RepliesEntity::class)
            ->find($id);
        $hidden = $reply->getHidden() === 1 ? 0 : 1;
        $reply->setHidden($hidden);
        $this->orm->save($reply);
        return $reply->id();
    }

    public function deleteReply(int $id): void
    {
        $this->orm->query(RepliesEntity::class)
            ->where('id')->is($id)
            ->delete();
    }
}