<?php declare(strict_types=1);

namespace f7k\Controller;

use f7k\Service\{TemplateService, MenuService, AuthenticationService};
use f7k\Sources\attributes\{Inject, Route};
use f7k\Sources\{ControllerBase, Request, Response};

class AppController extends ControllerBase {

    #[Inject(TemplateService::class)]
    protected $template;

    #[Inject(MenuService::class)]
    protected $menu;

    #[Inject(AuthenticationService::class)]
    protected $auth;

    protected array $data;
    protected bool $isLoggedIn;
    protected bool $isAdmin;

    public function __construct(protected Request $request, protected Response $response)
    {
        parent::__construct();

        $this->data = $this->request->getData();

        $this->isLoggedIn = $this->auth->isLoggedIn();
        $this->isAdmin = $this->auth->isAdmin();

        $this->template->assign([
            "e2eTestMode" => $_ENV['MODE'] === 'test',
            "nav" => $this->menu->getItems(),
            "user" => $this->auth->isLoggedIn() ? $_SESSION['user'] : [],
            "isLoggedIn" => $this->isLoggedIn,
            "isAdmin" => $this->isAdmin,
            "currentPath" => "/" . $this->request->getSegment(0)
        ]);
    }

    #[Route(path: '/Auth/login', method: 'get')]
    public function login(): void 
    {
        if(!isset($this->data['state'])) {
            $_SESSION['redirect'] = $this->request->getReferer();
        }
        
        $this->auth->login();

        $redirect = "/";
        if(isset($_SESSION['redirect'])) {
            $redirect = $_SESSION['redirect'];
            unset($_SESSION['redirect']);
        }
        if(isset($_SESSION['temp'])) {
            $redirect = key($_SESSION['temp']);
        }

        $this->response->redirect($redirect);
    }

    #[Route(path: '/Auth/logout', method: 'get')]
    public function logout(): void 
    {
        if(!isset($this->data['state'])) {
            $_SESSION['redirect'] = $this->request->getReferer();
        }

        $this->auth->logout();

        $redirect = "/";
        if(isset($_SESSION['redirect'])) {
            $redirect = $_SESSION['redirect'];
            unset($_SESSION['redirect']);
        }

        $this->response->redirect($redirect);
    }
}