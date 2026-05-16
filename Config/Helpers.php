<?php

use WiseTrap\Core\Application;
use WiseTrap\Core\Request;
use WiseTrap\Core\Response;

if (!function_exists('redirect')) {
    function redirect($url = '/'): void
    {
        Response::redirect($url);
    }
}
if (!function_exists('hasRole')) {
    function hasRole(string|array $roles): bool
    {
        if (!Application::$app->user) {
            return false;
        }
        $rolesMap = [
            'admin'         => 1,
            'loginTrap'     => 2,
            'SQLInjection'  => 3,
        ];
        $userRole = Application::$app->user->GroupId ?? null;
        if (is_array($roles)) {
            $roles = array_map(function ($role) use ($rolesMap) {
                return $rolesMap[$role] ?? null;
            }, $roles);

            return in_array($userRole, $roles);
        }
        if (isset($rolesMap[$roles])) {
            $roles = $rolesMap[$roles];
            return $userRole === $roles;
        }
        return false;
    }
}
if (!function_exists('config')) {
    function config(?string $file = null)
    {
        $separate = explode('.', $file);
        if ((!empty($separate) && count($separate) > 1) && !is_null($file)) {
            $file = include SRC_PATH . 'Config' . DS . $separate[0] . '.php';
            return $file[$separate[1]] ?? $file;
        }
        return $file;
    }
}
if (!function_exists('loadEnv')) {
    function loadEnv(string $path): void
    {
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (str_starts_with(trim($line), '#')) {
                continue;
            }
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            putenv("$key=$value");
        }
    }
}
if (!function_exists('request')) {
    function request(string $method = 'GET', ?string $name = null, mixed $default = null, bool $sanitize = true): mixed
    {
        $method = strtoupper($method);
        $validMethods = ['GET', 'POST', 'FILES', 'COOKIE', 'SESSION'];
        if (!in_array($method, $validMethods)) {
            return $default;
        }
        $sources = [
            'GET'     => $_GET,
            'POST'    => $_POST,
            'FILES'   => $_FILES,
            'COOKIE'  => $_COOKIE,
            'SESSION' => $_SESSION ?? [],
        ];
        $data = $sources[$method];
        if ($name !== null) {
            $value = $data[$name] ?? $default;
            if ($sanitize && is_string($value)) {
                return filter_var($value, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            }
            return $value;
        }
        return $data;
    }
}
if (!function_exists('url')) {
    function url(string $url = ''): string
    {
        $url = trim($url);
        if (preg_match('/^(javascript|data|vbscript):/i', $url)) {
            throw new LogicException("Unsafe URL scheme detected");
        }
        $url = filter_var($url, FILTER_SANITIZE_URL);
        $url = preg_replace('/[^a-zA-Z0-9\-\/_.]/', '', $url);
        $base = rtrim(config('WISE.base_url'), '/');
        $url  = ltrim($url, '/');
        return $base . '/' . $url;
    }
}
if (!function_exists('e')) {
    function e($value): string {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}
if (!function_exists('svg')) {
    function svg(string $symbol, string $width = "16", string $height = "16", string $class = "mx-1"): string
    {
        return '
        <svg width="' . e($width) . '" height="' . e($height) . '" class="' . e($class) . '">
            <use href="' . e(url('assets/img/') . 'icons.svg#' . e($symbol)) . '"></use>
        </svg>
    ';
    }
}
if (!function_exists('asset')) {
    function asset(string $path = ''): string
    {
        $path = trim($path);
        if (preg_match('/^(javascript|data|vbscript):/i', $path)) {
            throw new LogicException("Unsafe asset path detected");
        }
        $path       = filter_var($path, FILTER_SANITIZE_URL);
        $path       = preg_replace('/[^a-zA-Z0-9\-\/_.]/', '', $path);
        $fullPath   = rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/' . ltrim($path, '/');
        $version    = file_exists($fullPath) ? filemtime($fullPath) : time();
        return url($path) . '?v=' . $version;
    }
}
if (!function_exists('isActive')) {
    function isActive(string $page, int $offset = 0): string
    {
        $currentRoute = trim(Request::segments($offset), '/');
        $page = trim($page, '/');
        return $currentRoute === $page ? 'active' : '';
    }
}