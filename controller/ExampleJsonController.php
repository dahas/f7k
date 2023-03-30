<?php declare(strict_types=1);

namespace f7k\Controller;

use f7k\Library\JsonAdapter;
use f7k\Sources\attributes\{Inject, Route};
use f7k\Sources\{Request, Response};

class ExampleJsonController extends AppController {

    #[Inject(JsonAdapter::class)]
    protected $json;
    
    #[Route(path: '/ExampleJson', method: 'get')]
    public function main(Request $request, Response $response): void
    {
        $this->json->setMessage("Success");
        $this->json->setData([
            ["id" => 1, "manufacturer" => "Ferrari", "year" => 1958],
            ["id" => 2, "manufacturer" => "Lotus", "year" => 1973],
            ["id" => 3, "manufacturer" => "Mercedes Benz", "year" => 2012],
        ]);
        $this->json->addField("url", "http://localhost:2400/ExampleJson");
        $this->json->encode($request, $response);
    }
}