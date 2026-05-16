<?php

namespace WiseTrap\Core\Middleware;

use WiseTrap\Core\Application;

class AdminMiddleware implements Contract
{
    public function handle($request, $next, ...$params)
    {
        if (Application::$app->user === null || Application::$app->isGuest()) {
            redirect('/auth');
        }
        return $next($request);
    }
}