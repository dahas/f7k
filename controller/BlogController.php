<?php declare(strict_types=1);

namespace PHPSkeleton\Controller;

use PHPSkeleton\Sources\attributes\Route;
use PHPSkeleton\Sources\{Request, Response};
use PHPSkeleton\Entities\CommentEntity;

class BlogController extends AppController {

    private $entity = CommentEntity::class;

    #[Route(path: '/Blog', method: 'get')]
    public function main(Request $request, Response $response): void
    {
        $comments = ($this->orm)($this->entity)
            ->where('hidden')->is(0)
            ->orderBy('created', 'desc')
            ->all();

        $this->template->assign([
            'title' => 'Blog',
            'header' => 'Why you\'ll be amazed',
            "subtitle" => "Read on ...",
            "comments" => $comments
        ]);
        $this->template->parse('Blog.partial.html');
        $this->template->render($request, $response);
    }


    #[Route(path: '/Blog/Comment/create', method: 'post')]
    public function load(Request $request, Response $response): void
    {
        $data = $request->getData();
        $comment = $this->orm->create($this->entity);
        $comment->setName($data['name']);
        $comment->setEmail($data['email']);
        $comment->setTitle($data['title']);
        $comment->setComment(nl2br($data['comment']));
        $this->orm->save($comment);

        header('location: /Blog');
        exit();
    }

    #[Route(path: '/Blog/Comment/delete', method: 'get')]
    public function delete(Request $request, Response $response): void
    {
        $data = $request->getData();
        ($this->orm)($this->entity)
            ->where('id')->is($data['id'])
            ->delete();

        header('location: /Blog');
        exit();
    }

    #[Route(path: '/Blog/Comment/hide', method: 'get')]
    public function hide(Request $request, Response $response): void
    {
        $data = $request->getData();
        ($this->orm)($this->entity)
            ->where('id')->is($data['id'])
            ->update([
                'hidden' => 1
            ]);

        $comment = $this->orm->query($this->entity)
            ->find($data['id'])
            ->setHidden(1);
        $this->orm->save($comment);

        header('location: /Blog');
        exit();
    }
}