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

    public function __construct(protected Request $request, protected Response $response)
    {
        parent::__construct();

        $this->data = $this->request->getData();

        $this->template->assign([
            "nav" => $this->menu->getItems(),
            "user" => $this->auth->isLoggedIn() ? $_SESSION['user'] : [],
            "isLoggedIn" => $this->auth->isLoggedIn(),
            "currentPath" => "/" . $this->request->getSegments()[0]
        ]);
        $this->template->parse('Menu.partial.html');
    }

    #[Route(path: '/Auth/login', method: 'get')]
    public function login(): void 
    {
        if(isset($this->data['redirect']) && $this->data['redirect']) {
            $_SESSION['redirect'] = $this->data['redirect'];
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

        header("location: $redirect");
        exit();
    }

    #[Route(path: '/Auth/logout', method: 'get')]
    public function logout(): void 
    {
        if(isset($this->data['redirect']) && $this->data['redirect']) {
            $_SESSION['redirect'] = $this->data['redirect'];
        }

        $this->auth->logout();

        $redirect = "/";
        if(isset($_SESSION['redirect'])) {
            $redirect = $_SESSION['redirect'];
            unset($_SESSION['redirect']);
        }

        header("location: $redirect");
        exit();
    }
}