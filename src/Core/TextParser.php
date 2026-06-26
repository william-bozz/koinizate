<?php
namespace Koinizate\Core;

class TextParser {

    /**
     * Convierte un párrafo griego en HTML con palabras clickeables.
     * Preserva puntuación y espacios.
     */
    public static function parsear(string $texto, array $lexico, string $idioma = 'es'): string {
        // Separar en tokens preservando puntuación
        $tokens = preg_split('/(\s+|(?<=[^\s])[·;,\.·!?;]|(?=[^\s])[·;,\.·!?;])/u', $texto, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

        $html = '';
        foreach ($tokens as $token) {
            // Si es espacio o puntuación sola
            if (preg_match('/^[\s·;,\.!?;]+$/u', $token)) {
                $html .= htmlspecialchars($token);
                continue;
            }

            // Limpiar el token de puntuación para buscar en léxico
            $limpio = preg_replace('/[·;,\.!?;]+$/u', '', $token);
            $puntuacion = mb_substr($token, mb_strlen($limpio));

            // Buscar en léxico (exacto primero, luego sin acento final)
            $entrada = $lexico[$limpio] ?? $lexico[self::normalizarAcentos($limpio)] ?? null;

            if ($entrada) {
                $def     = htmlspecialchars($entrada['definicion_' . $idioma] ?? $entrada['definicion_es'] ?? '');
                $lexica  = htmlspecialchars($entrada['forma_lexica'] ?? $limpio);
                $pos     = htmlspecialchars($entrada['parte_del_discurso'] ?? '');
                $palabra = htmlspecialchars($limpio);

                $html .= '<span class="palabra-griega" '
                       . 'data-forma="' . $palabra . '" '
                       . 'data-lexica="' . $lexica . '" '
                       . 'data-pos="' . $pos . '" '
                       . 'data-def="' . $def . '">'
                       . $palabra
                       . '</span>';
            } else {
                $html .= '<span class="palabra-griega sin-definicion" data-forma="' . htmlspecialchars($limpio) . '">'
                       . htmlspecialchars($limpio)
                       . '</span>';
            }

            $html .= htmlspecialchars($puntuacion);
        }

        return $html;
    }

    /**
     * Normalización básica para matching sin distinción de acento tonal
     * (útil para formas como ἐστίν vs ἐστὶν)
     */
    private static function normalizarAcentos(string $palabra): string {
        $map = [
            'ά'=>'α','έ'=>'ε','ή'=>'η','ί'=>'ι','ό'=>'ο','ύ'=>'υ','ώ'=>'ω',
            'ὰ'=>'α','ὲ'=>'ε','ὴ'=>'η','ὶ'=>'ι','ὸ'=>'ο','ὺ'=>'υ','ὼ'=>'ω',
            'ᾶ'=>'α','ῆ'=>'η','ῖ'=>'ι','ῦ'=>'υ','ῶ'=>'ω',
            'Ά'=>'Α','Έ'=>'Ε','Ή'=>'Η','Ί'=>'Ι','Ό'=>'Ο','Ύ'=>'Υ','Ώ'=>'Ω',
        ];
        return strtr($palabra, $map);
    }
}
