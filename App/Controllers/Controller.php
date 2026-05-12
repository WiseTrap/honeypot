<?php

namespace WiseTrap\App\Controllers;

use WiseTrap\Core\View;

abstract class Controller
{
    protected array $layoutParams = [];
    public function setLayoutParam(string $key, $value): void
    {
        $this->layoutParams[$key] = $value;
    }
    public function render(string $view, array $params = []): bool|array|string
    {
        return View::renderView($view, array_merge($this->layoutParams, $params));
    }
}