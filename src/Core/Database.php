<?php
namespace Koinizate\Core;

use PDO;
use PDOException;

class Database {
    private static ?PDO $instance = null;

    public static function connect(): PDO {
        if (self::$instance !== null) return self::$instance;

        $host = Env::get('DB_HOST', 'localhost');
        $port = Env::get('DB_PORT', '3306');
        $name = Env::get('DB_NAME');
        $user = Env::get('DB_USER');
        $pass = Env::get('DB_PASS');

        $dsn = "mysql:host=$host;port=$port;dbname=$name;charset=utf8mb4";

        try {
            self::$instance = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            http_response_code(500);
            die(json_encode(['error' => 'Error de conexión a la base de datos.']));
        }

        return self::$instance;
    }

    public static function get(): PDO {
        return self::connect();
    }
}
