<?php

use WiseTrap\Core\Router;

Router::group(['prefix' => 'api'], function () {
    Router::get('/', function () {
        return 'API Page';
    });
});