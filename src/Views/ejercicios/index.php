<?php
$idioma = $user['idioma'] ?? 'es';
$instruccion_key = 'instruccion_' . $idioma;
$tit_key = 'titulo_' . $idioma;

$lbl = [
  'es' => ['titulo'=>'Ejercicios','verificar'=>'Verificar','siguiente'=>'Siguiente','ver_resumen'=>'Ver resultados','correcto'=>'¡Correcto!','incorrecto'=>'Incorrecto','corazones'=>'Corazones','teclado'=>'Teclado griego','escribe'=>'Escribe tu traducción...','nai'=>'Ναί','ou'=>'Οὔ','arrastrar_inst'=>'Arrastra aquí','relacionar_inst'=>'Toca un elemento de cada columna para relacionarlos','reintentar'=>'Repetir lección'],
  'en' => ['titulo'=>'Exercises','verificar'=>'Check','siguiente'=>'Next','ver_resumen'=>'See results','correcto'=>'Correct!','incorrecto'=>'Incorrect','corazones'=>'Hearts','teclado'=>'Greek keyboard','escribe'=>'Write your translation...','nai'=>'Ναί','ou'=>'Οὔ','arrastrar_inst'=>'Drop here','relacionar_inst'=>'Tap one element from each column to match them','reintentar'=>'Repeat lesson'],
  'pt' => ['titulo'=>'Exercícios','verificar'=>'Verificar','siguiente'=>'Próximo','ver_resumen'=>'Ver resultados','correcto'=>'Correto!','incorrecto'=>'Incorreto','corazones'=>'Corações','teclado'=>'Teclado grego','escribe'=>'Escreva sua tradução...','nai'=>'Ναί','ou'=>'Οὔ','arrastrar_inst'=>'Solte aqui','relacionar_inst'=>'Toque um elemento de cada coluna para relacioná-los','reintentar'=>'Repetir lição'],
  'fr' => ['titulo'=>'Exercices','verificar'=>'Vérifier','siguiente'=>'Suivant','ver_resumen'=>'Voir les résultats','correcto'=>'Correct !','incorrecto'=>'Incorrect','corazones'=>'Cœurs','teclado'=>'Clavier grec','escribe'=>'Écrivez votre traduction...','nai'=>'Ναί','ou'=>'Οὔ','arrastrar_inst'=>'Déposez ici','relacionar_inst'=>'Touchez un élément de chaque colonne pour les associer','reintentar'=>'Répéter la leçon'],
][$idioma] ?? [];
?>
<!DOCTYPE html>
<html lang="<?= $idioma ?>">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= $lbl['titulo'] ?> — Koinízate</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700&family=Noto+Sans:wght@400;500&display=swap" rel="stylesheet">
<style>
*{box-sizing:border-box;margin:0;padding:0}
:root{--egeo:#1B3A5C;--egeo-light:#2A5280;--gold:#C9A84C;--ivory:#F5F0E8;--ivory-dark:#EAE3D5;--terra:#8B4A3A;--verde:#2D6A4F;--rojo:#8B2020;--stone:#5A6473;--ink:#1A1614;--serif:'Cormorant Garamond',serif;--sans:'Noto Sans',sans-serif}
body{background:var(--ivory);font-family:var(--sans);min-height:100vh}
.mosaic{height:10px;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='42' height='10'%3E%3Crect x='0' width='12' height='10' fill='%231B3A5C'/%3E%3Crect x='12' width='2' height='10' fill='%23C9A84C'/%3E%3Crect x='14' width='12' height='10' fill='%23EAE3D5'/%3E%3Crect x='26' width='2' height='10' fill='%238B4A3A'/%3E%3Crect x='28' width='12' height='10' fill='%23EAE3D5'/%3E%3Crect x='40' width='2' height='10' fill='%23C9A84C'/%3E%3C/svg%3E");background-repeat:repeat-x;background-size:42px 10px}
nav{background:var(--egeo);padding:0 2rem;height:56px;display:flex;align-items:center;justify-content:space-between}
.nav-logo{font-family:var(--serif);font-size:1.5rem;font-weight:700;color:var(--gold);text-decoration:none}
.nav-back{color:rgba(245,240,232,.6);font-size:.82rem;text-decoration:none}
.nav-back:hover{color:var(--gold)}

/* Corazones */
.corazones-bar{background:var(--egeo);padding:.6rem 2rem;display:flex;align-items:center;justify-content:center;gap:.5rem}
.corazon{font-size:1.3rem;transition:all .3s}
.corazon.perdido{opacity:.25;filter:grayscale(1)}

/* Progreso de ejercicios */
.progreso-ej{background:var(--egeo-light);height:6px}
.progreso-ej-fill{height:100%;background:var(--gold);transition:width .4s}

/* Layout */
.ejercicio-wrap{max-width:680px;margin:0 auto;padding:3rem 1.5rem}
.ej-contador{font-family:var(--sans);font-size:.72rem;letter-spacing:.14em;color:var(--stone);text-transform:uppercase;margin-bottom:.5rem}
.ej-tipo{font-family:var(--serif);font-size:2rem;font-weight:700;color:var(--egeo);margin-bottom:.3rem}
.ej-instruccion{font-family:var(--sans);font-size:.9rem;color:var(--stone);margin-bottom:2rem;line-height:1.6}

/* Texto griego en ejercicio */
.texto-gr{font-family:var(--serif);font-size:1.6rem;color:var(--egeo);margin-bottom:1.5rem;line-height:1.5;padding:1.2rem 1.5rem;background:#fff;border-left:4px solid var(--gold);border:1px solid var(--ivory-dark);border-left:4px solid var(--gold)}

/* Escritura */
.input-traduccion{width:100%;padding:.9rem 1rem;border:2px solid var(--ivory-dark);background:#fff;font-family:var(--sans);font-size:1rem;color:var(--ink);outline:none;resize:none;min-height:80px;transition:border-color .2s}
.input-traduccion:focus{border-color:var(--egeo)}
.input-traduccion.correcto{border-color:var(--verde);background:rgba(45,106,79,.05)}
.input-traduccion.incorrecto{border-color:var(--rojo);background:rgba(139,32,32,.05)}

/* Teclado griego */
.teclado-toggle{font-family:var(--sans);font-size:.78rem;color:var(--egeo);background:none;border:1px solid var(--ivory-dark);padding:.35rem .8rem;cursor:pointer;margin-top:.5rem;display:flex;align-items:center;gap:.4rem}
.teclado-toggle:hover{border-color:var(--egeo)}
.teclado{display:none;margin-top:.8rem;background:#fff;border:1px solid var(--ivory-dark);padding:.8rem}
.teclado.visible{display:block}
.teclado-fila{display:flex;flex-wrap:wrap;gap:.3rem;margin-bottom:.3rem}
.tecla{font-family:var(--serif);font-size:1rem;padding:.4rem .5rem;border:1px solid var(--ivory-dark);background:var(--ivory);cursor:pointer;min-width:32px;text-align:center;transition:all .15s;color:var(--egeo)}
.tecla:hover{background:var(--egeo);color:#fff;border-color:var(--egeo)}
.tecla-esp{font-family:var(--sans);font-size:.72rem;padding:.4rem .7rem;background:var(--ivory-dark)}

/* Verdadero/Falso */
.vf-opciones{display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-top:1rem}
.vf-btn{padding:1rem;border:2px solid var(--ivory-dark);background:#fff;font-family:var(--serif);font-size:1.4rem;cursor:pointer;text-align:center;transition:all .2s;color:var(--egeo)}
.vf-btn:hover{border-color:var(--egeo);background:rgba(27,58,92,.05)}
.vf-btn.seleccionado{border-color:var(--egeo);background:rgba(27,58,92,.08)}
.vf-btn.correcto{border-color:var(--verde);background:rgba(45,106,79,.1);color:var(--verde)}
.vf-btn.incorrecto{border-color:var(--rojo);background:rgba(139,32,32,.1);color:var(--rojo)}

/* Arrastrar */
.arrastrar-opciones{display:flex;flex-wrap:wrap;gap:.6rem;margin-bottom:1.5rem}
.opcion-drag{font-family:var(--serif);font-size:1.1rem;padding:.5rem 1rem;border:2px solid var(--ivory-dark);background:#fff;cursor:grab;color:var(--egeo);user-select:none;transition:all .2s}
.opcion-drag:hover{border-color:var(--gold);background:rgba(201,168,76,.08)}
.opcion-drag.dragging{opacity:.4}
.opcion-drag.usada{opacity:.3;pointer-events:none}
.drop-zone{display:inline-block;min-width:120px;border-bottom:2px solid var(--egeo);padding:.2rem .5rem;margin:0 .3rem;font-family:var(--serif);font-size:1.3rem;color:var(--egeo);vertical-align:middle;text-align:center;cursor:pointer;transition:all .2s}
.drop-zone.drag-over{background:rgba(201,168,76,.15);border-color:var(--gold)}
.drop-zone.correcto{border-color:var(--verde);color:var(--verde)}
.drop-zone.incorrecto{border-color:var(--rojo);color:var(--rojo)}

/* Relacionar */
.relacionar-grid{display:grid;grid-template-columns:1fr 1fr;gap:1.5rem}
.relacionar-col{display:flex;flex-direction:column;gap:.6rem}
.rel-item{font-family:var(--serif);font-size:1.15rem;padding:.7rem 1rem;border:2px solid var(--ivory-dark);background:#fff;cursor:pointer;text-align:center;color:var(--egeo);transition:all .2s}
.rel-item:hover{border-color:var(--gold)}
.rel-item.seleccionado{border-color:var(--egeo);background:rgba(27,58,92,.08)}
.rel-item.conectado{border-color:var(--verde);background:rgba(45,106,79,.08);color:var(--verde);cursor:default}
.rel-item.incorrecto{border-color:var(--rojo);background:rgba(139,32,32,.08);animation:shake .3s}
@keyframes shake{0%,100%{transform:translateX(0)}25%{transform:translateX(-5px)}75%{transform:translateX(5px)}}

/* Feedback */
.feedback{padding:.8rem 1rem;margin-top:1rem;font-family:var(--sans);font-size:.88rem;display:none;align-items:center;gap:.6rem}
.feedback.visible{display:flex}
.feedback.correcto-fb{background:rgba(45,106,79,.1);border-left:4px solid var(--verde);color:var(--verde)}
.feedback.incorrecto-fb{background:rgba(139,32,32,.1);border-left:4px solid var(--rojo);color:var(--rojo)}
.feedback-respuesta{font-family:var(--serif);font-size:1rem;margin-top:.3rem;display:block}

/* Botón verificar/siguiente */
.btn-accion{width:100%;padding:.9rem;font-family:var(--sans);font-size:.9rem;font-weight:500;letter-spacing:.04em;border:none;cursor:pointer;margin-top:1.5rem;transition:background .2s}
.btn-verificar{background:var(--egeo);color:var(--ivory)}
.btn-verificar:hover{background:var(--egeo-light)}
.btn-verificar:disabled{background:var(--stone);cursor:not-allowed}
.btn-siguiente{background:var(--gold);color:var(--egeo);display:none}
.btn-siguiente:hover{background:#d4b050}

/* Pantalla de resumen */
.resumen{display:none;text-align:center;padding:2rem 0}
.resumen.visible{display:block}
.resumen-titulo{font-family:var(--serif);font-size:2.5rem;font-weight:700;color:var(--egeo);margin-bottom:.3rem}
.resumen-sub{font-family:var(--serif);font-style:italic;color:var(--stone);margin-bottom:2rem;font-size:1.05rem}
.resumen-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:1rem;margin-bottom:2rem}
.resumen-stat{background:#fff;border:1px solid var(--ivory-dark);padding:1.2rem;text-align:center}
.resumen-stat-num{font-family:var(--serif);font-size:2.2rem;font-weight:700;color:var(--egeo);line-height:1}
.resumen-stat-label{font-family:var(--sans);font-size:.72rem;color:var(--stone);letter-spacing:.08em;text-transform:uppercase;margin-top:.3rem}
.resumen-stat-num.gold{color:var(--gold)}
.ranking-mini{background:#fff;border:1px solid var(--ivory-dark);margin-bottom:1.5rem;overflow:hidden}
.ranking-mini-row{display:flex;align-items:center;gap:.8rem;padding:.7rem 1rem;border-bottom:1px solid var(--ivory-dark);font-family:var(--sans);font-size:.85rem}
.ranking-mini-row:last-child{border-bottom:none}
.ranking-mini-row.yo{background:rgba(201,168,76,.08);border-left:3px solid var(--gold)}
.rank-pos{font-family:var(--serif);font-size:1rem;font-weight:700;color:var(--stone);width:24px}
.rank-avatar{width:32px;height:32px;border-radius:50%;background:var(--ivory-dark);display:flex;align-items:center;justify-content:center;font-family:var(--serif);font-weight:700;color:var(--egeo);font-size:.9rem;flex-shrink:0}
.rank-nombre{flex:1;font-weight:500}
.rank-xp{font-family:var(--serif);font-weight:700;color:var(--egeo)}
.btn-continuar{display:block;width:100%;text-align:center;background:var(--egeo);color:var(--ivory);font-family:var(--sans);font-size:.9rem;font-weight:500;padding:.9rem;text-decoration:none;letter-spacing:.04em;transition:background .2s;border:none;cursor:pointer}
.btn-continuar:hover{background:var(--egeo-light)}
.btn-reintentar{display:block;width:100%;text-align:center;background:transparent;color:var(--stone);font-family:var(--sans);font-size:.85rem;padding:.7rem;text-decoration:none;border:1px solid var(--ivory-dark);margin-top:.5rem;cursor:pointer}
.btn-reintentar:hover{border-color:var(--egeo);color:var(--egeo)}
</style>
</head>
<body>
<div class="mosaic"></div>
<nav>
  <a href="/leccion/<?= $capitulo['slug'] ?>" class="nav-back">← <?= $capitulo[$tit_key] ?? $capitulo['titulo_es'] ?></a>
  <a href="/" class="nav-logo">Κοινίζατε</a>
  <span></span>
</nav>

<div class="corazones-bar">
  <span id="corazon-1" class="corazon">❤️</span>
  <span id="corazon-2" class="corazon">❤️</span>
  <span id="corazon-3" class="corazon">❤️</span>
</div>
<div class="progreso-ej"><div class="progreso-ej-fill" id="prog-fill"></div></div>

<div class="ejercicio-wrap" id="ejercicio-wrap">
  <!-- Ejercicios renderizados por JS desde PHP data -->
</div>

<div class="ejercicio-wrap" id="resumen-wrap" style="display:none">
  <div class="resumen visible" id="resumen"></div>
</div>

<script>
const CAPITULO_ID = <?= $capitulo['id'] ?>;
const IDIOMA      = '<?= $idioma ?>';
const LBL = <?= json_encode($lbl, JSON_UNESCAPED_UNICODE) ?>;

const EJERCICIOS  = <?= json_encode(array_map(function($ej) use ($idioma) {
    $instruccion_key = 'instruccion_' . $idioma;
    return [
        'id'         => $ej['id'],
        'tipo'       => $ej['tipo'],
        'instruccion'=> $ej[$instruccion_key] ?? $ej['instruccion_es'],
        'contenido'  => $ej['contenido'],
        'xp'         => $ej['xp_recompensa'],
    ];
}, $ejercicios), JSON_UNESCAPED_UNICODE) ?>;

// Estado global
let ejActual    = 0;
let pregActual  = 0;
let corazones   = 3;
let puntos      = 0;
let totalPregs  = 0;
let pregRespondidas = 0;
let selRelIzq   = null;
let pares_relacionar = [];
let palabrasNuevas = new Set();

// Contar total de preguntas
EJERCICIOS.forEach(ej => {
    if (ej.tipo === 'relacionar') totalPregs += (ej.contenido.pares || []).length;
    else totalPregs += (ej.contenido.preguntas || ej.contenido.pares || []).length;
});

function actualizarProgreso() {
    const pct = totalPregs > 0 ? Math.round(pregRespondidas / totalPregs * 100) : 0;
    document.getElementById('prog-fill').style.width = pct + '%';
}

function perderCorazon() {
    corazones--;
    const c = document.getElementById('corazon-' + (corazones + 1));
    if (c) c.classList.add('perdido');
    if (corazones <= 0) {
        setTimeout(() => {
            if (confirm(IDIOMA === 'es' ? '¡Sin corazones! Debes repetir la lección.' :
                        IDIOMA === 'en' ? 'No hearts left! You must repeat the lesson.' :
                        IDIOMA === 'pt' ? 'Sem corações! Você deve repetir a lição.' :
                        'Plus de cœurs ! Vous devez répéter la leçon.')) {
                window.location.href = '/leccion/<?= $capitulo['slug'] ?>';
            } else {
                window.location.href = '/leccion/<?= $capitulo['slug'] ?>';
            }
        }, 500);
    }
}

// ─── Renderizado de ejercicios ───────────────────────────────────────────────

function renderEjercicio() {
    if (ejActual >= EJERCICIOS.length) {
        mostrarResumen();
        return;
    }
    const ej   = EJERCICIOS[ejActual];
    const wrap = document.getElementById('ejercicio-wrap');

    const total = EJERCICIOS.length;
    let html = `<p class="ej-contador">${LBL.titulo} ${ejActual + 1} / ${total}</p>`;

    if (ej.tipo === 'escritura') {
        const p = ej.contenido.preguntas[pregActual];
        html += `
        <p class="ej-tipo">${ej.instruccion}</p>
        <div class="texto-gr">${p.texto_gr}</div>
        <textarea class="input-traduccion" id="resp-input" placeholder="${LBL.escribe}" rows="3"></textarea>
        <button class="teclado-toggle" onclick="toggleTeclado()">⌨ ${LBL.teclado}</button>
        <div class="teclado" id="teclado-gr">${renderTeclado()}</div>
        <div class="feedback" id="feedback"></div>
        <button class="btn-accion btn-verificar" id="btn-verificar" onclick="verificarEscritura(${ej.id}, ${p.id})">${LBL.verificar}</button>
        <button class="btn-accion btn-siguiente" id="btn-siguiente" onclick="siguiente()">${LBL.siguiente} →</button>`;

    } else if (ej.tipo === 'seleccion') {
        const p = ej.contenido.preguntas[pregActual];
        html += `
        <p class="ej-tipo">${ej.instruccion}</p>
        <div class="texto-gr">${p.texto}</div>
        <div class="vf-opciones">
          <button class="vf-btn" id="opt-nai" onclick="verificarVF(${ej.id}, ${p.id}, 'nai', this)">${LBL.nai}</button>
          <button class="vf-btn" id="opt-ou"  onclick="verificarVF(${ej.id}, ${p.id}, 'ou', this)">${LBL.ou}</button>
        </div>
        <div class="feedback" id="feedback"></div>
        <button class="btn-accion btn-siguiente" id="btn-siguiente" onclick="siguiente()" style="display:none">${LBL.siguiente} →</button>`;

    } else if (ej.tipo === 'arrastrar') {
        const p = ej.contenido.preguntas[pregActual];
        const opciones = [...p.opciones].sort(() => Math.random() - .5);
        const fraseHtml = p.frase.replace('___', `<span class="drop-zone" id="drop-zone" ondragover="event.preventDefault();this.classList.add('drag-over')" ondragleave="this.classList.remove('drag-over')" ondrop="soltarPalabra(event)">${LBL.arrastrar_inst}</span>`);
        const opcionesHtml = opciones.map(o =>
            `<div class="opcion-drag" draggable="true" ondragstart="arrastrar(event,'${o}')" onclick="tocarOpcion(this,'${o}')">${o}</div>`
        ).join('');
        html += `
        <p class="ej-tipo">${ej.instruccion}</p>
        <div class="arrastrar-opciones">${opcionesHtml}</div>
        <div class="texto-gr">${fraseHtml}</div>
        <div class="feedback" id="feedback"></div>
        <button class="btn-accion btn-verificar" id="btn-verificar" onclick="verificarArrastrar(${ej.id}, ${p.id}, '${p.respuesta}')">${LBL.verificar}</button>
        <button class="btn-accion btn-siguiente" id="btn-siguiente" onclick="siguiente()">${LBL.siguiente} →</button>`;

    } else if (ej.tipo === 'relacionar') {
        pares_relacionar = [];
        selRelIzq = null;
        const pares = ej.contenido.pares;
        const der   = [...pares].sort(() => Math.random() - .5);
        const izqHtml = pares.map(p =>
            `<div class="rel-item" id="izq-${p.id}" onclick="selRel('izq','${p.izquierda}',${p.id},this)">${p.izquierda}</div>`
        ).join('');
        const derHtml = der.map(p =>
            `<div class="rel-item" id="der-${p.id}" onclick="selRel('der','${p.derecha}',${p.id},this)">${p.derecha}</div>`
        ).join('');
        html += `
        <p class="ej-tipo">${ej.instruccion}</p>
        <p class="ej-instruccion">${LBL.relacionar_inst}</p>
        <div class="relacionar-grid">
          <div class="relacionar-col">${izqHtml}</div>
          <div class="relacionar-col">${derHtml}</div>
        </div>
        <div class="feedback" id="feedback"></div>
        <button class="btn-accion btn-siguiente" id="btn-siguiente" onclick="siguiente()" style="display:none">${LBL.siguiente} →</button>`;
    }

    wrap.innerHTML = html;
}

// ─── Verificaciones ──────────────────────────────────────────────────────────

function verificarEscritura(ejId, pregId) {
    const resp = document.getElementById('resp-input').value.trim();
    if (!resp) return;
    document.getElementById('btn-verificar').disabled = true;

    fetch('/ejercicio/verificar', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({tipo:'escritura', ejercicio_id:ejId, pregunta_id:pregId, respuesta:resp})
    })
    .then(r => r.json())
    .then(data => {
        const input = document.getElementById('resp-input');
        const fb    = document.getElementById('feedback');
        pregRespondidas++;
        actualizarProgreso();
        if (data.data.correcto) {
            puntos++;
            input.classList.add('correcto');
            fb.className = 'feedback visible correcto-fb';
            fb.innerHTML = '✓ ' + LBL.correcto;
        } else {
            perderCorazon();
            puntos = Math.max(0, puntos - 2);
            input.classList.add('incorrecto');
            fb.className = 'feedback visible incorrecto-fb';
            const ej = EJERCICIOS[ejActual];
            const p  = ej.contenido.preguntas[pregActual];
            const resp_key = 'respuesta_' + IDIOMA;
            fb.innerHTML = '✗ ' + LBL.incorrecto + '<span class="feedback-respuesta">' + p.respuesta_modelo + '</span>';
        }
        document.getElementById('btn-siguiente').style.display = 'block';
    });
}

function verificarVF(ejId, pregId, valor, btn) {
    document.querySelectorAll('.vf-btn').forEach(b => b.onclick = null);
    fetch('/ejercicio/verificar', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({tipo:'seleccion', ejercicio_id:ejId, pregunta_id:pregId, respuesta:valor})
    })
    .then(r => r.json())
    .then(data => {
        pregRespondidas++;
        actualizarProgreso();
        const fb = document.getElementById('feedback');
        if (data.data.correcto) {
            puntos++;
            btn.classList.add('correcto');
            fb.className = 'feedback visible correcto-fb';
            fb.innerHTML = '✓ ' + LBL.correcto;
        } else {
            perderCorazon();
            puntos = Math.max(0, puntos - 2);
            btn.classList.add('incorrecto');
            // Mostrar cuál era la correcta
            const otra = valor === 'nai' ? document.getElementById('opt-ou') : document.getElementById('opt-nai');
            otra.classList.add('correcto');
            fb.className = 'feedback visible incorrecto-fb';
            fb.innerHTML = '✗ ' + LBL.incorrecto;
        }
        document.getElementById('btn-siguiente').style.display = 'block';
    });
}

let palabraArrastrada = null;
function arrastrar(e, palabra) { palabraArrastrada = palabra; e.target.classList.add('dragging'); }
function tocarOpcion(el, palabra) {
    // Para móvil: tap pone la palabra en la zona
    document.querySelectorAll('.opcion-drag').forEach(o => o.classList.remove('seleccionado'));
    el.classList.add('seleccionado');
    palabraArrastrada = palabra;
    const dz = document.getElementById('drop-zone');
    if (dz) { dz.textContent = palabra; dz.dataset.valor = palabra; }
}
function soltarPalabra(e) {
    e.preventDefault();
    const dz = e.currentTarget;
    dz.textContent    = palabraArrastrada;
    dz.dataset.valor  = palabraArrastrada;
    dz.classList.remove('drag-over');
    document.querySelectorAll('.opcion-drag').forEach(o => o.classList.remove('dragging'));
}

function verificarArrastrar(ejId, pregId, respCorrecta) {
    const dz   = document.getElementById('drop-zone');
    const resp = dz?.dataset.valor || '';
    if (!resp || resp === LBL.arrastrar_inst) return;
    document.getElementById('btn-verificar').disabled = true;

    fetch('/ejercicio/verificar', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({tipo:'arrastrar', ejercicio_id:ejId, pregunta_id:pregId, respuesta:resp})
    })
    .then(r => r.json())
    .then(data => {
        pregRespondidas++;
        actualizarProgreso();
        const fb = document.getElementById('feedback');
        if (data.data.correcto) {
            puntos++;
            dz.classList.add('correcto');
            fb.className = 'feedback visible correcto-fb';
            fb.innerHTML = '✓ ' + LBL.correcto;
        } else {
            perderCorazon();
            puntos = Math.max(0, puntos - 2);
            dz.classList.add('incorrecto');
            fb.className = 'feedback visible incorrecto-fb';
            fb.innerHTML = '✗ ' + LBL.incorrecto + '<span class="feedback-respuesta">' + respCorrecta + '</span>';
        }
        document.getElementById('btn-siguiente').style.display = 'block';
    });
}

// Relacionar
function selRel(lado, valor, id, el) {
    if (el.classList.contains('conectado')) return;

    if (lado === 'izq') {
        document.querySelectorAll('[id^="izq-"]').forEach(e => { if (!e.classList.contains('conectado')) e.classList.remove('seleccionado'); });
        el.classList.add('seleccionado');
        selRelIzq = {valor, id, el};
        if (selRelIzq && window._selRelDer) intentarConectar();
    } else {
        document.querySelectorAll('[id^="der-"]').forEach(e => { if (!e.classList.contains('conectado')) e.classList.remove('seleccionado'); });
        el.classList.add('seleccionado');
        window._selRelDer = {valor, id, el};
        if (selRelIzq) intentarConectar();
    }
}

function intentarConectar() {
    const izq = selRelIzq;
    const der = window._selRelDer;
    if (!izq || !der) return;

    const ej = EJERCICIOS[ejActual];
    const par_correcto = (ej.contenido.pares || []).find(p => p.izquierda === izq.valor && p.derecha === der.valor);

    if (par_correcto) {
        puntos++;
        pregRespondidas++;
        izq.el.classList.remove('seleccionado');
        der.el.classList.remove('seleccionado');
        izq.el.classList.add('conectado');
        der.el.classList.add('conectado');
        pares_relacionar.push({izquierda: izq.valor, derecha: der.valor});
        actualizarProgreso();

        const totalPares = (ej.contenido.pares || []).length;
        if (pares_relacionar.length === totalPares) {
            const fb = document.getElementById('feedback');
            fb.className = 'feedback visible correcto-fb';
            fb.innerHTML = '✓ ' + LBL.correcto;
            document.getElementById('btn-siguiente').style.display = 'block';
        }
    } else {
        perderCorazon();
        puntos = Math.max(0, puntos - 2);
        izq.el.classList.add('incorrecto');
        der.el.classList.add('incorrecto');
        setTimeout(() => {
            izq.el.classList.remove('incorrecto','seleccionado');
            der.el.classList.remove('incorrecto','seleccionado');
        }, 600);
    }

    selRelIzq = null;
    window._selRelDer = null;
}

// ─── Navegación ──────────────────────────────────────────────────────────────

function siguiente() {
    const ej = EJERCICIOS[ejActual];
    let avanzarEj = false;

    if (ej.tipo === 'escritura' || ej.tipo === 'seleccion' || ej.tipo === 'arrastrar') {
        const preguntas = ej.contenido.preguntas || [];
        if (pregActual + 1 < preguntas.length) {
            pregActual++;
        } else {
            pregActual = 0;
            avanzarEj = true;
        }
    } else {
        avanzarEj = true;
    }

    if (avanzarEj) {
        ejActual++;
        pregActual = 0;
    }
    renderEjercicio();
}

// ─── Teclado griego ──────────────────────────────────────────────────────────

function renderTeclado() {
    const filas = [
        ['α','β','γ','δ','ε','ζ','η','θ'],
        ['ι','κ','λ','μ','ν','ξ','ο','π'],
        ['ρ','σ','τ','υ','φ','χ','ψ','ω'],
        ['ά','έ','ή','ί','ό','ύ','ώ'],
        ['ἀ','ἁ','ἐ','ἑ','ἰ','ἱ','ὀ','ὁ'],
        ['ὐ','ὑ','ὠ','ὡ','ᾶ','ῆ','ῖ','ῦ','ῶ'],
    ];
    return filas.map(fila =>
        `<div class="teclado-fila">${fila.map(t => `<button type="button" class="tecla" onclick="insertarLetra('${t}')">${t}</button>`).join('')}</div>`
    ).join('') + `<div class="teclado-fila"><button type="button" class="tecla tecla-esp" onclick="insertarLetra(' ')">espacio</button><button type="button" class="tecla tecla-esp" onclick="borrarLetra()">⌫</button></div>`;
}

function toggleTeclado() {
    document.getElementById('teclado-gr').classList.toggle('visible');
}
function insertarLetra(l) {
    const t = document.getElementById('resp-input');
    if (!t) return;
    const s = t.selectionStart, e = t.selectionEnd;
    t.value = t.value.slice(0,s) + l + t.value.slice(e);
    t.selectionStart = t.selectionEnd = s + l.length;
    t.focus();
}
function borrarLetra() {
    const t = document.getElementById('resp-input');
    if (!t || t.selectionStart === 0) return;
    const s = t.selectionStart;
    t.value = t.value.slice(0, s-1) + t.value.slice(s);
    t.selectionStart = t.selectionEnd = s - 1;
    t.focus();
}

// ─── Resumen ─────────────────────────────────────────────────────────────────

function mostrarResumen() {
    document.getElementById('ejercicio-wrap').style.display = 'none';
    document.getElementById('resumen-wrap').style.display   = 'block';

    fetch('/ejercicio/completar', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            capitulo_id:   CAPITULO_ID,
            puntos:        puntos,
            total:         totalPregs,
            palabras_nuevas: <?= count($ejercicios) > 0 ? 22 : 0 ?>
        })
    })
    .then(r => r.json())
    .then(data => {
        const d = data.data;
        const pct = totalPregs > 0 ? Math.round(puntos / totalPregs * 100) : 0;

        let rankHtml = '';
        if (d.arriba) {
            rankHtml += `<div class="ranking-mini-row"><span class="rank-pos">${d.posicion - 1}</span><div class="rank-avatar">${d.arriba.nombre[0]}</div><span class="rank-nombre">${d.arriba.nombre} ${d.arriba.apellido}</span><span class="rank-xp">${d.arriba.xp_total} XP</span></div>`;
        }
        rankHtml += `<div class="ranking-mini-row yo"><span class="rank-pos">${d.posicion}</span><div class="rank-avatar" style="background:var(--egeo);color:#fff"><?= mb_substr($user['nombre'], 0, 1) ?></div><span class="rank-nombre" style="color:var(--egeo);font-weight:600"><?= htmlspecialchars($user['nombre']) ?></span><span class="rank-xp" style="color:var(--terra)">${d.xp_total} XP</span></div>`;
        if (d.abajo) {
            rankHtml += `<div class="ranking-mini-row"><span class="rank-pos">${d.posicion + 1}</span><div class="rank-avatar">${d.abajo.nombre[0]}</div><span class="rank-nombre">${d.abajo.nombre} ${d.abajo.apellido}</span><span class="rank-xp">${d.abajo.xp_total} XP</span></div>`;
        }

        const titulo = pct >= 70
            ? (IDIOMA==='es'?'¡Καλῶς ἐποίησας!':IDIOMA==='en'?'Well done!':IDIOMA==='pt'?'Muito bem!':'Bravo !')
            : (IDIOMA==='es'?'Sigue practicando':IDIOMA==='en'?'Keep practicing':IDIOMA==='pt'?'Continue praticando':'Continuez à pratiquer');

        document.getElementById('resumen').innerHTML = `
          <h2 class="resumen-titulo">${titulo}</h2>
          <p class="resumen-sub">${puntos} / ${totalPregs} ${IDIOMA==='es'?'respuestas correctas':IDIOMA==='en'?'correct answers':IDIOMA==='pt'?'respostas corretas':'réponses correctes'}</p>
          <div class="resumen-grid">
            <div class="resumen-stat"><div class="resumen-stat-num gold">+${d.xp}</div><div class="resumen-stat-label">XP</div></div>
            <div class="resumen-stat"><div class="resumen-stat-num">🔥 ${d.racha}</div><div class="resumen-stat-label">${IDIOMA==='es'?'días seguidos':IDIOMA==='en'?'day streak':IDIOMA==='pt'?'dias seguidos':'jours consécutifs'}</div></div>
            <div class="resumen-stat"><div class="resumen-stat-num">${d.nivel}</div><div class="resumen-stat-label">${IDIOMA==='es'?'Nivel':IDIOMA==='en'?'Level':IDIOMA==='pt'?'Nível':'Niveau'}</div></div>
            <div class="resumen-stat"><div class="resumen-stat-num">${d.palabras}</div><div class="resumen-stat-label">${IDIOMA==='es'?'palabras nuevas':IDIOMA==='en'?'new words':IDIOMA==='pt'?'palavras novas':'mots nouveaux'}</div></div>
          </div>
          <p style="font-family:var(--sans);font-size:.75rem;color:var(--stone);letter-spacing:.08em;text-transform:uppercase;margin-bottom:.5rem">${IDIOMA==='es'?'Tu posición':IDIOMA==='en'?'Your position':IDIOMA==='pt'?'Sua posição':'Votre position'}</p>
          <div class="ranking-mini">${rankHtml}</div>
          <a href="/curso/input-comprensible" class="btn-continuar">${IDIOMA==='es'?'Continuar al siguiente capítulo':IDIOMA==='en'?'Continue to next chapter':IDIOMA==='pt'?'Continuar para o próximo capítulo':'Continuer au chapitre suivant'} →</a>
          <button onclick="window.location.href='/leccion/<?= $capitulo['slug'] ?>'" class="btn-reintentar">↺ ${LBL.reintentar}</button>
        `;
    });
}

// Iniciar
renderEjercicio();
</script>
</body>
</html>
