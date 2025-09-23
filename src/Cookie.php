<?php

namespace f7k\Sources;

use f7k\Sources\interfaces\CookieInterface;

class Cookie implements CookieInterface
{
    private array $data;

    public function __construct(
        private string $name,
        private int    $expires = 0,
        private string $path = "/"
    )
    {
    }

    public function set(string $key, mixed $value): void
    {
        if (isset($_COOKIE[$this->name])) {
            $this->data = json_decode($_COOKIE[$this->name]);
            $this->data[$key] = $value;
            $_COOKIE[$this->name] = json_encode($this->data);
        } else {
            setcookie($this->name, json_encode([$key => $value]), $this->expires, $this->path);
        }
    }

    public function get(string $key): mixed
    {
        return $_COOKIE[$this->name][$key];
    }

    public function destroy(): void
    {
        if (isset($_COOKIE[$this->name])) {
            unset($_COOKIE[$this->name]);
            setcookie($this->name, null, -1, '/');
        }
    }
}