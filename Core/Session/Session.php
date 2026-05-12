<?php

namespace WiseTrap\Core\Session;

use InvalidArgumentException;
use WiseTrap\Core\Hash;

class Session
{
    public function __construct()
    {
        $cookieLifetime = config('WISE.expiration_timeout');
        $sessionDomain  = config('WISE.session_domain');
        $sessionSecure  = config('WISE.session_secure');
        $sessionPath    = config('WISE.session_save_path');
        $sessionPrefix  = config('WISE.session_prefix');

        session_set_cookie_params([
            'lifetime' => $cookieLifetime,
            'path' => '/',
            'domain' => $sessionDomain,
            'secure' => $sessionSecure,
            'httponly' => true,
            'samesite' => 'Strict'
        ]);

        $handler = new SessionHandler($sessionPath,$sessionPrefix);
        $handler->gc($cookieLifetime);
        session_set_save_handler($handler, true);
        session_name($sessionPrefix);
        if ($sessionPath) {
            session_save_path($sessionPath);
        }
        if (session_id() == '') {
            session_start([
                'cookie_lifetime' => $cookieLifetime
            ]);
        }
    }
    public static function make(string $key, mixed $value = null): string
    {
        self::validateKey($key);
        if (!is_null($value)) {
            $_SESSION[$key] = Hash::encrypt($value);
        }
        return self::get($key);
    }
    public static function get(string $key): string
    {
        self::validateKey($key);
        return isset($_SESSION[$key]) ? Hash::decrypt($_SESSION[$key]) : '';
    }
    public static function has(string $key): bool
    {
        self::validateKey($key);
        return isset($_SESSION[$key]);
    }
    public static function flash(string $key, mixed $value = null): string
    {
        self::validateKey($key);
        if (!is_null($value)) {
            $_SESSION[$key] = Hash::encrypt($value);
        }
        $session = self::get($key);
        self::forget($key);
        return $session;
    }
    public static function forget(string $key): void
    {
        self::validateKey($key);
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }
    public static function forget_all(): void
    {
        if (session_id() != '') {
            session_destroy();
        }
    }
    private static function validateKey(string $key): void
    {
        if (empty($key)) {
            throw new InvalidArgumentException("Invalid session key.");
        }
    }
}