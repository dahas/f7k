<?php

namespace f7k\Sources;

use f7k\Sources\interfaces\SessionInterface;

class Session implements SessionInterface {

    public function start(): void
    {
        session_start();
    }

    public function get(string $name): mixed
    {
        return $_SESSION[$name];
    }

    public function set(string $name, mixed $value): void
    {
        $_SESSION[$name] = $value;
    }

    public function unset(string $name): void
    {
        unset($_SESSION[$name]);
    }

    public function getTemp(): mixed
    {
        return $_SESSION['temp'] ?? null;
    }

    public function setTemp(mixed $temp): void
    {
        $_SESSION['temp'] = $temp;
    }

    public function unsetTemp(): void
    {
        unset($_SESSION['temp']);
    }

    public function destroy(): void
    {
        session_destroy();
    }
}