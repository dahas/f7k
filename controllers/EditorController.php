<?php declare(strict_types=1);

namespace f7k\Controller;

use f7k\Service\ArticlesService;
use f7k\Sources\attributes\{Inject, Route};
use f7k\Sources\{Request, Response};

class EditorController extends AppController {

    #[Inject(ArticlesService::class)]
    protected $articles;

    #[Route(path: '/Editor', method: 'get')]
    public function main(): void
    {
        if (!$this->auth->isLoggedIn()) {
            $_SESSION['redirect'] = $this->request->getUri();
            $this->auth->login();
        }

        if (isset($_SESSION['temp'])) {
            $tmpData = $_SESSION['temp'][$this->request->getUri()];
            $this->data = $tmpData;
            unset($_SESSION['temp']);
        }

        $this->template->assign([
            'title' => "Create an Article",
            'articleTitle' => $this->data['title'],
            'articleDescription' => $this->data['description'],
            'articleText' => $this->data['articleText'],
            'articlePage' => $this->data['page'],
            'articleHidden' => $this->data['hidden']
        ]);

        $this->template->parse('Editor.partial.html');
        $this->template->render($this->request, $this->response);
    }

    #[Route(path: '/Editor/edit', method: 'get')]
    public function edit(): void
    {
        if (!$this->auth->isLoggedIn()) {
            $_SESSION['redirect'] = $this->request->getUri();
            $this->auth->login();
        }

        if (isset($_SESSION['temp'])) {
            $tmpData = $_SESSION['temp'][$this->request->getUri()];
            $this->data = $tmpData;
            unset($_SESSION['temp']);

            $this->template->assign([
                'title' => "Create an Article",
                'redirect' => $this->data['redirect'],
                'articleId' => $this->data['articleId'],
                'articleTitle' => $this->data['title'],
                'articleDescription' => $this->data['description'],
                'articleText' => $this->data['articleText'],
                'articlePage' => $this->data['page'],
                'articleHidden' => $this->data['hidden']
            ]);
        } else {
            $article = $this->articles->read((int) $this->data['articleId']);

            $this->template->assign([
                'title' => "Edit an Article",
                'redirect' => $this->data['redirect'],
                'articleId' => $article->id(),
                'articleTitle' => $article->getTitle(),
                'articleDescription' => $article->getDescription(),
                'articleText' => $article->getArticle(),
                'articlePage' => $article->getPage(),
                'articleHidden' => $article->getHidden()
            ]);
        }

        $this->template->parse('Editor.partial.html');
        $this->template->render($this->request, $this->response);
    }

    #[Route(path: '/Editor/create', method: 'post')]
    public function createArticle(): void
    {
        if (!$this->auth->isLoggedIn()) {
            $_SESSION['temp'] = [
                "/Editor?page={$this->data['page']}" => $this->data
            ];
            $this->auth->login();
        }
        
        $this->articles->create($this->data);

        header("location: " . $this->data['page']);
        exit();
    }

    #[Route(path: '/Editor/update', method: 'post')]
    public function updateArticle(): void
    {
        if (!$this->auth->isLoggedIn()) {
            $_SESSION['temp'] = [
                "/Editor/edit?page={$this->data['page']}" => $this->data
            ];
            $this->auth->login();
        }

        $this->articles->update($this->data);

        header("location: " . $this->data['redirect']);
        exit();
    }

    #[Route(path: '/Editor/hide', method: 'get')]
    public function hideArticle(): void
    {
        if (!$this->auth->isLoggedIn()) {
            $_SESSION['redirect'] = $this->request->getUri();
            $this->auth->login();
        }

        $this->articles->hide((int) $this->data['articleId']);

        header("location: " . $this->data['page']);
        exit();
    }

    #[Route(path: '/Editor/delete', method: 'get')]
    public function deleteArticle(): void
    {
        if (!$this->auth->isLoggedIn()) {
            $_SESSION['redirect'] = $this->request->getUri();
            $this->auth->login();
        }

        $this->articles->delete((int) $this->data['articleId']);

        header("location: " . $this->data['page']);
        exit();
    }

    #[Route(path: '/Editor/upload', method: 'post')]
    public function upload(): void
    {
        $accepted_origins = array_map('trim', explode(",", $_ENV['ALLOW_ORIGINS']));

        $imageFolder = ROOT . "/public/blog_files/";

        if (isset($_SERVER['HTTP_ORIGIN'])) {
            // same-origin requests won't set an origin. If the origin is set, it must be valid.
            if (in_array($_SERVER['HTTP_ORIGIN'], $accepted_origins)) {
                $this->response->addHeader('Access-Control-Allow-Origin', $_SERVER['HTTP_ORIGIN']);
            } else {
                $this->response->setStatus(403, "Origin Denied");
                return;
            }
        }

        reset($_FILES);
        $temp = current($_FILES);
        if (is_uploaded_file($temp['tmp_name'])) {
            /*
            If your script needs to receive cookies, set images_upload_credentials : true in
            the configuration and enable the following two headers.
            */
            // header('Access-Control-Allow-Credentials: true');
            // header('P3P: CP="There is no P3P policy."');

            // Sanitize input
            if (preg_match("/([^\w\s\d\-_~,;:\[\]\(\).])|([\.]{2,})/", $temp['name'])) {
                $this->response->setStatus(400, "Invalid File Name");
                return;
            }

            // Verify extension
            if (!in_array(strtolower(pathinfo($temp['name'], PATHINFO_EXTENSION)), array("gif", "jpg", "png"))) {
                $this->response->setStatus(400, "Invalid File Type");
                return;
            }

            $filetowrite = $imageFolder . $temp['name'];
            if (move_uploaded_file($temp['tmp_name'], $filetowrite)) {
                // Respond to the successful upload with JSON.
                // Use a location key to specify the path to the saved image resource.
                // { location : '/blog_files/file.ext'}
                $json = json_encode(array('location' => '/blog_files/' . $temp['name']));
                $this->response->setStatus(200);
                $this->response->addHeader("Content-Type", "application/json");
                $this->response->write($json);
            } else {
                $this->response->setStatus(500);
            }
        } else {
            $this->response->setStatus(500);
        }
    }
}