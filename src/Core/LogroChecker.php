<?php
namespace Koinizate\Core;

use PDO;

class LogroChecker {

    public static function verificar(int $userId): array {
        $db = Database::get();
        $desbloqueados = [];

        // Datos del usuario
        $exp = $db->prepare('SELECT * FROM experiencia WHERE usuario_id = ?');
        $exp->execute([$userId]);
        $exp = $exp->fetch();

        $racha = $db->prepare('SELECT * FROM rachas WHERE usuario_id = ?');
        $racha->execute([$userId]);
        $racha = $racha->fetch();

        $caps_completados = $db->prepare('
            SELECT COUNT(*) FROM progreso
            WHERE usuario_id = ? AND completado = 1
        ');
        $caps_completados->execute([$userId]);
        $caps_completados = (int)$caps_completados->fetchColumn();

        // Logros ya obtenidos
        $stmt = $db->prepare('SELECT logro_id FROM logros_usuario WHERE usuario_id = ?');
        $stmt->execute([$userId]);
        $ya_tiene = array_column($stmt->fetchAll(), 'logro_id');

        // Todos los logros
        $todos = $db->query('SELECT * FROM logros')->fetchAll();

        foreach ($todos as $logro) {
            if (in_array($logro['id'], $ya_tiene)) continue;

            $cumple = false;

            switch ($logro['condicion_tipo']) {
                case 'capitulos_completados':
                    $cumple = $caps_completados >= (int)$logro['condicion_valor'];
                    break;
                case 'racha':
                    $cumple = ($racha['racha_actual'] ?? 0) >= (int)$logro['condicion_valor'] ||
                              ($racha['racha_maxima'] ?? 0) >= (int)$logro['condicion_valor'];
                    break;
                case 'leccion_perfecta':
                    // Verificar si alguna lección fue completada con 0 errores
                    $stmt2 = $db->prepare('
                        SELECT COUNT(*) FROM progreso
                        WHERE usuario_id = ? AND completado = 1
                        AND xp_ganado >= 50
                    ');
                    $stmt2->execute([$userId]);
                    $cumple = (int)$stmt2->fetchColumn() >= (int)$logro['condicion_valor'];
                    break;
                case 'hora_estudio':
                    $hora = (int)date('H');
                    $cumple = $hora <= (int)$logro['condicion_valor'];
                    break;
            }

            if ($cumple) {
                // Desbloquear logro
                $db->prepare('
                    INSERT IGNORE INTO logros_usuario (usuario_id, logro_id)
                    VALUES (?, ?)
                ')->execute([$userId, $logro['id']]);

                // Dar bonus XP y óbolos
                if ($logro['xp_bonus'] > 0) {
                    $db->prepare('
                        UPDATE experiencia SET xp_total = xp_total + ?
                        WHERE usuario_id = ?
                    ')->execute([$logro['xp_bonus'], $userId]);
                }

                if ($logro['obolos_bonus'] > 0) {
                    $db->prepare('
                        UPDATE obolos SET cantidad = cantidad + ?
                        WHERE usuario_id = ?
                    ')->execute([$logro['obolos_bonus'], $userId]);

                    $db->prepare('
                        INSERT INTO obolos_historial (usuario_id, cantidad, motivo)
                        VALUES (?, ?, ?)
                    ')->execute([$userId, $logro['obolos_bonus'], 'logro_' . $logro['codigo']]);
                }

                $desbloqueados[] = $logro;
            }
        }

        return $desbloqueados;
    }
}
