<?php
namespace Koinizate\Controllers;

use Koinizate\Core\Auth;

class HomeController {
    public function index(): void {
        $user = Auth::user();
        require __DIR__ . '/../Views/home.php';
    }
}
