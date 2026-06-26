<?php
namespace Koinizate\Controllers;

use Koinizate\Core\Auth;
use Koinizate\Core\Database;

class CursoController {

    public function index(): void {
        Auth::require();
        $db     = Database::get();
        $user   = Auth::user();
        $idioma = $user['idioma'] ?? 'es';

        $cursos = $db->query('SELECT * FROM cursos ORDER BY orden')->fetchAll();

        require __DIR__ . '/../Views/cursos/index.php';
    }

    public function lecciones(string $slug): void {
        Auth::require();
        $db     = Database::get();
        $user   = Auth::user();
        $idioma = $user['idioma'] ?? 'es';

        $stmt = $db->prepare('SELECT * FROM cursos WHERE slug = ? LIMIT 1');
        $stmt->execute([$slug]);
        $curso = $stmt->fetch();

        if (!$curso) {
            http_response_code(404);
            require __DIR__ . '/../Views/404.php';
            return;
        }

        $capitulos = $db->prepare('
            SELECT c.*,
                   p.completado,
                   p.xp_ganado
            FROM capitulos c
            LEFT JOIN progreso p ON p.capitulo_id = c.id AND p.usuario_id = ?
            WHERE c.curso_id = ? AND c.publicado = 1
            ORDER BY c.orden
        ');
        $capitulos->execute([$user['id'], $curso['id']]);
        $capitulos = $capitulos->fetchAll();

        require __DIR__ . '/../Views/lecciones/index.php';
    }
}
