<?php declare(strict_types=1);

namespace f7k\Sources;

use f7k\Sources\interfaces\AppInterface;
use f7k\Controller\NotFoundController;
use f7k\Sources\Request;
use f7k\Sources\Response;
use f7k\Sources\Router;

class Application implements AppInterface {

    protected Request $request;
    protected Response $response;
    protected Router $router;

    public function execute(): void
    {
        $this->request = new Request();
        $this->response = new Response();
        
        $this->router = new Router($this->request, $this->response);
        $this->router->notFound(function() {
            header("location: /PageNotFound");
            exit();
        });
        $this->router->run();

        $this->response->flush();
    }
}