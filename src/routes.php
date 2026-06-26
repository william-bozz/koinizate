<?php
use Koinizate\Core\Router;
use Koinizate\Controllers\AuthController;
use Koinizate\Controllers\HomeController;
use Koinizate\Controllers\DashboardController;
use Koinizate\Controllers\CursoController;
use Koinizate\Controllers\LeccionController;

/** @var Router $router */

$router->get('/',                    [HomeController::class,      'index']);
$router->get('/login',               [AuthController::class,      'loginForm']);
$router->post('/login',              [AuthController::class,      'login']);
$router->get('/registro',            [AuthController::class,      'registerForm']);
$router->post('/registro',           [AuthController::class,      'register']);
$router->get('/logout',              [AuthController::class,      'logout']);
$router->get('/dashboard',           [DashboardController::class, 'index']);
$router->get('/cursos',              [CursoController::class,     'index']);
$router->get('/curso/{slug}',        [CursoController::class,     'lecciones']);
$router->get('/leccion/{slug}',      [LeccionController::class,   'leer']);
$router->post('/leccion/marcar-leido', [LeccionController::class, 'marcarLeido']);
