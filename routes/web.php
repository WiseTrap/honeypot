<?php

use WiseTrap\App\Controllers\AttackersController;
use WiseTrap\App\Controllers\AuthController;
use WiseTrap\App\Controllers\CustomersController;
use WiseTrap\App\Controllers\DashController;
use WiseTrap\App\Controllers\PriceOffersController;
use WiseTrap\Core\Middleware\AdminMiddleware;
use WiseTrap\Core\Middleware\GuestMiddleware;
use WiseTrap\Core\Router;

Router::group(['prefix' => '/auth'], function () {
    Router::get('/', [AuthController::class, 'index'], [GuestMiddleware::class]);
    Router::post('/', [AuthController::class, 'index'], [GuestMiddleware::class]);
});
Router::get('/logout', [AuthController::class, 'logout'], [GuestMiddleware::class]);
Router::get('/', [DashController::class, 'index'], [AdminMiddleware::class]);
Router::group(['prefix' => '/dashboard'], function () {
    Router::get('/', [DashController::class, 'index'], [AdminMiddleware::class]);
});
Router::get('/about', [DashController::class, 'about'], [AdminMiddleware::class]);
Router::get('/contact', [DashController::class, 'contact'], [AdminMiddleware::class]);
Router::group(['prefix' => '/settings'], function () {
    Router::get('/', [DashController::class, 'settings'], [AdminMiddleware::class]);
    Router::get('/check-updates', [DashController::class, 'checkUpdates'], [AdminMiddleware::class]);
    Router::post('/install-update', [DashController::class, 'installUpdate'], [AdminMiddleware::class]);
});
Router::get('/profile', [DashController::class, 'profile'], [AdminMiddleware::class]);
Router::group(['prefix' => '/customers'], function () {
    Router::get('/', [CustomersController::class, 'index'], [AdminMiddleware::class]);
});
Router::group(['prefix' => '/price_offers'], function () {
    Router::get('/', [PriceOffersController::class, 'index'], [AdminMiddleware::class]);
});
Router::group(['prefix' => '/attackers'], function () {
    Router::get('/', [AttackersController::class, 'index'], [AdminMiddleware::class]);
});
Router::get('/login.txt', function () {header('Content-Type: text/plain');readfile(APP_PATH . '../Public/login.txt');});