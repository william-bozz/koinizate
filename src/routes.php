<?php
use Koinizate\Core\Router;
use Koinizate\Controllers\AuthController;
use Koinizate\Controllers\HomeController;
use Koinizate\Controllers\DashboardController;

/** @var Router $router */

$router->get('/',          [HomeController::class,      'index']);
$router->get('/login',     [AuthController::class,      'loginForm']);
$router->post('/login',    [AuthController::class,      'login']);
$router->get('/registro',  [AuthController::class,      'registerForm']);
$router->post('/registro', [AuthController::class,      'register']);
$router->get('/logout',    [AuthController::class,      'logout']);
$router->get('/dashboard', [DashboardController::class, 'index']);
