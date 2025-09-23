<?php declare(strict_types=1);

namespace f7k\Sources\interfaces;

interface CookieInterface
{
    public function set(string $key, mixed $value): void;

    public function get(string $key): mixed;

    public function destroy(): void;
}