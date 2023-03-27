<?php

namespace PHPSkeleton\Library;

use PHPSkeleton\Sources\Request;
use PHPSkeleton\Sources\Response;

class JsonAdapter {

    private string $message = "";
    private array $data = [];

    public function setMessage(string $message)
    {
        $this->message = $message;
    }

    public function setData(array $data): void
    {
        $this->data = array_merge($this->data, $data);
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function encode(Request $request, Response $response): void
    {
        $json = json_encode([
            "message" => $this->message,
            "data" => $this->data,
            "count" => count($this->data)
        ]);

        $response->addHeader("Content-Type", "application/json");
        $response->write($json);
    }
}