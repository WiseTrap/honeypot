<?php

namespace WiseTrap\Core;

use LogicException;

class AutoLoader
{
    public static function register(): void
    {
        spl_autoload_register(array(__CLASS__, 'load'));
    }
    public static function load(string $className): void
    {
        $className = str_replace('WiseTrap\\', '' , $className);
        $className = str_replace('\\', DIRECTORY_SEPARATOR, $className);
        $filePath  = '..'. DS . $className . '.php';
        if (is_readable($filePath)){
            require_once $filePath;
        } else {
            throw new LogicException("Unable to load file: $filePath");
        }
    }
}
AutoLoader::register();