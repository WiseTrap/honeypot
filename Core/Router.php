<?php

namespace WiseTrap\Core;

use LogicException;
use WiseTrap\Core\Middleware\Middleware;
use WiseTrap\routes\Methods;

class Router
{
    use Methods;
    protected Request $request;
    protected Response $response;
    protected static array $routes = [];
    protected static array $groupAttributes = [];
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }
    public function dispatch()
    {
        $path   = $this->request->getPath();
        $method = $this->request->getMethod();
        foreach (self::$routes as $route) {
            $pattern = $this->compilePattern($route['uri']);
            if ($route['method'] === $method && preg_match($pattern, $path, $matches)){
                $params     = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                $handler    = $route['handler'];
                $middleware = $route['middleware'];
                $next = function () use ($handler, $params) {
                    return $this->invokeHandler($handler, $params);
                };
                $next = Middleware::handleMiddleware($middleware, $next);
                return $next($path);
            }
        }
        throw new LogicException("Page Not Found",404);
    }
    private function compilePattern($uri): string
    {
        $pattern = preg_replace('/{([a-zA-Z0-9_]+)}/', '(?P<$1>[a-zA-Z0-9_]+)', $uri);
        return "#^$pattern$#";
    }
    private function invokeHandler($handler, $params)
    {
        if (is_callable($handler)){
            return call_user_func($handler, ...$params);
        }

        if (is_array($handler)) {
            [$controller, $action] = $handler;
            if (class_exists($controller) && method_exists($controller, $action)) {
                return call_user_func_array([new $controller, $action], $params);
            }
            throw new LogicException("Controller '$controller' or action '$action' not found.", 500);
        }

        throw new LogicException("Invalid route handler.", 500);
    }
    public static function add(string $method, string $route, callable|array $handler, array $middleware = []): void
    {
        $route      = self::applyGroupPrefix($route);
        $middleware = array_merge(self::getGroupMiddleware(), $middleware);

        self::$routes[] = [
            'method'    => $method,
            'uri'       => $route === '/' ? $route : ltrim($route, '/'),
            'handler'   => $handler,
            'middleware'=> $middleware
        ];
    }
    private static function applyGroupPrefix(string $route): string
    {
        if (isset(self::$groupAttributes['prefix'])) {
            $fullRoute = rtrim(self::$groupAttributes['prefix'], '/') . '/' . ltrim($route, '/');
            return rtrim($fullRoute, '/');
        }
        return $route;
    }
    private static function getGroupMiddleware(): array
    {
        return static::$groupAttributes['middleware'] ?? [];
    }
    public static function group($attributes, $callback): void
    {
        $previousGroupAttribute  = self::$groupAttributes;
        self::$groupAttributes = array_merge(self::$groupAttributes, $attributes);
        call_user_func($callback);
        self::$groupAttributes = $previousGroupAttribute;
    }
}