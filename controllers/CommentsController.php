<?php declare(strict_types=1);

namespace f7k\Controller;

use f7k\Service\CommentsService;
use f7k\Sources\attributes\Inject;
use f7k\Sources\{Request, Response};

class CommentsController extends AppController {

    #[Inject(CommentsService::class)]
    protected $comments;

    protected string $page;
    protected string $route;
    protected string $templateFile;

    public function __construct(
        protected Request $request,
        protected Response $response
    ) {
        if (!isset($this->page) || !$this->page) {
            throw new \f7k\Sources\exceptions\InvalidConfigException(
                "Protected parameter '\$page' missing! Must be set in child class to overwrite parent setting."
            );
        }

        if (!isset($this->route) || !$this->route) {
            throw new \f7k\Sources\exceptions\InvalidConfigException(
                "Protected parameter '\$route' missing! Must be set in child class to overwrite parent setting."
            );
        }

        if (!isset($this->templateFile) || !$this->templateFile) {
            throw new \f7k\Sources\exceptions\InvalidConfigException(
                "Protected parameter '\$templateFile' missing! Must be set in child class to overwrite parent setting."
            );
        }

        parent::__construct($request, $response);

        $this->template->assign([
            'page' => '/' . $this->page,
            "expanded" => false,
        ]);
    }

    public function renderComments(string $page, int $articleId = 0): void
    {
        $text = '';
        if ($this->session->issetTemp()) {
            $tmpData = $this->session->getTempData($this->request->getUri());
            $text = $tmpData['comment'];
            $this->session->unsetTemp();
        }

        $this->template->assign([
            "comments" => $this->comments->readAll($page, $articleId),
            'route' => $this->route,
            "expanded" => !empty($text) || $this->data['expanded'],
            "text" => $text
        ]);
        $this->template->parse($this->templateFile);
        $this->template->render($this->request, $this->response);
    }

    public function reply(): void
    {
        if (!$this->auth->isLoggedIn()) {
            $_SESSION['redirect'] = $this->request->getUri();
            $this->auth->login();
        }

        $text = '';
        if ($this->session->issetTemp()) {
            $tmpData = $this->session->getTempData($this->request->getUri());
            $this->data['id'] = $tmpData['comment_id'];
            $text = $tmpData['comment'];
            $this->session->unsetTemp();
        }

        $comment = $this->comments->read((int) $this->data['id']);

        if ($comment) {
            $this->template->assign([
                'isReply' => true,
                'route' => $this->route,
                'form_header' => 'Reply to #' . $this->data['id'],
                'comment_id' => $this->data['id'],
                'text' => $text,
                "comments" => [$comment]
            ]);
            $this->template->parse($this->templateFile);
            $this->template->render($this->request, $this->response);
        } else {
            $this->response->redirect("/PageNotFound");
        }
    }

    public function editComment(): void
    {
        if (!$this->auth->isLoggedIn()) {
            $_SESSION['redirect'] = $this->request->getUri();
            $this->auth->login();
        }

        $text = '';
        if ($this->session->issetTemp()) {
            $tmpData = $this->session->getTempData($this->request->getUri());
            $this->data['id'] = $tmpData['comment_id'];
            $text = $tmpData['comment'];
            $this->session->unsetTemp();
        }

        $comment = $this->comments->read((int) $this->data['id']);

        if ($comment) {
            if (!$this->auth->isAuthorized($comment->getEmail())) {
                $this->response->redirect("/PermissionDenied");
            }

            $this->template->assign([
                'isUpdate' => true,
                'route' => $this->route,
                'form_header' => 'Edit Comment #' . $this->data['id'],
                'comment_id' => $this->data['id'],
                "name" => $comment->getName(),
                "email" => $comment->getEmail(),
                "text" => $text ? $text : $comment->getComment(),
                "comments" => [$comment]
            ]);
            $this->template->parse($this->templateFile);
            $this->template->render($this->request, $this->response);
        } else {
            $this->response->redirect("/PageNotFound");
        }
    }

