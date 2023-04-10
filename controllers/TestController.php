<?php declare(strict_types=1);

namespace f7k\Controller;

use f7k\Sources\attributes\Route;
use f7k\Sources\{Request, Response};

class TestController extends CommentsController {

    protected string $menuItem = 'Test';
    protected string $templateFile = 'Test.partial.html';

    public function __construct(protected Request $request, protected Response $response)
    {
        parent::__construct($request, $response);

        $this->template->assign([
            'title' => "My Test Suite"
        ]);
    }

    #[Route(path: '/Test', method: 'get')]
    public function main(): void
    {
        parent::main();
    }

    #[Route(path: '/Test/Reply', method: 'get')]
    public function reply(): void
    {
        parent::reply();
    }

    #[Route(path: '/Test/Comments/edit', method: 'get')]
    public function editComment(): void
    {
        parent::editComment();
    }

    #[Route(path: '/Test/Reply/edit', method: 'get')]
    public function editReply(): void
    {
        parent::editReply();
    }

    #[Route(path: '/Test/Comments/create', method: 'post')]
    public function createComment(): void
    {
        parent::createComment();
    }

    #[Route(path: '/Test/Comments/update', method: 'post')]
    public function updateComment(): void
    {
        parent::updateComment();
    }

    #[Route(path: '/Test/Comments/hide', method: 'get')]
    public function hideComment(): void
    {
        parent::hideComment();
    }

    #[Route(path: '/Test/Comments/delete', method: 'get')]
    public function deleteComment(): void
    {
        parent::deleteComment();
    }

    #[Route(path: '/Test/Reply/create', method: 'post')]
    public function createReply(): void
    {
        parent::createReply();
    }

    #[Route(path: '/Test/Reply/update', method: 'post')]
    public function updateReply(): void
    {
        parent::updateReply();
    }

    #[Route(path: '/Test/Reply/hide', method: 'get')]
    public function hideReply(): void
    {
        parent::hideReply();
    }

    #[Route(path: '/Test/Reply/delete', method: 'get')]
    public function deleteReply(): void
    {
        parent::deleteReply();
    }
}