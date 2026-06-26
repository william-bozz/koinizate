<?php
namespace Koinizate\Controllers;

use Koinizate\Core\Auth;
use Koinizate\Core\Database;

class RankingController {

    public function index(): void {
        Auth::require();
        $db     = Database::get();
        $user   = Auth::user();
        $idioma = $user['idioma'] ?? 'es';

        $periodo = $_GET['periodo'] ?? 'semana';
        $pais    = $_GET['pais'] ?? '';

        $xp_col = match($periodo) {
            'mes'    => 'e.xp_mes',
            'total'  => 'e.xp_total',
            default  => 'e.xp_semana',
        };

        $where = $pais ? "AND u.pais = " . $db->quote($pais) : '';

        $stmt = $db->prepare("
            SELECT u.id, u.nombre, u.apellido, u.pais, u.avatar_url, u.plan, u.es_falso,
                   e.xp_total, e.xp_semana, e.xp_mes, e.nivel,
                   r.racha_actual, r.escudos,
                   {$xp_col} as xp_periodo
            FROM usuarios u
            JOIN experiencia e ON e.usuario_id = u.id
            JOIN rachas r ON r.usuario_id = u.id
            WHERE u.activo = 1 {$where}
            ORDER BY {$xp_col} DESC
            LIMIT 50
        ");
        $stmt->execute();
        $rows = $stmt->fetchAll();

        // Asignar posiciones en PHP — ordenado correctamente
        $ranking = [];
        foreach ($rows as $i => $row) {
            $row['posicion'] = $i + 1;
            $ranking[] = $row;
        }

        // Posición del usuario actual
        $mi_posicion = 0;
        foreach ($ranking as $r) {
            if ((int)$r['id'] === (int)$user['id']) {
                $mi_posicion = $r['posicion'];
                break;
            }
        }

        // Si el usuario no está en el top 50, buscar su posición real
        if (!$mi_posicion) {
            $col_clean = str_replace("e.", "", $xp_col);
            $stmt2 = $db->prepare("
                SELECT COUNT(*) + 1
                FROM experiencia e2
                JOIN usuarios u2 ON u2.id = e2.usuario_id
                WHERE u2.activo = 1
                AND e2.{$col_clean} > (
                    SELECT {$col_clean} FROM experiencia WHERE usuario_id = ?
                )
            ");
            $stmt2->execute([$user['id']]);
            $mi_posicion = (int)$stmt2->fetchColumn();
        }

        // Lista de países disponibles para filtro
        $paises_stmt = $db->query("
            SELECT DISTINCT u.pais, COUNT(*) as total
            FROM usuarios u
            JOIN experiencia e ON e.usuario_id = u.id
            WHERE u.activo = 1 AND u.pais IS NOT NULL
            GROUP BY u.pais
            ORDER BY total DESC
        ");
        $paises_disponibles = $paises_stmt->fetchAll();

        require __DIR__ . '/../Views/ranking/index.php';
    }
}
