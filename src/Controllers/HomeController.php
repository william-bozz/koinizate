<?php
namespace Koinizate\Controllers;

use Koinizate\Core\Auth;
use Koinizate\Core\Response;

class HomeController {
    public function index(): void {
        if (Auth::check()) {
            Response::redirect('/dashboard');
        } else {
            Response::redirect('/login');
        }
    }
}
