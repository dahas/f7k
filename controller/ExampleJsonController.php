<?php declare(strict_types=1);

namespace f7k\Controller;

use f7k\Sources\attributes\Route;
use f7k\Sources\{Request, Response};

class ExampleJsonController extends AppController {

    #[Route(path: '/ExampleJson', method: 'get')]
    public function main(Request $request, Response $response): void
    {
        $data = [
            ["id" => 1, "manufacturer" => "Ferrari", "year" => 1958],
            ["id" => 2, "manufacturer" => "Lotus", "year" => 1973],
            ["id" => 3, "manufacturer" => "Mercedes Benz", "year" => 2012],
        ];

        $json = json_encode([
            "message" => "Success",
            "url" => "http://localhost:2400/ExampleJson",
            "data" => $data,
            "count" => count($data),
        ]);

        $response->addHeader("Content-Type", "application/json");
        $response->write($json);
    }
}