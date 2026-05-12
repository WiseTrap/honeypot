<?php

namespace WiseTrap\Core;

use LogicException;

class View
{
    public static function renderView(string $view, ?array $params): bool|string
    {
        $viewPath = self::getViewPath($view);
        if (!file_exists($viewPath)) {
            throw new LogicException("View file not found: $view",500);
        }

        if ($params) {
            extract($params, EXTR_SKIP);
        }

        ob_start();
        include $viewPath;
        $viewContent = ob_get_clean();

        $layoutContent = self::layoutContent($params ?? []);
        return str_replace('{{content}}', $viewContent, $layoutContent);
    }
    protected static function getViewPath(string $view): string
    {;
        $view = str_replace('.', DS, $view);
        return APP_PATH . 'Views' . DS . $view . '.tpl.php';
    }
    protected static function layoutContent(array $params): false|string
    {
        extract($params, EXTR_SKIP);
        ob_start();
        include APP_PATH . 'Views' . DS . 'layouts' . DS . 'main.tpl.php';
        return ob_get_clean();
    }
}