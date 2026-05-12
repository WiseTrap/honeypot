<?php

namespace WiseTrap\Core;

use WiseTrap\Core\Session\Session;

class Request
{
    public static array $globalWeb = [
        Session::class
    ];
    public static array $globalApi = [];
    public function __construct()
    {
        self::isApiRequest() ? self::apiRoute() : self::webRoute();
    }
    public static function getPath(): string
    {
        $path = filter_var($_SERVER['REQUEST_URI'] ?? '/', FILTER_SANITIZE_URL);
        $path = strtok($path, '?');
        return $path === '/' ? '/' : trim($path, '/');
    }
    public static function getMethod(): string
    {
        return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
    }
    public static function isApiRequest(): bool
    {
        return str_starts_with(self::getPath(), 'api');
    }
    public static function webRoute(): void
    {
        foreach (static::$globalWeb as $global) {
            new $global();
        }
        include Application::$SRC_DIR . DS . 'routes' . DS . 'web.php';
    }
    public static function apiRoute(): void
    {
        foreach (static::$globalApi as $global) {
            new $global();
        }
        include Application::$SRC_DIR . DS . 'routes' . DS . 'api.php';
    }
    public static function segments(int $offset, string $default = ''): string
    {
        static $cachedSegments = null;

        if ($cachedSegments === null) {
            $path = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);
            $path = rawurldecode(trim(parse_url($path, PHP_URL_PATH), '/'));
            $path = preg_replace('#/+#', '/', $path);
            $cachedSegments = explode('/', $path);
        }

        return $cachedSegments[$offset] ?? $default;
    }
}