<?php declare(strict_types=1);

namespace f7k\Sources;

use f7k\Sources\interfaces\AppInterface;

class Application implements AppInterface {

    public function execute(): void
    {
        session_name('sid');
        session_start();

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