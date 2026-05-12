<?php

namespace WiseTrap\Core\Middleware;

use LogicException;
use WiseTrap\Core\Request;

class Middleware
{
    private static array $middlewareWebRoute = [
        'guest' => GuestMiddleware::class,
        'admin' => AdminMiddleware::class
    ];
    private static array $middlewareApiRoute = [
        'guest' => GuestMiddleware::class,
    ];
    public static function handleMiddleware($middlewareStack, $next)
    {
        foreach (array_reverse($middlewareStack) as $middlewares){
            $next = function ($request) use ($middlewares, $next){
                $role = explode(',', $middlewares);
                $middleware = array_shift($role);
                if (!class_exists($middleware)){
                    $middleware = self::getMiddleware($middleware);
                }
                return (new $middleware)->handle($request, $next, ...$role);
            };
        }
        return $next;
    }
    public static function getMiddleware(string $key): string
    {
        $type = Request::segments(0) === 'api'?'api':'web';
        if ($type == 'web' && isset(self::$middlewareWebRoute[$key])) {
            return self::$middlewareWebRoute[$key];
        } elseif ($type == 'api' && isset(self::$middlewareApiRoute[$key])) {
            return self::$middlewareApiRoute[$key];
        } else {
            throw new LogicException('This Middleware (' . $key . ') Not Found ',500);
        }
    }
}