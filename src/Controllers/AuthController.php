<?php
namespace Koinizate\Controllers;

use Koinizate\Core\Auth;
use Koinizate\Core\Response;

class AuthController {

    public function loginForm(): void {
        if (Auth::check()) Response::redirect('/cursos');
        require __DIR__ . '/../Views/auth/login.php';
    }

    public function login(): void {
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (!$email || !$password) {
            $_SESSION['error'] = 'Completa todos los campos.';
            Response::redirect('/login');
        }

        $result = Auth::attempt($email, $password);

        if (!$result) {
            $_SESSION['error'] = 'Correo o contraseña incorrectos.';
            Response::redirect('/login');
        }

        Response::redirect('/cursos');
    }

    public function registerForm(): void {
        if (Auth::check()) Response::redirect('/cursos');
        require __DIR__ . '/../Views/auth/registro.php';
    }

    public function register(): void {
        $data = [
            'nombre'   => trim($_POST['nombre']   ?? ''),
            'apellido' => trim($_POST['apellido'] ?? ''),
            'email'    => trim($_POST['email']    ?? ''),
            'password' => $_POST['password']      ?? '',
            'edad'     => (int)($_POST['edad']    ?? 0) ?: null,
            'genero'   => $_POST['genero']        ?? null,
            'pais'     => $_POST['pais']          ?? null,
            'motivo'   => trim($_POST['motivo']   ?? ''),
            'idioma'   => $_POST['idioma']        ?? 'es',
        ];

        foreach (['nombre','apellido','email','password'] as $campo) {
            if (!$data[$campo]) {
                $_SESSION['error'] = 'Completa todos los campos obligatorios.';
                Response::redirect('/registro');
            }
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'El correo no es válido.';
            Response::redirect('/registro');
        }

        if (strlen($data['password']) < 8) {
            $_SESSION['error'] = 'La contraseña debe tener al menos 8 caracteres.';
            Response::redirect('/registro');
        }

        $result = Auth::register($data);

        if ($result === 'email_en_uso') {
            $_SESSION['error'] = 'Ese correo ya está registrado.';
            Response::redirect('/registro');
        }

        Response::redirect('/cursos');
    }

    public function logout(): void {
        Auth::logout();
        Response::redirect('/');
    }
}
