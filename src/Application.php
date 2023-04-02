<?php declare(strict_types=1);

namespace f7k\Sources;

use f7k\Sources\interfaces\AppInterface;
use f7k\Sources\Request;
use f7k\Sources\Response;
use f7k\Sources\Router;

class Application implements AppInterface {

    public function execute(): void
    {
        $request = new Request();
        $response = new Response();
        
        $router = new Router($request, $response);
        $router->notFound(function() {
            header("location: /PageNotFound");
            exit();
        });
        $router->run();

        $response->flush();
    }
}