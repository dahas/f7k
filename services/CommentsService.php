<?php

namespace f7k\Service;

use f7k\Entities\CommentEntity;
use f7k\Entities\RepliesEntity;
use f7k\Sources\attributes\Inject;
use f7k\Sources\ServiceBase;

class CommentsService extends ServiceBase {

    #[Inject(DbalService::class)]
    protected $dbal;

    private $orm;

    private string|null $page;

    public function __construct(private array|null $options = [])
    {
        parent::__construct();

        $this->orm = $this->dbal->getEntityManager();

        $this->page = $this->options['page'];
    }

    public function readAll(): array
    {
        $query = $this->orm->query(CommentEntity::class);
        $query->where('hidden')->is(0);
        if ($this->page) {
            $query->andWhere('page')->is($this->page);
        }
        $query->orderBy('created', 'desc');
        return $query->all();
    }

    public function read(int $id): CommentEntity
    {
        return $this->orm->query(CommentEntity::class)
            ->find($id);
    }

    public function create(array $data): int
    {
        $comment = $this->orm->create(CommentEntity::class)
            ->setName($data['name'])
            ->setEmail($data['email'])
            ->setTitle($data['title'])
            ->setPage($this->page)
            ->setComment(nl2br($data['comment']));
        $this->orm->save($comment);
        return $comment->id();
    }

    public function update(array $data): void
    {
        $comment = $this->orm->query(CommentEntity::class)
            ->find($data['id'])
            ->setHidden(1)
            ->setName($data['name'])
            ->setEmail($data['email'])
            ->setTitle($data['title'])
            ->setPage($this->page)
            ->setComment(nl2br($data['comment']));
        $this->orm->save($comment);
    }

    public function hide(int $id): void
    {
        $comment = $this->orm->query(CommentEntity::class)
            ->find($id)
            ->setHidden(1);
        $this->orm->save($comment);
    }

    public function delete(int $id): void
    {
        $this->orm->query(CommentEntity::class)
            ->where('id')->is($id)
            ->delete();
    }

    public function createReply(array $data): int
    {
        $reply = $this->orm->create(RepliesEntity::class)
            ->setName($data['name'])
            ->setEmail($data['email'])
            ->setTitle($data['title'])
            ->setCommentID((int) $data['comment_id'])
            ->setReply(nl2br($data['comment']));
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