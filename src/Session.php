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

    public function storeTemporay(mixed $temp): void
    {
        $_SESSION['temp'] = $temp;
    }

    public function destroy(): void
    {
    }
}