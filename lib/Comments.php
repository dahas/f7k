<?php

namespace PHPSkeleton\Library;

use Opis\ORM\EntityManager;
use PHPSkeleton\Entities\CommentEntity;
use PHPSkeleton\Library\DatabaseLayer as Dbal;

class Comments {

    private EntityManager $orm;
    private $entity = CommentEntity::class;

    public function __construct()
    {
        $con = (new Dbal())->getCon();
        $this->orm = new EntityManager($con);
    }

    public function readAll(): array
    {
        return $this->orm->query($this->entity)
            ->where('hidden')->is(0)
            ->orderBy('created', 'desc')
            ->all();
    }

    public function create(array $data): void
    {
        $comment = $this->orm->create($this->entity)
            ->setName($data['name'])
            ->setEmail($data['email'])
            ->setTitle($data['title'])
            ->setComment(nl2br($data['comment']));
        $this->orm->save($comment);
    }

    public function update(array $data): void
    {
        $comment = $this->orm->query($this->entity)
            ->find($data['id'])
            ->setHidden(1)
            ->setName($data['name'])
            ->setEmail($data['email'])
            ->setTitle($data['title'])
            ->setComment(nl2br($data['comment']));
        $this->orm->save($comment);
    }

    public function delete(int $id): void
    {
        $this->orm->query($this->entity)
            ->where('id')->is($id)
            ->delete();
    }

    public function hide(int $id): void
    {
        $comment = $this->orm->query($this->entity)
            ->find($id)
            ->setHidden(1);
        $this->orm->save($comment);
    }
}