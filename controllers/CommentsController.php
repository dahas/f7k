<?php declare(strict_types=1);

namespace f7k\Controller;

use f7k\Service\CommentsService;
use f7k\Sources\attributes\Inject;
use f7k\Sources\{Request, Response};

class CommentsController extends AppController  {

    #[Inject(CommentsService::class)]
    protected $comments;

    protected string $menuItem;
    protected string $templateFile;
    protected int $articleId = 0;

    public function __construct(protected Request $request, protected Response $response)
    {
        if (!isset($this->menuItem) || !$this->menuItem) {
            throw new \f7k\Sources\exceptions\InvalidConfigException(
                "Protected parameter '\$menuItem' missing! Must be set in child class to overwrite parent setting."
            );
        }

        if (!isset($this->templateFile) || !$this->templateFile) {
            throw new \f7k\Sources\exceptions\InvalidConfigException(
                "Protected parameter '\$templateFile' missing! Must be set in child class to overwrite parent setting."
            );
        }

        parent::__construct($request, $response);

        $this->template->assign([
            'route' => '/' . $this->menuItem,
            "expanded" => false,
        ]);
    }

    public function renderAll(): void
    {
        $text = '';
        if (isset($_SESSION['temp'])) {
            $tmpData = $_SESSION['temp'][$this->request->getRoute()];
            $text = $tmpData['comment'];
            unset($_SESSION['temp']);
        }

        $this->template->assign([
            "comments" => $this->comments->readAll('/' . $this->menuItem, $this->articleId),
            "expanded" => !empty($text),
            "text" => $text
        ]);
        $this->template->parse($this->templateFile);
        $this->template->render($this->request, $this->response);
    }

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
        $this->template->parse($this->templateFile);
        $this->template->render($this->request, $this->response);
    }

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
        $this->template->parse($this->templateFile);
        $this->template->render($this->request, $this->response);
    }

    public function editReply(): void
    {
        if (!$this->auth->isLoggedIn()) {
            $this->auth->login();
        }

        $text = '';
        if (isset($_SESSION['temp'])) {
            $tmpData = $_SESSION['temp'][$this->request->getRoute()."?article={$this->data['article']}"];
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
        $this->template->parse($this->templateFile);
        $this->template->render($this->request, $this->response);
    }

    public function createComment(): void
    {
        if (!$this->auth->isLoggedIn()) {
            $_SESSION['temp'] = [
                "/{$this->menuItem}?article={$this->data['article']}" => $this->data
            ];
            $this->auth->login();
        }
        
        $this->comments->create($this->data);
        
        header("location: /{$this->menuItem}?article={$this->data['article']}#comments");
        exit();
    }

    public function updateComment(): void
    {
        if (!$this->auth->isLoggedIn()) {
            $_SESSION['temp'] = [
                "/{$this->menuItem}/Comments/edit?article={$this->data['article']}" => $this->data
            ];
            $this->auth->login();
        }
        
        $id = $this->comments->updateComment($this->data);

        header("location: /{$this->menuItem}?article={$this->data['article']}#C" . $id);
        exit();
    }

    public function hideComment(): void
    {
        if (!$this->auth->isLoggedIn()) {
            $this->auth->login();
        }
        
        $this->comments->hide((int) $this->data['id']);

        header("location: /{$this->menuItem}?article={$this->data['article']}#comments");
        exit();
    }

    public function deleteComment(): void
    {
        if (!$this->auth->isLoggedIn()) {
            $this->auth->login();
        }
        
        $this->comments->delete((int) $this->data['id']);

        header("location: /{$this->menuItem}?article={$this->data['article']}#comments");
        exit();
    }

    public function createReply(): void
    {
        if (!$this->auth->isLoggedIn()) {
            $_SESSION['temp'] = [
                "/{$this->menuItem}/Reply?article={$this->data['article']}" => $this->data
            ];
            $this->auth->login();
        } 
        
        $id = $this->comments->createReply($this->data);

        header("location: /{$this->menuItem}?article={$this->data['article']}#R" . $id);
        exit();
    }

    public function updateReply(): void
    {
        if (!$this->auth->isLoggedIn()) {
            $_SESSION['temp'] = [
                "/{$this->menuItem}/Reply/edit?article={$this->data['article']}" => $this->data
            ];
            $this->auth->login();
        }
        
        $id = $this->comments->updateReply($this->data);

        header("location: /{$this->menuItem}?article={$this->data['article']}#R" . $id);
        exit();
    }

    public function hideReply(): void
    {
        if (!$this->auth->isLoggedIn()) {
            $this->auth->login();
        }
        
        $id = $this->comments->hideReply((int) $this->data['id']);

        header("location: /{$this->menuItem}?article={$this->data['article']}#R" . $id);
        exit();
    }

    public function deleteReply(): void
    {
        if (!$this->auth->isLoggedIn()) {
            $this->auth->login();
        }
        
        $this->comments->deleteReply((int) $this->data['id']);

        header("location: /{$this->menuItem}?article={$this->data['article']}#C" . $this->data['comment_id']);
        exit();
    }
}