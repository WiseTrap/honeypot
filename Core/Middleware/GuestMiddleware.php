<?php

namespace WiseTrap\Core\Middleware;

use WiseTrap\Core\Application;

class GuestMiddleware implements Contract
{
    public function handle($request, $next, ...$role)
    {
        if ($request !== 'auth' && (Application::$app->user === null || Application::$app->isGuest())) {
            redirect('/auth');
        }
        if ($request === 'auth' && Application::$app->user !== null && !Application::$app->isGuest()) {
            redirect('/dashboard');
        }
        return $next($request);
    }
}