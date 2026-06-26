<?php
namespace Koinizate\Controllers;

use Koinizate\Core\Auth;
use Koinizate\Core\Database;

class DashboardController {
    public function index(): void {
        Auth::require();
        $db   = Database::get();
        $user = Auth::user();

        // XP y nivel
        $stmt = $db->prepare('SELECT * FROM experiencia WHERE usuario_id = ?');
        $stmt->execute([$user['id']]);
        $exp = $stmt->fetch();

        // Racha y óbolos
        $stmt = $db->prepare('SELECT * FROM rachas WHERE usuario_id = ?');
        $stmt->execute([$user['id']]);
        $racha = $stmt->fetch();

        $stmt = $db->prepare('SELECT cantidad FROM obolos WHERE usuario_id = ?');
        $stmt->execute([$user['id']]);
        $obolos = $stmt->fetchColumn() ?: 0;

        // Logros del usuario
        $stmt = $db->prepare('
            SELECT l.*, lu.obtenido_en
            FROM logros_usuario lu
            JOIN logros l ON l.id = lu.logro_id
            WHERE lu.usuario_id = ?
            ORDER BY lu.obtenido_en DESC
        ');
        $stmt->execute([$user['id']]);
        $logros_obtenidos = $stmt->fetchAll();

        // Todos los logros para mostrar bloqueados
        $todos_logros = $db->query('SELECT * FROM logros ORDER BY xp_bonus ASC')->fetchAll();

        // Progreso de capítulos
        $stmt = $db->prepare('
            SELECT c.*, p.completado, p.xp_ganado, p.completado_en,
                   cu.slug as curso_slug
            FROM capitulos c
            JOIN cursos cu ON cu.id = c.curso_id
            LEFT JOIN progreso p ON p.capitulo_id = c.id AND p.usuario_id = ?
            WHERE c.publicado = 1
            ORDER BY c.orden
        ');
        $stmt->execute([$user['id']]);
        $capitulos = $stmt->fetchAll();

        // Próximo capítulo a estudiar
        $proximo = null;
        foreach ($capitulos as $cap) {
            if (!$cap['completado']) {
                $proximo = $cap;
                break;
            }
        }

        // Posición en ranking global
        $stmt = $db->query('
            SELECT usuario_id,
                   @pos := @pos + 1 AS posicion
            FROM experiencia, (SELECT @pos := 0) r
            ORDER BY xp_total DESC
        ');
        $posiciones = $stmt->fetchAll();
        $mi_posicion = 0;
        foreach ($posiciones as $p) {
            if ((int)$p['usuario_id'] === (int)$user['id']) {
                $mi_posicion = $p['posicion'];
                break;
            }
        }

        $idioma = $user['idioma'] ?? 'es';

        require __DIR__ . '/../Views/dashboard.php';
    }
}
