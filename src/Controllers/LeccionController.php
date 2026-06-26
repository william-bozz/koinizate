<?php
namespace Koinizate\Controllers;

use Koinizate\Core\Auth;
use Koinizate\Core\Database;
use Koinizate\Core\Response;
use Koinizate\Core\TextParser;

class LeccionController {

    public function leer(string $slug): void {
        Auth::require();
        $db   = Database::get();
        $user = Auth::user();

        $stmt = $db->prepare('SELECT c.*, cu.slug as curso_slug FROM capitulos c JOIN cursos cu ON cu.id = c.curso_id WHERE c.slug = ? AND c.publicado = 1 LIMIT 1');
        $stmt->execute([$slug]);
        $capitulo = $stmt->fetch();

        if (!$capitulo) {
            http_response_code(404);
            require __DIR__ . '/../Views/404.php';
            return;
        }

        if ($capitulo['es_premium'] && $user['plan'] === 'free') {
            Response::redirect('/cursos');
        }

        $stmt = $db->prepare('SELECT * FROM escenas WHERE capitulo_id = ? ORDER BY numero');
        $stmt->execute([$capitulo['id']]);
        $escenas = $stmt->fetchAll();

        $palabras = $db->query('SELECT * FROM palabras')->fetchAll();
        $lexico   = [];
        foreach ($palabras as $p) {
            $lexico[$p['forma_griega']] = $p;
        }

        // Registrar inicio sin dar XP
        $db->prepare('
            INSERT INTO progreso (usuario_id, capitulo_id)
            VALUES (?, ?)
            ON DUPLICATE KEY UPDATE intentos = intentos
        ')->execute([$user['id'], $capitulo['id']]);

        $idioma = $user['idioma'] ?? 'es';

        require __DIR__ . '/../Views/lecciones/leer.php';
    }

    // La lectura solo marca que el usuario llegó a los ejercicios
    // El XP se otorga exclusivamente en EjercicioController después de completar actividades
    public function marcarLeido(): void {
        Auth::require();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') Response::error('Método no permitido', 405);

        $db         = Database::get();
        $user       = Auth::user();
        $capituloId = (int)($_POST['capitulo_id'] ?? 0);

        if (!$capituloId) Response::error('Capítulo inválido');

        // Solo marca como "texto leído", sin XP — los ejercicios dan el XP
        $db->prepare('
            UPDATE progreso SET lectura_completada = 1
            WHERE usuario_id = ? AND capitulo_id = ?
        ')->execute([$user['id'], $capituloId]);

        // Redirige a ejercicios del capítulo
        $stmt = $db->prepare('SELECT slug FROM capitulos WHERE id = ? LIMIT 1');
        $stmt->execute([$capituloId]);
        $cap = $stmt->fetch();

        Response::ok([
            'redirect' => '/ejercicios/' . ($cap['slug'] ?? '')
        ], 'Lectura completada — ahora los ejercicios');
    }
}
