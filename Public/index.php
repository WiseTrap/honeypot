<?php
namespace WiseTrap;

use WiseTrap\App\Models\UserModel;
use WiseTrap\Core\Application;

define('DS', DIRECTORY_SEPARATOR);
define('SRC_PATH', realpath(dirname(__FILE__)) . DS . '..' . DS);
define('CORE_PATH', SRC_PATH . 'Core' . DS);
define('CONFIG_PATH', SRC_PATH . 'Config' . DS);
define('APP_PATH', SRC_PATH . 'App' . DS);

date_default_timezone_set('Asia/Amman');
require_once CORE_PATH . 'AutoLoader.php';
require_once CONFIG_PATH . 'Helpers.php';
loadEnv(SRC_PATH . '.env');
$config = [
    'userClass' => UserModel::class,
    'db' => [
        'host'      => getenv('DB_HOST'),
        'dbname'    => getenv('DB_NAME'),
        'port'      => getenv('DB_PORT'),
        'user'      => getenv('DB_USER'),
        'password'  => getenv('DB_PASSWORD')
    ]
];
(new Application(SRC_PATH, $config))->run();