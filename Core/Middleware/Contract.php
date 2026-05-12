<?php

namespace WiseTrap\Core\Middleware;

interface Contract
{
    public function handle($request, $next,...$role);
}