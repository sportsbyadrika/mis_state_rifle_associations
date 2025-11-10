<?php

namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $uri, callable $action): void
    {
        $this->addRoute('GET', $uri, $action);
    }

    public function post(string $uri, callable $action): void
    {
        $this->addRoute('POST', $uri, $action);
    }

    private function addRoute(string $method, string $uri, callable $action): void
    {
        $this->routes[$method][] = [
            'pattern' => $this->compilePattern($uri),
            'action' => $action,
        ];
    }

    public function dispatch(string $uri, string $method): void
    {
        $path = $this->normalize(parse_url($uri, PHP_URL_PATH));
        $method = strtoupper($method);

        if (!isset($this->routes[$method])) {
            $this->notFound();
            return;
        }

        foreach ($this->routes[$method] as $route) {
            if (preg_match($route['pattern']['regex'], $path, $matches)) {
                array_shift($matches);
                call_user_func_array($route['action'], $matches);
                return;
            }
        }

        $this->notFound();
    }

    private function compilePattern(string $uri): array
    {
        $uri = $this->normalize($uri);
        $pattern = preg_replace('#\{([^/]+)\}#', '([^/]+)', $uri);
        $regex = '#^' . $pattern . '$#';
        return ['uri' => $uri, 'regex' => $regex];
    }

    private function normalize(string $uri): string
    {
        return '/' . trim($uri, '/');
    }

    private function notFound(): void
    {
        http_response_code(404);
        echo '404 Not Found';
    }
}