    public function editReply(): void
    {
        if (!$this->auth->isLoggedIn()) {
            $_SESSION['redirect'] = $this->request->getUri();
            $this->auth->login();
        }

        $text = '';
        if ($this->session->issetTemp()) {
            $tmpData = $this->session->getTempData($this->request->getUri());
            $this->data = $tmpData;
            $text = $tmpData['comment'];
            $this->session->unsetTemp();
        }

        $comment = $this->comments->read((int) $this->data['comment_id']);

        if ($comment) {
            $reply = $this->comments->getReply((int) $this->data['id']);

            if (!$this->auth->isAuthorized($reply->getEmail())) {
                $this->response->redirect("/PermissionDenied");
            }

            $this->template->assign([
                'isReplyUpdate' => true,
                'route' => $this->route,
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
        } else {
            $this->response->redirect("/PageNotFound");
        }
    }

    public function createComment(): void
    {
        $id = $this->comments->create($this->data);
        if ($id < 0) { // Not logged in!
            $this->session->setTempData($this->route, $this->data);
            $this->auth->login();
        }
        $this->response->redirect("{$this->route}#comments");
    }

    public function updateComment(): void
    {
        $id = $this->comments->updateComment($this->data);
        if ($id < 0) { // Not logged in!
            $this->session->setTempData("{$this->route}/Comments/edit/
                {$this->data['comment_id']}", $this->data);
            $this->auth->login();
        } else if ($id == 0) {
            $this->response->redirect("/PermissionDenied");
        }
        $this->response->redirect("{$this->route}#C{$id}");
    }

    public function hideComment(): void
    {
        $id = $this->comments->hide((int) $this->data['id']);
        if ($id < 0) { // Not logged in!
            $_SESSION['redirect'] = $this->request->getUri();
            $this->auth->login();
        } else if ($id == 0) {
            $this->response->redirect("/PermissionDenied");
        }
        $this->response->redirect("{$this->route}#comments");
    }

    public function deleteComment(): void
    {
        $id = $this->comments->delete((int) $this->data['id']);
        if ($id < 0) { // Not logged in!
            $_SESSION['redirect'] = $this->request->getUri();
            $this->auth->login();
        } else if ($id == 0) {
            $this->response->redirect("/PermissionDenied");
        }
        $this->response->redirect("{$this->route}#comments");
    }

    public function createReply(): void
    {
        $id = $this->comments->createReply($this->data);
        if ($id < 0) { // Not logged in!
            $this->session->setTempData("{$this->route}/Reply/
                {$this->data['articleId']}", $this->data);
            $this->auth->login();
        }
        $this->response->redirect("{$this->route}#R{$id}");
    }

    public function updateReply(): void
    {
        $id = $this->comments->updateReply($this->data);
        if ($id < 0) { // Not logged in!
            $this->session->setTempData("{$this->route}/Reply/edit/
                {$this->data['articleId']}", $this->data);
            $this->auth->login();
        } else if ($id == 0) {
            $this->response->redirect("/PermissionDenied");
        }
        $this->response->redirect("{$this->route}#R{$id}");
    }

    public function hideReply(): void
    {
        $id = $this->comments->hideReply((int) $this->data['id']);
        if ($id < 0) { // Not logged in!
            $_SESSION['redirect'] = $this->request->getUri();
            $this->auth->login();
        } else if ($id == 0) {
            $this->response->redirect("/PermissionDenied");
        }
        $this->response->redirect("{$this->route}#R{$id}");
    }

    public function deleteReply(): void
    {
        $id = $this->comments->deleteReply((int) $this->data['id']);
        if ($id < 0) { // Not logged in!
            $_SESSION['redirect'] = $this->request->getUri();
            $this->auth->login();
        } else if ($id == 0) {
            $this->response->redirect("/PermissionDenied");
        }
        $this->response->redirect("{$this->route}#C" .
            $this->data['comment_id']);
    }
}