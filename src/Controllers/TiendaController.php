<?php
namespace Koinizate\Controllers;

use Koinizate\Core\Auth;
use Koinizate\Core\Database;
use Koinizate\Core\Response;

class TiendaController {
    public function comprar(): void {
        Auth::require();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') Response::error('Método no permitido', 405);

        $input  = json_decode(file_get_contents('php://input'), true);
        $tipo   = $input['tipo']   ?? '';
        $precio = (int)($input['precio'] ?? 0);

        $db   = Database::get();
        $user = Auth::user();

        // Verificar óbolos
        $stmt = $db->prepare('SELECT cantidad FROM obolos WHERE usuario_id = ?');
        $stmt->execute([$user['id']]);
        $obolos = (int)$stmt->fetchColumn();

        if ($obolos < $precio) {
            Response::error('Óbolos insuficientes');
        }

        // Aplicar compra
        switch ($tipo) {
            case 'escudo':
                $db->prepare('UPDATE rachas SET escudos = escudos + 1 WHERE usuario_id = ?')
                   ->execute([$user['id']]);
                $motivo = 'compra_escudo';
                break;
            case 'corazon':
                // Los corazones extra se almacenan en sesión PHP para la próxima sesión de ejercicios
                $_SESSION['corazones_extra'] = ($_SESSION['corazones_extra'] ?? 0) + 1;
                $motivo = 'compra_corazon';
                break;
            default:
                Response::error('Producto inválido');
        }

        // Descontar óbolos
        $db->prepare('UPDATE obolos SET cantidad = cantidad - ? WHERE usuario_id = ?')
           ->execute([$precio, $user['id']]);

        $db->prepare('INSERT INTO obolos_historial (usuario_id, cantidad, motivo) VALUES (?, ?, ?)')
           ->execute([$user['id'], -$precio, $motivo]);

        Response::ok(['obolos' => $obolos - $precio], 'Compra exitosa');
    }
}
