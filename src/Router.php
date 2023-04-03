<?php declare(strict_types=1);

namespace f7k\Sources;

use f7k\Sources\attributes\Route;

class Router {

    private Request $request;
    private Response $response;
    private array $handlers;
    private $notFoundHandler;

    private const GET = "get";
    private const POST = "post";

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;

        $routesCacheFile = ROOT . "/.routes.cache";

        if (file_exists($routesCacheFile) && $_ENV["MODE"] !== "dev") {
            $routesCache = file($routesCacheFile)[0];
            $this->handlers = unserialize($routesCache);
        } else {
            $handle = fopen($routesCacheFile, "w");

            $files = array_diff(scandir(ROOT . "/controllers"), array('.', '..'));

            foreach ($files as $file) {
                $controller = "f7k\\Controller\\" . explode(".", $file)[0];
                $reflectionController = new \ReflectionClass($controller);

                foreach ($reflectionController->getMethods() as $method) {
                    $attributes = $method->getAttributes(Route::class);

                    foreach ($attributes as $attribute) {
                        $route = $attribute->newInstance();

                        $rqMethod = strtolower($route->method);
                        if (is_array($route->path)) {
                            foreach($route->path as $rt) {
                                $this->handlers[$rqMethod . $rt] = [
                                    "method" => $rqMethod,
                                    "path" => $rt,
                                    "callback" => [$controller, $method->getName()]
                                ];
                            }
                        } else {
                            $this->handlers[$rqMethod . $route->path] = [
                                "method" => $rqMethod,
                                "path" => $route->path,
                                "callback" => [$controller, $method->getName()]
                            ];
                        }
                    }
                }
            }

            fwrite($handle, serialize($this->handlers));
        }
    }

    // public function get(string $path, callable |array $callback): void
    // {
    //     $this->addHandlers(self::GET, $path, $callback);
    // }

    // public function post(string $path, callable |array $callback): void
    // {
    //     $this->addHandlers(self::POST, $path, $callback);
    // }

    // private function addHandlers(string $method, string $path, callable |array $callback): void
    // {
    //     $method = strtolower($method);
    //     $this->handlers[$method . $path] = [
    //         "method" => $method,
    //         "path" => $path,
    //         "callback" => $callback
    //     ];
    // }

    public function notFound(callable|array $callback): void
    {
        $this->notFoundHandler = $callback;
    }

    public function run(): void
    {
        $route = $this->request->getRoute();
        $method = $this->request->getMethod();

        if (isset($this->handlers[$method . $route])) {
            $handler = $this->handlers[$method . $route];

            if (is_array($handler["callback"]) && count($handler["callback"]) == 2) {
                $callback = [new $handler["callback"][0]($this->request, $this->response), $handler["callback"][1]];
            } else if (is_array($handler["callback"]) && count($handler["callback"]) != 2) {
                $callback = null;
            } else {
                $callback = $handler["callback"];
            }

            if ($callback) {
                $res = call_user_func_array($callback, []);
                if ($res === false) {
                    $this->response->setStatus(500);
                }
            }
        } else {
            if ($this->notFoundHandler) {
                $res = call_user_func_array($this->notFoundHandler, []);
                if ($res === false) {
                    $this->response->setStatus(500);
                }
            } else {
                $this->response->setStatus(404);
            }
        }
    }
}