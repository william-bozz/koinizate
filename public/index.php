<?php
declare(strict_types=1);

require_once __DIR__ . '/../src/Core/Autoloader.php';

use Koinizate\Core\Autoloader;
use Koinizate\Core\Env;
use Koinizate\Core\Router;

Autoloader::register(__DIR__ . '/../src');
Env::load(__DIR__ . '/../.env');

session_start();

$router = new Router();
require __DIR__ . '/../src/routes.php';

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
