<?php

namespace f7k\Library;

use f7k\Sources\{Request, Response};

class JsonAdapter {

    private string $message = "";
    private array $fields = [];
    private array $data = [];

    public function __construct(private array|null $options = [])
    {
    }

    public function setMessage(string $message)
    {
        $this->message = $message;
    }

    public function addField(string $name, mixed $value): void
    {
        $this->fields[$name] = $value;
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
        $jsonData = [
            "message" => $this->message,
            "data" => $this->data,
            "count" => count($this->data)
        ];
        $json = json_encode(array_merge($this->fields, $jsonData));

        $response->addHeader("Content-Type", "application/json");
        $response->write($json);
    }
}