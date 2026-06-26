<?php
namespace Koinizate\Core;

class Env {
    public static function load(string $path): void {
        if (!file_exists($path)) return;
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (str_starts_with(trim($line), '#')) continue;
            [$key, $value] = array_map('trim', explode('=', $line, 2));
            $_ENV[$key] = $value;
            putenv("$key=$value");
        }
    }

    public static function get(string $key, mixed $default = null): mixed {
        return $_ENV[$key] ?? getenv($key) ?: $default;
    }
}
