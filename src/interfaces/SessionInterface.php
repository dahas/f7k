<?php declare(strict_types=1);

namespace f7k\Sources\interfaces;

interface SessionInterface {

    public function start(): void;
    public function get(string $name): mixed;
    public function set(string $name, mixed $value): void;
    public function unset(string $name, mixed $value): void;
    public function destroy(): void;
}