<?php
use Koinizate\Core\TextParser;

$titulo_key = 'titulo_' . $idioma;
$titulo_cap = $capitulo[$titulo_key] ?? $capitulo['titulo_es'];

$labels = [
    'es' => ['leccion'=>'Lección','capitulo'=>'Capítulo','continuar'=>'Marcar como leído','anterior'=>'Anterior','inicio'=>'Volver al inicio','nota'=>'Nota gramatical','cargando'=>'Cargando...'],
    'en' => ['leccion'=>'Lesson','capitulo'=>'Chapter','continuar'=>'Mark as read','anterior'=>'Previous','inicio'=>'Back to start','nota'=>'Grammar note','cargando'=>'Loading...'],
    'pt' => ['leccion'=>'Lição','capitulo'=>'Capítulo','continuar'=>'Marcar como lido','anterior'=>'Anterior','inicio'=>'Voltar ao início','nota'=>'Nota gramatical','cargando'=>'Carregando...'],
    'fr' => ['leccion'=>'Leçon','capitulo'=>'Chapitre','continuar'=>'Marquer comme lu','anterior'=>'Précédent','inicio'=>'Retour au début','nota'=>'Note grammaticale','cargando'=>'Chargement...'],
];
$lbl = $labels[$idioma] ?? $labels['es'];
?>
<!DOCTYPE html>
<html lang="<?= $idioma ?>">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= htmlspecialchars($titulo_cap) ?> — Koinízate</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Noto+Sans:wght@400;500&display=swap" rel="stylesheet">
<style>
*{box-sizing:border-box;margin:0;padding:0}
:root{
  --egeo:#1B3A5C;--egeo-light:#2A5280;--gold:#C9A84C;--gold-light:#E8CC7A;
  --ivory:#F5F0E8;--ivory-dark:#EAE3D5;--terra:#8B4A3A;--stone:#5A6473;--ink:#1A1614;
  --serif:'Cormorant Garamond',serif;--sans:'Noto Sans',sans-serif;
}
body{background:var(--ivory);color:var(--ink);font-family:var(--sans);min-height:100vh}

