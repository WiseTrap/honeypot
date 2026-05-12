<?php

namespace WiseTrap\Core;

use JsonException;
use LogicException;

class Response
{
    public function setStatusCode(int $code): void
    {
        if ($code < 100 || $code > 599) {
            throw new LogicException("Invalid HTTP status code: $code");
        }
        http_response_code($code);
    }
    public function json(mixed $data): never
    {
        header_remove();
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Pragma: no-cache');
        header('X-Content-Type-Options: nosniff');
        header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
        try {
            $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            error_log('JSON encode failed: ' . $e->getMessage());
            $this->setStatusCode(500);
            echo json_encode(['error' => 'Internal Server Error'], JSON_UNESCAPED_UNICODE);
            exit();
        }
        echo $json;
        exit();
    }
    public static function redirect(string $url = '/', int $statusCode = 302): never
    {
        if (!preg_match('#^/[-\w._~/]*(\?.*)?$#', $url)) {
            throw new LogicException('Invalid or unsafe URL provided for redirection.');
        }
        header_remove();
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Pragma: no-cache');
        header('X-Content-Type-Options: nosniff');
        header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
        header('Location: ' . $url, true, $statusCode);
        exit();
    }
}