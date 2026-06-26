<?php
namespace Koinizate\Core;

class Autoloader {
    public static function register(string $baseDir): void {
        spl_autoload_register(function (string $class) use ($baseDir) {
            $prefix = 'Koinizate\\';
            if (!str_starts_with($class, $prefix)) return;

            $relative = str_replace('\\', '/', substr($class, strlen($prefix)));
            $file = $baseDir . '/' . $relative . '.php';

            if (file_exists($file)) require $file;
        });
    }
}
