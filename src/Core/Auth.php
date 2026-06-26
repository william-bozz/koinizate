<?php
namespace Koinizate\Core;

use PDO;

class Auth {
    private static ?array $currentUser = null;

    public static function attempt(string $email, string $password): array|false {
        $db   = Database::get();
        $stmt = $db->prepare('SELECT * FROM usuarios WHERE email = ? AND activo = 1 LIMIT 1');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password_hash'])) {
            return false;
        }

        return self::createSession($user);
    }

    public static function createSession(array $user): array {
        $db    = Database::get();
        $token = bin2hex(random_bytes(32));
        $expira = date('Y-m-d H:i:s', strtotime('+30 days'));

        $db->prepare('INSERT INTO sesiones (usuario_id, token, expira_en, ip, user_agent) VALUES (?, ?, ?, ?, ?)')
           ->execute([
               $user['id'],
               $token,
               $expira,
               $_SERVER['REMOTE_ADDR'] ?? '',
               $_SERVER['HTTP_USER_AGENT'] ?? '',
           ]);

        $db->prepare('UPDATE usuarios SET ultimo_acceso = NOW() WHERE id = ?')
           ->execute([$user['id']]);

        setcookie('koinizate_token', $token, [
            'expires'  => strtotime('+30 days'),
            'path'     => '/',
            'httponly' => true,
            'samesite' => 'Lax',
            'secure'   => Env::get('APP_ENV') === 'production',
        ]);

        return ['user' => $user, 'token' => $token];
    }

    public static function user(): ?array {
        if (self::$currentUser !== null) return self::$currentUser;

        $token = $_COOKIE['koinizate_token'] ?? $_SERVER['HTTP_AUTHORIZATION'] ?? null;
        if (!$token) return null;

        $token = str_replace('Bearer ', '', $token);
        $db    = Database::get();

        $stmt = $db->prepare('
            SELECT u.* FROM usuarios u
            INNER JOIN sesiones s ON s.usuario_id = u.id
            WHERE s.token = ? AND s.expira_en > NOW() AND u.activo = 1
            LIMIT 1
        ');
        $stmt->execute([$token]);
        $user = $stmt->fetch();

        self::$currentUser = $user ?: null;
        return self::$currentUser;
    }

    public static function check(): bool {
        return self::user() !== null;
    }

    public static function logout(): void {
        $token = $_COOKIE['koinizate_token'] ?? null;
        if ($token) {
            $db = Database::get();
            $db->prepare('DELETE FROM sesiones WHERE token = ?')->execute([$token]);
        }
        setcookie('koinizate_token', '', time() - 3600, '/');
        self::$currentUser = null;
    }

    public static function require(): void {
        if (!self::check()) {
            header('Location: /login');
            exit;
        }
    }

    public static function register(array $data): array|string {
        $db = Database::get();

        $stmt = $db->prepare('SELECT id FROM usuarios WHERE email = ? LIMIT 1');
        $stmt->execute([$data['email']]);
        if ($stmt->fetch()) return 'email_en_uso';

        $token = bin2hex(random_bytes(32));
        $hash  = password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]);

        $stmt = $db->prepare('
            INSERT INTO usuarios
                (nombre, apellido, email, password_hash, edad, genero, pais, motivo, idioma, token_verificacion)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ');
        $stmt->execute([
            $data['nombre'],
            $data['apellido'],
            $data['email'],
            $hash,
            $data['edad']    ?? null,
            $data['genero']  ?? null,
            $data['pais']    ?? null,
            $data['motivo']  ?? null,
            $data['idioma']  ?? 'es',
            $token,
        ]);

        $userId = (int) $db->lastInsertId();
        self::initUserTables($userId);

        $user = $db->query("SELECT * FROM usuarios WHERE id = $userId")->fetch();
        return self::createSession($user);
    }

    public static function initUserTables(int $userId): void {
        $db = Database::get();
        $db->prepare('INSERT IGNORE INTO rachas (usuario_id) VALUES (?)')->execute([$userId]);
        $db->prepare('INSERT IGNORE INTO obolos (usuario_id) VALUES (?)')->execute([$userId]);
        $db->prepare('INSERT IGNORE INTO experiencia (usuario_id, semana_actual, mes_actual, anio_actual) VALUES (?, ?, ?, ?)')->execute([
            $userId,
            (int) date('W'),
            (int) date('n'),
            (int) date('Y'),
        ]);
    }
}
