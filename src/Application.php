<?php declare(strict_types=1);

namespace PHPSkeleton\Sources;

use PHPSkeleton\Sources\interfaces\AppInterface;
use PHPSkeleton\Controller\NotFoundController;
use PHPSkeleton\Sources\Request;
use PHPSkeleton\Sources\Response;
use PHPSkeleton\Sources\Router;

class Application implements AppInterface {

    protected Request $request;
    protected Response $response;
    protected Router $router;

    public function execute(): void
    {
        $this->request = new Request();
        $this->response = new Response();
        
        $this->router = new Router($this->request, $this->response);
        $this->router->notFound([new NotFoundController, "main"]);
        $this->router->run();

        $this->response->flush();
    }
}