<?php declare(strict_types=1);

namespace f7k\Controller;

use f7k\Sources\attributes\Route;

class BlogController extends CommentsController {

    protected string $menuItem = 'Blog';
    protected string $templateFile = 'Blog.partial.html';

    #[Route(path: '/Blog', method: 'get')]
    public function main(): void
    {
        parent::main();
    }

    #[Route(path: '/Blog/Reply', method: 'get')]
    public function reply(): void
    {
        parent::reply();
    }

    #[Route(path: '/Blog/Comments/edit', method: 'get')]
    public function editComment(): void
    {
        parent::editComment();
    }

    #[Route(path: '/Blog/Reply/edit', method: 'get')]
    public function editReply(): void
    {
        parent::editReply();
    }

    #[Route(path: '/Blog/Comments/create', method: 'post')]
    public function createComment(): void
    {
        parent::createComment();
    }

    #[Route(path: '/Blog/Comments/update', method: 'post')]
    public function updateComment(): void
    {
        parent::updateComment();
    }

    #[Route(path: '/Blog/Comments/hide', method: 'get')]
    public function hideComment(): void
    {
        parent::hideComment();
    }

    #[Route(path: '/Blog/Comments/delete', method: 'get')]
    public function deleteComment(): void
    {
        parent::deleteComment();
    }

    #[Route(path: '/Blog/Reply/create', method: 'post')]
    public function createReply(): void
    {
        parent::createReply();
    }

    #[Route(path: '/Blog/Reply/update', method: 'post')]
    public function updateReply(): void
    {
        parent::updateReply();
    }

    #[Route(path: '/Blog/Reply/hide', method: 'get')]
    public function hideReply(): void
    {
        parent::hideReply();
    }

    #[Route(path: '/Blog/Reply/delete', method: 'get')]
    public function deleteReply(): void
    {
        parent::deleteReply();
    }
}