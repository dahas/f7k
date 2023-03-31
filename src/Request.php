<?php

namespace f7k\Sources;

use f7k\Sources\traits\Utils;

final class Request {

    use Utils;

    private string $method;
    private string $route;
    private array $data = [];

    public function __construct()
    {
        $uri = $_SERVER['REQUEST_URI'] ?? "";
        $method = $_SERVER['REQUEST_METHOD'] ?? "get";
        $this->method = strtolower($method);
        $this->parseUri($uri);
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getRoute(): string
    {
        return $this->route;
    }

    public function getData(): array
    {
        return $this->data;
    }

    private function parseUri(string $uri = ""): void
    {
        $arrUri = parse_url($uri);
        $this->route = $arrUri['path'];
        $query = $arrUri['query'] ?? "";

        $qArr = [];
        if ($query) {
            parse_str($query, $qArr);
        }

        $dirtyVars = array_merge($qArr, $_GET, $_POST);
        $cleanVars = $this->sanitizeRequest($dirtyVars);

        $this->data = $cleanVars;

        unset($_GET);
        unset($_POST);
        unset($_REQUEST);
    }
}