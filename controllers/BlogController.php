<?php declare(strict_types=1);

namespace f7k\Controller;

use f7k\Service\CommentsService;
use f7k\Sources\attributes\{Inject, Route};
use f7k\Sources\{Request, Response};

class BlogController extends AppController {

    #[Inject(CommentsService::class)]
    protected $comments;

    public function __construct(protected Request $request, protected Response $response)
    {
        parent::__construct($request, $response);

        $this->template->assign([
            'title' => 'Blog',
            'route' => '/Blog',
            "expanded" => false,
            "href_reply" => "/Blog/reply?id=",
        ]);
    }

    #[Route(path: '/Blog', method: 'get')]
    public function main(): void
    {
        $text = '';
        if (isset($_SESSION['temp'])) {
            $tmpData = $_SESSION['temp'][$this->request->getRoute()];
            $text = $tmpData['comment'];
            unset($_SESSION['temp']);
        }

        $this->template->assign([
            "comments" => $this->comments->readAll('/Blog'),
            "expanded" => !empty($text),
            "text" => $text
        ]);
        $this->template->parse('Blog.partial.html');
        $this->template->render($this->request, $this->response);
    }

    #[Route(path: '/Blog/reply', method: 'get')]
    public function reply(): void
    {
        $text = '';
        if (isset($_SESSION['temp'])) {
            $tmpData = $_SESSION['temp'][$this->request->getRoute()];
            $this->data['id'] = $tmpData['comment_id'];
            $text = $tmpData['comment'];
            unset($_SESSION['temp']);
        }
        
        $this->template->assign([
            'isReply' => true,
            'form_header' => 'Reply to #' . $this->data['id'],
            'comment_id' => $this->data['id'],
            'text' => $text,
            "comments" => [$this->comments->read((int) $this->data['id'])]
        ]);
        $this->template->parse('Blog.partial.html');
        $this->template->render($this->request, $this->response);
    }

    #[Route(path: '/Blog/Comments/edit', method: 'get')]
    public function editComment(): void
    {
        $text = '';
        if (isset($_SESSION['temp'])) {
            $tmpData = $_SESSION['temp'][$this->request->getRoute()];
            $this->data['id'] = $tmpData['comment_id'];
            $text = $tmpData['comment'];
            unset($_SESSION['temp']);
        }
        
        $comment = $this->comments->read((int) $this->data['id']);
        $this->template->assign([
            'isUpdate' => true,
            'form_header' => 'Edit Comment #' . $this->data['id'],
            'comment_id' => $this->data['id'],
            "name" => $comment->getName(),
            "email" => $comment->getEmail(),
            "text" => $text ? $text : $comment->getComment(),
            "comments" => [$comment]
        ]);
        $this->template->parse('Blog.partial.html');
        $this->template->render($this->request, $this->response);
    }

    #[Route(path: '/Blog/Reply/edit', method: 'get')]
    public function editReply(): void
    {
        if (!$this->auth->isLoggedIn()) {
            $this->auth->login();
        }

        $text = '';
        if (isset($_SESSION['temp'])) {
            $tmpData = $_SESSION['temp'][$this->request->getRoute()];
            $this->data = $tmpData;
            $text = $tmpData['comment'];
            unset($_SESSION['temp']);
        }
        
        $comment = $this->comments->read((int) $this->data['comment_id']);
        $reply = $this->comments->getReply((int) $this->data['id']);
        $this->template->assign([
            'isReplyUpdate' => true,
            'form_header' => 'Edit Reply #' . $reply->id(),
            'comment_id' => $reply->getCommentID(),
            'id' => $reply->id(),
            "name" => $reply->getName(),
            "email" => $reply->getEmail(),
            "text" => $text ? $text : $reply->getReply(),
            "comments" => [$comment]
        ]);
        $this->template->parse('Blog.partial.html');
        $this->template->render($this->request, $this->response);
    }

    #[Route(path: '/Blog/Comments/create', method: 'post')]
    public function createComment(): void
    {
        if (!$this->auth->isLoggedIn()) {
            $_SESSION['temp'] = [
                "/Blog" => $this->data
            ];
            $this->auth->login();
        }
        
        $this->comments->create($this->data);
        header("location: /Blog#comments");
        exit();
    }

    #[Route(path: '/Blog/Comments/update', method: 'post')]
    public function updateComment(): void
    {
        if (!$this->auth->isLoggedIn()) {
            $_SESSION['temp'] = [
                "/Blog/Comments/edit" => $this->data
            ];
            $this->auth->login();
        }
        
        $id = $this->comments->updateComment($this->data);
        header("location: /Blog#C" . $id);
        exit();
    }

    #[Route(path: '/Blog/Comments/hide', method: 'get')]
    public function hideComment(): void
    {
        if (!$this->auth->isLoggedIn()) {
            $this->auth->login();
        }
        
        $this->comments->hide((int) $this->data['id']);
        header("location: /Blog#comments");
        exit();
    }

    #[Route(path: '/Blog/Comments/delete', method: 'get')]
    public function deleteComment(): void
    {
        if (!$this->auth->isLoggedIn()) {
            $this->auth->login();
        }
        
        $this->comments->delete((int) $this->data['id']);
        header("location: /Blog#comments");
        exit();
    }

    #[Route(path: '/Blog/Reply/create', method: 'post')]
    public function createReply(): void
    {
        if (!$this->auth->isLoggedIn()) {
            $_SESSION['temp'] = [
                "/Blog/reply" => $this->data
            ];
            $this->auth->login();
        } 
        
        $id = $this->comments->createReply($this->data);
        header("location: /Blog#R" . $id);
        exit();
    }

    #[Route(path: '/Blog/Reply/update', method: 'post')]
    public function updateReply(): void
    {
        if (!$this->auth->isLoggedIn()) {
            $_SESSION['temp'] = [
                "/Blog/Reply/edit" => $this->data
            ];
            $this->auth->login();
        }
        
        $id = $this->comments->updateReply($this->data);
        header("location: /Blog#R" . $id);
        exit();
    }

    #[Route(path: '/Blog/Reply/hide', method: 'get')]
    public function hideReply(): void
    {
        if (!$this->auth->isLoggedIn()) {
            $this->auth->login();
        }
        
        $id = $this->comments->hideReply((int) $this->data['id']);
        header("location: /Blog#R" . $id);
        exit();
    }

    #[Route(path: '/Blog/Reply/delete', method: 'get')]
    public function deleteReply(): void
    {
        if (!$this->auth->isLoggedIn()) {
            $this->auth->login();
        }
        
        $this->comments->deleteReply((int) $this->data['id']);
        header("location: /Blog#C" . $this->data['comment_id']);
        exit();
    }
}