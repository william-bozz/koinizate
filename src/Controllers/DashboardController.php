<?php
namespace Koinizate\Controllers;

use Koinizate\Core\Auth;

class DashboardController {
    public function index(): void {
        Auth::require();
        $user = Auth::user();
        require __DIR__ . '/../Views/dashboard.php';
    }
}
