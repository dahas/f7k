<?php

namespace f7k\Component;

use f7k\Entities\CommentEntity;
use f7k\Entities\RepliesEntity;
use f7k\Service\{TemplateService, DbalService};
use f7k\Sources\attributes\Inject;
use f7k\Sources\{ServiceBase, Request, Response};

class CommentsComponent extends ServiceBase {

    #[Inject(TemplateService::class)]
    protected $template;

    #[Inject(DbalService::class)]
    protected $dbal;

    private string $tmplFile = 'Comments.partial.html';
    private $orm;

    public function __construct(
        private string $route,
        protected Request $request,
        protected Response $response
    ) {
        parent::__construct();

        $this->orm = $this->dbal->getEntityManager();
    }

    public function fetchAll(): string
    {
        $this->template->assign([
            "href_reply" => "{$this->route}/reply?id=",
            'route' => $this->route,
            "comments" => $this->readAll($this->route)
        ]);
        $this->template->parse($this->tmplFile);
        return $this->template->getHtml();
    }

    public function fetchItem(int $id): string
    {
        $this->template->assign([
            "href_reply" => "{$this->route}/reply?id=",
            'route' => $this->route,
            'isReply' => true,
            'comments_header' => 'Reply to #' . $id,
            'comment_id' => $id,
            "href_cancel" => $this->route . "#comments",
            "comments" => [$this->read((int) $id)]
        ]);
        $this->template->parse($this->tmplFile);
        return $this->template->getHtml();
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
            ->setTitle($data['title'])
            ->setController($data['controller'])
            ->setComment(nl2br($data['comment']));
        $this->orm->save($comment);
        header("location: {$this->route}#comments");
        exit();
    }

    public function update(array $data): void
    {
        $comment = $this->orm->query(CommentEntity::class)
            ->find($data['id'])
            ->setHidden(1)
            ->setName($data['name'])
            ->setEmail($data['email'])
            ->setTitle($data['title'])
            ->setController($data['controller'])
            ->setComment(nl2br($data['comment']));
        $this->orm->save($comment);
        header("location: {$this->route}#comments");
        exit();
    }

    public function hide(int $id): void
    {
        $comment = $this->orm->query(CommentEntity::class)
            ->find($id)
            ->setHidden(1);
        $this->orm->save($comment);
        header("location: {$this->route}#comments");
        exit();
    }

    public function delete(int $id): void
    {
        $this->orm->query(CommentEntity::class)
            ->where('id')->is($id)
            ->delete();
        header("location: {$this->route}#comments");
        exit();
    }

    public function createReply(array $data): void
    {
        $reply = $this->orm->create(RepliesEntity::class)
            ->setName($data['name'])
            ->setEmail($data['email'])
            ->setTitle($data['title'])
            ->setCommentID((int) $data['comment_id'])
            ->setReply(nl2br($data['comment']));
        $this->orm->save($reply);
        header("location: {$this->route}#R" . $reply->id());
        exit();
    }

    public function hideReply(int $id): void
    {
        $reply = $this->orm->query(RepliesEntity::class)
            ->find($id)
            ->setHidden(1);
        $this->orm->save($reply);
        header("location: {$this->route}#comments");
        exit();
    }

    public function deleteReply(int $id): void
    {
        $this->orm->query(RepliesEntity::class)
            ->where('id')->is($id)
            ->delete();
        header("location: {$this->route}#comments");
        exit();
    }
}