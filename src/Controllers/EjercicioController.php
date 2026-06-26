<?php
namespace Koinizate\Controllers;

use Koinizate\Core\Auth;
use Koinizate\Core\Database;
use Koinizate\Core\Response;
use Koinizate\Core\LogroChecker;

class EjercicioController {

    public function index(string $slug): void {
        Auth::require();
        $db   = Database::get();
        $user = Auth::user();

        $stmt = $db->prepare('SELECT * FROM capitulos WHERE slug = ? AND publicado = 1 LIMIT 1');
        $stmt->execute([$slug]);
        $capitulo = $stmt->fetch();

        if (!$capitulo) {
            http_response_code(404);
            require __DIR__ . '/../Views/404.php';
            return;
        }

        $stmt = $db->prepare('SELECT * FROM ejercicios WHERE capitulo_id = ? ORDER BY orden');
        $stmt->execute([$capitulo['id']]);
        $ejercicios = $stmt->fetchAll();

        // Decodificar JSON de cada ejercicio
        foreach ($ejercicios as &$ej) {
            $ej['contenido'] = json_decode($ej['contenido_json'], true);
        }
        unset($ej);

        $idioma = $user['idioma'] ?? 'es';

        // Obtener posición actual en ranking para mostrar en resumen
        $stmt = $db->prepare('
            SELECT u.id, u.nombre, u.apellido, u.pais, u.avatar_url, e.xp_total, e.nivel
            FROM experiencia e
            JOIN usuarios u ON u.id = e.usuario_id
            WHERE u.activo = 1
            ORDER BY e.xp_total DESC
        ');
        $stmt->execute();
        $ranking = $stmt->fetchAll();

        $mi_posicion = 0;
        $usuario_arriba = null;
        $usuario_abajo  = null;
        foreach ($ranking as $i => $r) {
            if ($r['id'] == $user['id']) {
                $mi_posicion    = $i + 1;
                $usuario_arriba = $i > 0 ? $ranking[$i - 1] : null;
                $usuario_abajo  = isset($ranking[$i + 1]) ? $ranking[$i + 1] : null;
                break;
            }
        }

        require __DIR__ . '/../Views/ejercicios/index.php';
    }

    public function verificar(): void {
        Auth::require();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') Response::error('Método no permitido', 405);

        $input = json_decode(file_get_contents('php://input'), true);
        $tipo  = $input['tipo'] ?? '';
        $resp  = $input['respuesta'] ?? '';
        $ej_id = (int)($input['ejercicio_id'] ?? 0);
        $preg_id = (int)($input['pregunta_id'] ?? 0);

        $db   = Database::get();
        $stmt = $db->prepare('SELECT * FROM ejercicios WHERE id = ? LIMIT 1');
        $stmt->execute([$ej_id]);
        $ej = $stmt->fetch();

        if (!$ej) Response::error('Ejercicio no encontrado', 404);

        $contenido = json_decode($ej['contenido_json'], true);
        $correcto  = false;

        switch ($tipo) {

            case 'escritura':
                $preguntas = $contenido['preguntas'] ?? [];
                $respuesta_modelo = '';
                $parcial = false;
                $parciales = [];
                foreach ($preguntas as $p) {
                    if ((int)$p['id'] !== $preg_id) continue;
                    $claves = $p['palabras_clave'] ?? [];
                    $resultado = $this->verificarEscrituraFlexible($resp, $claves);
                    $correcto  = $resultado['correcto'];
                    $parcial   = $resultado['parcial'];
                    $parciales = $resultado['parciales'];
                    $respuesta_modelo = $p['respuesta_modelo'] ?? '';
                    break;
                }
                Response::ok([
                    'correcto'         => $correcto,
                    'parcial'          => $parcial,
                    'parciales'        => $parciales,
                    'respuesta_modelo' => $respuesta_modelo,
                ]);
                return;

            case 'seleccion':
                $preguntas = $contenido['preguntas'] ?? [];
                foreach ($preguntas as $p) {
                    if ((int)$p['id'] !== $preg_id) continue;
                    $correcto = strtolower(trim($resp)) === strtolower(trim($p['respuesta']));
                    break;
                }
                break;

            case 'arrastrar':
                $preguntas = $contenido['preguntas'] ?? [];
                foreach ($preguntas as $p) {
                    if ((int)$p['id'] !== $preg_id) continue;
                    $correcto = trim($resp) === trim($p['respuesta']);
                    break;
                }
                break;

            case 'relacionar':
                // Para relacionar se envía array de pares completos al final
                $pares_usuario = $input['pares'] ?? [];
                $pares_correctos = $contenido['pares'] ?? [];
                $correctos = 0;
                foreach ($pares_correctos as $par) {
                    foreach ($pares_usuario as $pu) {
                        if ($pu['izquierda'] === $par['izquierda'] && $pu['derecha'] === $par['derecha']) {
                            $correctos++;
                            break;
                        }
                    }
                }
                $correcto = $correctos === count($pares_correctos);
                Response::ok(['correcto' => $correcto, 'correctos' => $correctos, 'total' => count($pares_correctos)]);
                return;
        }

        Response::ok(['correcto' => $correcto]);
    }

    public function completar(): void {
        Auth::require();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') Response::error('Método no permitido', 405);

        $input      = json_decode(file_get_contents('php://input'), true);
        $capId      = (int)($input['capitulo_id'] ?? 0);
        $puntos     = (int)($input['puntos'] ?? 0);
        $total      = (int)($input['total'] ?? 1);
        $palabras   = (int)($input['palabras_nuevas'] ?? 0);

        if (!$capId) Response::error('Capítulo inválido');

        $db   = Database::get();
        $user = Auth::user();

        // XP proporcional a puntos obtenidos
        $xp     = max(0, $puntos * 5);
        $obolos = max(0, (int)($puntos * 2));

        // Actualizar progreso
        $db->prepare('
            UPDATE progreso
            SET completado = 1, xp_ganado = ?, completado_en = NOW(), intentos = intentos + 1
            WHERE usuario_id = ? AND capitulo_id = ?
        ')->execute([$xp, $user['id'], $capId]);

        if ($xp > 0) {
            $semana = (int)date('W');
            $mes    = (int)date('n');
            $anio   = (int)date('Y');

            $db->prepare('
                UPDATE experiencia
                SET xp_total   = xp_total + ?,
                    xp_semana  = IF(semana_actual = ? AND anio_actual = ?, xp_semana + ?, ?),
                    xp_mes     = IF(mes_actual = ? AND anio_actual = ?, xp_mes + ?, ?),
                    semana_actual = ?, mes_actual = ?, anio_actual = ?,
                    nivel = GREATEST(1, FLOOR(1 + SQRT(xp_total + ?)/10))
                WHERE usuario_id = ?
            ')->execute([
                $xp,
                $semana, $anio, $xp, $xp,
                $mes,    $anio, $xp, $xp,
                $semana, $mes, $anio,
                $xp, $user['id']
            ]);

            $db->prepare('UPDATE obolos SET cantidad = cantidad + ? WHERE usuario_id = ?')
               ->execute([$obolos, $user['id']]);

            $db->prepare('INSERT INTO obolos_historial (usuario_id, cantidad, motivo) VALUES (?,?,?)')
               ->execute([$user['id'], $obolos, 'ejercicios_capitulo_' . $capId]);
        }

        // Actualizar racha
        $this->actualizarRacha($user['id'], $db);

        // Obtener datos actualizados para el resumen
        $exp = $db->prepare('SELECT * FROM experiencia WHERE usuario_id = ?');
        $exp->execute([$user['id']]);
        $exp = $exp->fetch();

        $racha = $db->prepare('SELECT racha_actual FROM rachas WHERE usuario_id = ?');
        $racha->execute([$user['id']]);
        $racha = $racha->fetch();

        // Posición en ranking
        $stmt = $db->query('
            SELECT u.id, u.nombre, u.apellido, u.pais, u.avatar_url, e.xp_total, e.nivel,
                   r.racha_actual,
                   @rownum := @rownum + 1 AS posicion
            FROM experiencia e
            JOIN usuarios u ON u.id = e.usuario_id
            JOIN (SELECT @rownum := 0) rn
            LEFT JOIN rachas r ON r.usuario_id = u.id
            WHERE u.activo = 1
            ORDER BY e.xp_total DESC
        ');
        $ranking_full = $stmt->fetchAll();

        $mi_pos = 0;
        $arriba = null;
        $abajo  = null;
        foreach ($ranking_full as $i => $r) {
            if ((int)$r['id'] === (int)$user['id']) {
                $mi_pos = $i + 1;
                $arriba = $i > 0 ? $ranking_full[$i-1] : null;
                $abajo  = isset($ranking_full[$i+1]) ? $ranking_full[$i+1] : null;
                break;
            }
        }

        // Verificar y desbloquear logros
        $logros_nuevos = LogroChecker::verificar($user['id']);

        Response::ok([
            'xp'           => $xp,
            'obolos'       => $obolos,
            'nivel'        => $exp['nivel'] ?? 1,
            'xp_total'     => $exp['xp_total'] ?? 0,
            'racha'        => $racha['racha_actual'] ?? 0,
            'palabras'     => $palabras,
            'posicion'     => $mi_pos,
            'arriba'       => $arriba,
            'abajo'        => $abajo,
            'puntos'       => $puntos,
            'total'        => $total,
            'logros_nuevos'=> $logros_nuevos,
        ]);
    }

    /**
     * Verifica escritura griega con flexibilidad:
     * - Ignora diacríticos completamente
     * - Acepta variantes de caso (σπαρτα = σπαρτη) comparando raíz truncada
     * - Retorna: ["correcto"=>bool, "parcial"=>bool, "faltantes"=>array]
     */
    public function verificarEscrituraFlexible(string $respuesta, array $claves): array {
        $normalizado = $this->normalizarGriego($respuesta);
        $tokens      = preg_split("/\s+/u", $normalizado, -1, PREG_SPLIT_NO_EMPTY);

        $faltantes = [];
        $parciales = [];

        foreach ($claves as $clave) {
            $clave_norm = $this->normalizarGriego($clave);
            $clave_raiz = mb_substr($clave_norm, 0, max(3, mb_strlen($clave_norm) - 2), "UTF-8");

            // Buscar coincidencia exacta primero
            if (strpos($normalizado, $clave_norm) !== false) {
                continue; // exacta — ok
            }

            // Buscar por raíz truncada
            $encontrado_parcial = false;
            foreach ($tokens as $token) {
                $token_raiz = mb_substr($token, 0, max(3, mb_strlen($token) - 2), "UTF-8");
                if ($token_raiz === $clave_raiz) {
                    $parciales[] = $clave;
                    $encontrado_parcial = true;
                    break;
                }
            }

            if (!$encontrado_parcial) {
                $faltantes[] = $clave;
            }
        }

        $correcto = empty($faltantes);
        $parcial  = $correcto && !empty($parciales);

        return [
            "correcto"  => $correcto,
            "parcial"   => $parcial,
            "faltantes" => $faltantes,
            "parciales" => $parciales,
        ];
    }

    private function verificarEscritura(string $respuesta, array $claves): bool {
        return $this->verificarEscrituraFlexible($respuesta, $claves)["correcto"];
    }

    /**
     * Normaliza texto griego:
     * - Minúsculas
     * - Elimina todos los diacríticos (espíritus, acentos, iotas suscritas)
     * - Elimina puntuación griega y latina
     * - Colapsa espacios
     */
    private function normalizarGriego(string $texto): string {
        $texto = mb_strtolower($texto, "UTF-8");

        // Mapa de formas con diacríticos → forma base
        $diacriticos = [
            // Alpha
            "ά"=>"α","ὰ"=>"α","ᾶ"=>"α","ἀ"=>"α","ἁ"=>"α","ἂ"=>"α","ἃ"=>"α",
            "ἄ"=>"α","ἅ"=>"α","ἆ"=>"α","ἇ"=>"α","ᾀ"=>"α","ᾁ"=>"α","ᾂ"=>"α",
            "ᾃ"=>"α","ᾄ"=>"α","ᾅ"=>"α","ᾆ"=>"α","ᾇ"=>"α","ά"=>"α","ᾱ"=>"α","ᾰ"=>"α",
            // Epsilon
            "έ"=>"ε","ὲ"=>"ε","ἐ"=>"ε","ἑ"=>"ε","ἒ"=>"ε","ἓ"=>"ε","ἔ"=>"ε","ἕ"=>"ε","έ"=>"ε",
            // Eta
            "ή"=>"η","ὴ"=>"η","ῆ"=>"η","ἠ"=>"η","ἡ"=>"η","ἢ"=>"η","ἣ"=>"η",
            "ἤ"=>"η","ἥ"=>"η","ἦ"=>"η","ἧ"=>"η","ᾐ"=>"η","ᾑ"=>"η","ᾒ"=>"η",
            "ᾓ"=>"η","ᾔ"=>"η","ᾕ"=>"η","ᾖ"=>"η","ᾗ"=>"η","ή"=>"η",
            // Iota
            "ί"=>"ι","ὶ"=>"ι","ῖ"=>"ι","ἰ"=>"ι","ἱ"=>"ι","ἲ"=>"ι","ἳ"=>"ι",
            "ἴ"=>"ι","ἵ"=>"ι","ἶ"=>"ι","ἷ"=>"ι","ΐ"=>"ι","ῒ"=>"ι","ί"=>"ι","ῑ"=>"ι","ῐ"=>"ι",
            // Omicron
            "ό"=>"ο","ὸ"=>"ο","ὀ"=>"ο","ὁ"=>"ο","ὂ"=>"ο","ὃ"=>"ο","ὄ"=>"ο","ὅ"=>"ο","ό"=>"ο",
            // Upsilon
            "ύ"=>"υ","ὺ"=>"υ","ῦ"=>"υ","ὐ"=>"υ","ὑ"=>"υ","ὒ"=>"υ","ὓ"=>"υ",
            "ὔ"=>"υ","ὕ"=>"υ","ὖ"=>"υ","ὗ"=>"υ","ΰ"=>"υ","ῢ"=>"υ","ύ"=>"υ","ῡ"=>"υ","ῠ"=>"υ",
            // Omega
            "ώ"=>"ω","ὼ"=>"ω","ῶ"=>"ω","ὠ"=>"ω","ὡ"=>"ω","ὢ"=>"ω","ὣ"=>"ω",
            "ὤ"=>"ω","ὥ"=>"ω","ὦ"=>"ω","ὧ"=>"ω","ᾠ"=>"ω","ᾡ"=>"ω","ᾢ"=>"ω",
            "ᾣ"=>"ω","ᾤ"=>"ω","ᾥ"=>"ω","ᾦ"=>"ω","ᾧ"=>"ω","ώ"=>"ω",
            // Rho
            "ῤ"=>"ρ","ῥ"=>"ρ",
            // Sigma final
            "ς"=>"σ",
        ];

        $texto = strtr($texto, $diacriticos);
        // Eliminar puntuación griega y latina
        $texto = preg_replace("/[·;,.!?:·\"'\-]/u", " ", $texto);
        // Colapsar espacios
        return preg_replace("/\s+/u", " ", trim($texto));
    }

    private function actualizarRacha(int $userId, $db): void {
        $hoy  = date('Y-m-d');
        $ayer = date('Y-m-d', strtotime('-1 day'));
        $stmt = $db->prepare('SELECT * FROM rachas WHERE usuario_id = ?');
        $stmt->execute([$userId]);
        $racha = $stmt->fetch();
        if (!$racha) return;
        if ($racha['ultima_fecha_estudio'] === $hoy) return;
        if ($racha['ultima_fecha_estudio'] === $ayer) {
            $nueva = $racha['racha_actual'] + 1;
            $max   = max($nueva, $racha['racha_maxima']);
            $db->prepare('UPDATE rachas SET racha_actual=?,racha_maxima=?,ultima_fecha_estudio=? WHERE usuario_id=?')
               ->execute([$nueva, $max, $hoy, $userId]);
        } else {
            if ($racha['escudos'] > 0) {
                $db->prepare('UPDATE rachas SET escudos=escudos-1,ultima_fecha_estudio=? WHERE usuario_id=?')
                   ->execute([$hoy, $userId]);
            } else {
                $db->prepare('UPDATE rachas SET racha_actual=1,ultima_fecha_estudio=? WHERE usuario_id=?')
                   ->execute([$hoy, $userId]);
            }
        }
    }
}
