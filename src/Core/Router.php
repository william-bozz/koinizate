<?php
namespace Koinizate\Core;

class Router {
    private array $routes = [];
    private string $prefix = '';

    public function get(string $path, callable|array $handler): void {
        $this->add('GET', $path, $handler);
    }

    public function post(string $path, callable|array $handler): void {
        $this->add('POST', $path, $handler);
    }

    public function group(string $prefix, callable $callback): void {
        $prev = $this->prefix;
        $this->prefix = $prev . $prefix;
        $callback($this);
        $this->prefix = $prev;
    }

    private function add(string $method, string $path, callable|array $handler): void {
        $fullPath = $this->prefix . $path;
        $pattern  = preg_replace('#\{([a-z_]+)\}#', '(?P<$1>[^/]+)', $fullPath);
        $this->routes[] = [
            'method'  => $method,
            'pattern' => '#^' . $pattern . '$#',
            'handler' => $handler,
        ];
    }

    public function dispatch(string $method, string $uri): void {
        $uri = strtok($uri, '?');

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) continue;
            if (!preg_match($route['pattern'], $uri, $matches)) continue;

            $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

            if (is_array($route['handler'])) {
                [$class, $action] = $route['handler'];
                (new $class())->$action(...array_values($params));
            } else {
                ($route['handler'])(...array_values($params));
            }
            return;
        }

        http_response_code(404);
        require __DIR__ . '/../Views/404.php';
    }
}
