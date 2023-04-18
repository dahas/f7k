<?php

namespace f7k\Sources;

use f7k\Sources\interfaces\SessionInterface;

class Session implements SessionInterface {

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

    public function issetTemp(): bool
    {
        return isset($_SESSION['temp']);
    }

    public function getTempRoute(): string
    {
        return key($_SESSION['temp']);
    }

    public function getTempData(string $route): mixed
    {
        return $_SESSION['temp'][$route] ?? null;
    }

    public function setTempData(string $route, mixed $data): void
    {
        $_SESSION['temp'][$route] = $data;
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