.mosaic{height:10px;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='42' height='10'%3E%3Crect x='0' width='12' height='10' fill='%231B3A5C'/%3E%3Crect x='12' width='2' height='10' fill='%23C9A84C'/%3E%3Crect x='14' width='12' height='10' fill='%23EAE3D5'/%3E%3Crect x='26' width='2' height='10' fill='%238B4A3A'/%3E%3Crect x='28' width='12' height='10' fill='%23EAE3D5'/%3E%3Crect x='40' width='2' height='10' fill='%23C9A84C'/%3E%3C/svg%3E");background-repeat:repeat-x;background-size:42px 10px}

nav{background:var(--egeo);padding:0 2rem;height:56px;display:flex;align-items:center;justify-content:space-between}
.nav-logo{font-family:var(--serif);font-size:1.5rem;font-weight:700;color:var(--gold);text-decoration:none}
.nav-back{color:rgba(245,240,232,.6);font-size:.82rem;text-decoration:none;display:flex;align-items:center;gap:.4rem}
.nav-back:hover{color:var(--gold)}
.nav-info{font-family:var(--sans);font-size:.78rem;color:rgba(245,240,232,.5);letter-spacing:.04em}

/* Layout principal */
.lector-layout{display:grid;grid-template-columns:1fr 280px;gap:0;min-height:calc(100vh - 66px);max-width:1100px;margin:0 auto;padding:2rem 1.5rem}

/* Columna principal: texto */
.texto-principal{padding-right:2.5rem}
.cap-eyebrow{font-family:var(--sans);font-size:.72rem;letter-spacing:.14em;color:var(--terra);text-transform:uppercase;margin-bottom:.4rem}
.cap-titulo{font-family:var(--serif);font-size:2.8rem;font-weight:700;color:var(--egeo);margin-bottom:.3rem;line-height:1.1}
.cap-titulo-gr{font-family:var(--serif);font-size:1.2rem;font-style:italic;color:var(--stone);margin-bottom:2rem}

.parrafo{font-family:var(--serif);font-size:1.35rem;line-height:2;color:var(--ink);margin-bottom:1.8rem;padding-bottom:1.8rem;border-bottom:1px solid var(--ivory-dark)}
.parrafo:last-child{border-bottom:none}

/* Palabras clickeables */
.palabra-griega{
  cursor:pointer;
  border-bottom:1px solid transparent;
  transition:all .15s;
  border-radius:2px;
  padding:0 1px;
  position:relative;
}
.palabra-griega:hover{
  color:var(--egeo);
  border-bottom-color:var(--gold);
  background:rgba(201,168,76,.08);
}
.palabra-griega.activa{
  color:var(--egeo);
  border-bottom-color:var(--egeo);
  background:rgba(27,58,92,.08);
}
.palabra-griega.sin-definicion:hover{
  border-bottom-color:var(--stone);
  cursor:default;
}

/* Botón completar */
.btn-completar{
  display:inline-flex;align-items:center;gap:.6rem;
  background:var(--egeo);color:var(--ivory);
  font-family:var(--sans);font-size:.9rem;font-weight:500;
  padding:.9rem 2rem;border:none;cursor:pointer;
  letter-spacing:.04em;transition:background .2s;
  margin-top:2rem;
}
.btn-completar:hover{background:var(--egeo-light)}
.btn-completar:disabled{background:var(--stone);cursor:not-allowed}

/* Panel lateral: tooltip fijo */
.panel-lateral{
  position:sticky;top:2rem;
  align-self:start;
  height:fit-content;
}

.palabra-card{
  background:#fff;
  border:1px solid var(--ivory-dark);
  border-top:3px solid var(--egeo);
  padding:1.5rem;
  min-height:200px;
  transition:all .2s;
}
.palabra-card-vacia{
  display:flex;flex-direction:column;align-items:center;justify-content:center;
  text-align:center;color:var(--stone);opacity:.5;
  font-family:var(--serif);font-style:italic;font-size:1rem;
  gap:.5rem;min-height:200px;
}
.palabra-card-vacia .hint{font-family:var(--sans);font-size:.75rem;font-style:normal}

.wc-forma{font-family:var(--serif);font-size:2rem;font-weight:700;color:var(--egeo);line-height:1;margin-bottom:.3rem}
.wc-lexica{font-family:var(--serif);font-style:italic;font-size:1rem;color:var(--stone);margin-bottom:.2rem}
.wc-pos{display:inline-block;font-family:var(--sans);font-size:.68rem;letter-spacing:.1em;text-transform:uppercase;color:var(--terra);background:rgba(139,74,58,.08);padding:.15rem .5rem;margin-bottom:1rem}
.wc-def{font-family:var(--serif);font-size:1.05rem;color:var(--ink);line-height:1.6}
.wc-sep{border:none;border-top:1px solid var(--ivory-dark);margin:.8rem 0}
.wc-audio{display:flex;align-items:center;gap:.5rem;font-family:var(--sans);font-size:.78rem;color:var(--stone);cursor:pointer;background:none;border:1px solid var(--ivory-dark);padding:.4rem .8rem;width:100%;margin-top:.5rem;transition:border-color .2s}
.wc-audio:hover{border-color:var(--egeo);color:var(--egeo)}

/* Progreso de lectura */





/* Toast de completado */
.toast{
  position:fixed;bottom:2rem;left:50%;transform:translateX(-50%) translateY(100px);
  background:var(--egeo);color:var(--ivory);
  font-family:var(--sans);font-size:.9rem;
  padding:1rem 2rem;border-top:3px solid var(--gold);
  transition:transform .3s;z-index:100;
  display:flex;align-items:center;gap:.8rem;
}
.toast.visible{transform:translateX(-50%) translateY(0)}
.toast-xp{font-family:var(--serif);font-size:1.1rem;font-weight:700;color:var(--gold)}

@media(max-width:700px){
  .lector-layout{grid-template-columns:1fr;padding:1rem}
  .texto-principal{padding-right:0}
  .panel-lateral{position:fixed;bottom:0;left:0;right:0;z-index:50;top:auto}
  .palabra-card{border-top:3px solid var(--egeo);border-left:none;border-right:none;border-bottom:none;padding:1rem 1.5rem}
  .palabra-card-vacia{min-height:80px}
}
</style>
</head>
<body>

<div class="mosaic"></div>
<nav>
  <a href="/lecciones" class="nav-back">← <?= $lbl['inicio'] ?></a>
  <a href="/" class="nav-logo">Κοινίζατε</a>
  <span class="nav-info"><?= $lbl['capitulo'] ?> <?= $capitulo['numero'] ?></span>
</nav>

<div class="lector-layout">

  <!-- Columna izquierda: texto -->
  <div class="texto-principal">
    <p class="cap-eyebrow"><?= $lbl['leccion'] ?> <?= $capitulo['numero'] ?></p>
    <h1 class="cap-titulo"><?= htmlspecialchars($titulo_cap) ?></h1>
    <p class="cap-titulo-gr">Ὁ Κόσμος</p>

    <?php foreach ($escenas as $escena):
      $contenido = json_decode($escena['contenido_json'], true);
      $parrafos  = $contenido['parrafos'] ?? [];
    ?>
      <?php foreach ($parrafos as $p): ?>
        <p class="parrafo" id="<?= $p['id'] ?>">
          <?= TextParser::parsear($p['texto'], $lexico, $idioma) ?>
        </p>
      <?php endforeach; ?>
    <?php endforeach; ?>

    <button class="btn-completar" id="btn-completar" onclick="completarLeccion()">
      <?= $lbl['continuar'] ?> →
    </button>
  </div>

  <!-- Columna derecha: panel de palabra -->
  <!-- Columna derecha: panel de palabra -->
  <div class="panel-lateral">

    <div class="palabra-card" id="palabra-card">
      <div class="palabra-card-vacia" id="card-vacia">
        <span>↑</span>
        <span><?= $idioma === 'es' ? 'Toca cualquier palabra' : ($idioma === 'en' ? 'Tap any word' : ($idioma === 'pt' ? 'Toque qualquer palavra' : 'Touchez un mot')) ?></span>
        <span class="hint"><?= $idioma === 'es' ? 'para ver su significado' : ($idioma === 'en' ? 'to see its meaning' : ($idioma === 'pt' ? 'para ver seu significado' : 'pour voir sa signification')) ?></span>
      </div>
      <div id="card-contenido" style="display:none">
        <div class="wc-forma" id="wc-forma"></div>
        <div class="wc-lexica" id="wc-lexica"></div>
        <span class="wc-pos" id="wc-pos"></span>
        <hr class="wc-sep">
        <div class="wc-def" id="wc-def"></div>
        <button class="wc-audio" id="wc-audio" onclick="reproducirAudio()">
          ▶ <?= $idioma === 'es' ? 'Escuchar pronunciación' : ($idioma === 'en' ? 'Hear pronunciation' : ($idioma === 'pt' ? 'Ouvir pronúncia' : 'Écouter la prononciation')) ?>
        </button>
      </div>
    </div>
  </div>

</div>

<div class="toast" id="toast">
  <span id="toast-msg"></span>
  <span class="toast-xp" id="toast-xp"></span>
</div>

<script>
const CAPITULO_ID = <?= $capitulo['id'] ?>;
let palabraActiva = null;

// Click en palabra
document.querySelectorAll('.palabra-griega').forEach(el => {
  el.addEventListener('click', function() {
    if (palabraActiva) palabraActiva.classList.remove('activa');
    this.classList.add('activa');
    palabraActiva = this;

    const forma  = this.dataset.forma  || '';
    const lexica = this.dataset.lexica || forma;
    const pos    = this.dataset.pos    || '';
    const def    = this.dataset.def    || '';

    if (!def) return; // sin definición, no mostrar

    document.getElementById('card-vacia').style.display    = 'none';
    document.getElementById('card-contenido').style.display = 'block';
    document.getElementById('wc-forma').textContent  = forma;
    document.getElementById('wc-lexica').textContent = lexica !== forma ? lexica : '';
    document.getElementById('wc-pos').textContent    = pos;
    document.getElementById('wc-def').textContent    = def;

      });
});

function reproducirAudio() {
  const forma = document.getElementById('wc-forma').textContent;
  if (!forma) return;
  if ('speechSynthesis' in window) {
    window.speechSynthesis.cancel();
    const utt = new SpeechSynthesisUtterance(forma);
    utt.lang = 'el-GR';
    utt.rate = 0.85;
    utt.pitch = 1;
    window.speechSynthesis.speak(utt);
    const btn = document.getElementById('wc-audio');
    btn.textContent = '🔊 ' + forma;
    utt.onend = () => { btn.innerHTML = '▶ Escuchar pronunciación'; };
  } else {
    alert('Tu navegador no soporta síntesis de voz. Usa Chrome o Edge.');
  }
}

function completarLeccion() {
  window.location.href = '/ejercicios/<?= $capitulo["slug"] ?>';
}
</script>
</body>
</html>
