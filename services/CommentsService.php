<?php

namespace f7k\Service;

use f7k\Entities\CommentEntity;
use f7k\Entities\RepliesEntity;
use f7k\Service\DbalService;
use f7k\Sources\attributes\Inject;
use f7k\Sources\ServiceBase;

class CommentsService extends ServiceBase {

    #[Inject(DbalService::class)]
    protected $dbal;

    private string $tmplFile = 'Comments.partial.html';
    private $orm;

    public function __construct(private array|null $options = []) 
    {
        parent::__construct();
        $this->orm = $this->dbal->getEntityManager();
    }

    public function readAll(string $controller = ""): array
    {
        $query = $this->orm->query(CommentEntity::class);
        $query->where('hidden')->is(0);
        if ($controller) {
            $query->andWhere('controller')->is($controller);
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
            ->setName($data['name'])
            ->setEmail($data['email'])
            ->setTitle($data['title'] ?? "")
            ->setController($data['controller'])
            ->setComment($data['comment']);
        $this->orm->save($comment);
    }

    public function updateComment(array $data): int
    {
        $comment = $this->orm->query(CommentEntity::class)
            ->find($data['comment_id'])
            ->setName($data['name'])
            ->setEmail($data['email'])
            ->setComment($data['comment']);
        $this->orm->save($comment);
        return $comment->id();
    }

    public function hide(int $id): int
    {
        $comment = $this->orm->query(CommentEntity::class)
            ->find($id)
            ->setHidden(1);
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
            ->setName($data['name'])
            ->setEmail($data['email'])
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
            ->setName($data['name'])
            ->setEmail($data['email'])
            ->setTitle($data['title'] ?? "")
            ->setReply($data['comment']);
        $this->orm->save($reply);
        return $reply->id();
    }

    public function hideReply(int $id): void
    {
        $reply = $this->orm->query(RepliesEntity::class)
            ->find($id)
            ->setHidden(1);
        $this->orm->save($reply);
    }

    public function deleteReply(int $id): void
    {
        $this->orm->query(RepliesEntity::class)
            ->where('id')->is($id)
            ->delete();
    }
}