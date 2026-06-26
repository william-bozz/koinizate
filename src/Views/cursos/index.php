<?php
$labels = [
  'es' => ['titulo'=>'Elige tu curso','subtitulo'=>'Tres caminos para aprender el griego koiné','proxim'=>'Próximamente','empezar'=>'Comenzar curso','premium'=>'Solo Premium','disponible'=>'Disponible'],
  'en' => ['titulo'=>'Choose your course','subtitulo'=>'Three paths to learn Koine Greek','proxim'=>'Coming soon','empezar'=>'Start course','premium'=>'Premium only','disponible'=>'Available'],
  'pt' => ['titulo'=>'Escolha seu curso','subtitulo'=>'Três caminhos para aprender o grego koinê','proxim'=>'Em breve','empezar'=>'Começar curso','premium'=>'Somente Premium','disponible'=>'Disponível'],
  'fr' => ['titulo'=>'Choisissez votre cours','subtitulo'=>'Trois chemins pour apprendre le grec koinè','proxim'=>'Bientôt disponible','empezar'=>'Commencer le cours','premium'=>'Premium uniquement','disponible'=>'Disponible'],
];
$lbl = $labels[$idioma] ?? $labels['es'];

$desc_key = 'descripcion_' . $idioma;
$nom_key  = 'nombre_' . $idioma;
?>
<!DOCTYPE html>
<html lang="<?= $idioma ?>">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= $lbl['titulo'] ?> — Koinízate</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=Noto+Sans:wght@400;500&display=swap" rel="stylesheet">
<style>
*{box-sizing:border-box;margin:0;padding:0}
:root{--egeo:#1B3A5C;--egeo-light:#2A5280;--gold:#C9A84C;--gold-light:#E8CC7A;--ivory:#F5F0E8;--ivory-dark:#EAE3D5;--terra:#8B4A3A;--stone:#5A6473;--ink:#1A1614;--serif:'Cormorant Garamond',serif;--sans:'Noto Sans',sans-serif}
body{background:var(--ivory);font-family:var(--sans);min-height:100vh}
.mosaic{height:10px;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='42' height='10'%3E%3Crect x='0' width='12' height='10' fill='%231B3A5C'/%3E%3Crect x='12' width='2' height='10' fill='%23C9A84C'/%3E%3Crect x='14' width='12' height='10' fill='%23EAE3D5'/%3E%3Crect x='26' width='2' height='10' fill='%238B4A3A'/%3E%3Crect x='28' width='12' height='10' fill='%23EAE3D5'/%3E%3Crect x='40' width='2' height='10' fill='%23C9A84C'/%3E%3C/svg%3E");background-repeat:repeat-x;background-size:42px 10px}
nav{background:var(--egeo);padding:0 2rem;height:56px;display:flex;align-items:center;justify-content:space-between}
.nav-logo{font-family:var(--serif);font-size:1.5rem;font-weight:700;color:var(--gold);text-decoration:none}
.nav-right{display:flex;align-items:center;gap:1.5rem}
.nav-user{font-family:var(--sans);font-size:.8rem;color:rgba(245,240,232,.6)}
.nav-link{color:rgba(245,240,232,.5);text-decoration:none;font-size:.78rem;font-family:var(--sans)}
.nav-link:hover{color:var(--gold)}

.hero{background:var(--egeo);padding:3.5rem 2rem;text-align:center}
.hero h1{font-family:var(--serif);font-size:2.8rem;font-weight:700;color:var(--ivory);margin-bottom:.5rem}
.hero p{font-family:var(--serif);font-style:italic;color:rgba(245,240,232,.55);font-size:1.1rem}

.container{max-width:960px;margin:0 auto;padding:3rem 1.5rem}
.cursos-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:2rem}

.curso-card{background:#fff;border:1px solid var(--ivory-dark);display:flex;flex-direction:column;transition:border-color .2s;position:relative}
.curso-card:hover:not(.curso-inactivo){border-color:var(--egeo)}
.curso-card.curso-destacado{border:2px solid var(--egeo)}
.curso-inactivo{opacity:.7}

.curso-header{padding:2rem 2rem 1.5rem;border-bottom:1px solid var(--ivory-dark);position:relative}
.curso-icono{font-size:2rem;margin-bottom:1rem;display:block}
.curso-nombre-gr{font-family:var(--serif);font-size:1rem;font-style:italic;color:var(--stone);margin-bottom:.4rem;line-height:1.4}
.curso-nombre{font-family:var(--serif);font-size:1.5rem;font-weight:700;color:var(--egeo);line-height:1.2;margin-bottom:0}

.curso-body{padding:1.5rem 2rem;flex:1}
.curso-desc{font-family:var(--sans);font-size:.84rem;color:var(--stone);line-height:1.7}

.curso-footer{padding:1.2rem 2rem;border-top:1px solid var(--ivory-dark)}
.badge-disponible{display:inline-block;font-family:var(--sans);font-size:.65rem;letter-spacing:.1em;text-transform:uppercase;padding:.2rem .6rem;margin-bottom:.8rem}
.badge-libre{background:rgba(27,58,92,.08);color:var(--egeo)}
.badge-premium{background:rgba(201,168,76,.15);color:#8a6d1a}
.badge-pronto{background:rgba(90,100,115,.1);color:var(--stone)}

.btn-curso{display:block;width:100%;text-align:center;padding:.8rem;font-family:var(--sans);font-size:.85rem;font-weight:500;letter-spacing:.04em;text-decoration:none;border:none;cursor:pointer;transition:all .2s}
.btn-curso-activo{background:var(--egeo);color:var(--ivory)}
.btn-curso-activo:hover{background:var(--egeo-light)}
.btn-curso-deshabilitado{background:var(--ivory-dark);color:var(--stone);cursor:not-allowed}

.estrella-destacado{position:absolute;top:-1px;right:1.5rem;background:var(--egeo);color:var(--gold);font-family:var(--sans);font-size:.65rem;font-weight:500;padding:.2rem .7rem;letter-spacing:.06em;text-transform:uppercase}
</style>
</head>
<body>
<div class="mosaic"></div>
<nav>
  <a href="/" class="nav-logo">Κοινίζατε</a>
  <div class="nav-right">
    <span class="nav-user"><?= htmlspecialchars($user['nombre']) ?></span>
    <a href="/logout" class="nav-link">Salir</a>
  </div>
</nav>

<div class="hero">
  <h1><?= $lbl['titulo'] ?></h1>
  <p><?= $lbl['subtitulo'] ?></p>
</div>

<div class="mosaic" style="transform:scaleX(-1)"></div>

<div class="container">
  <div class="cursos-grid">
    <?php foreach ($cursos as $curso):
      $nombre = $curso[$nom_key] ?? $curso['nombre_es'];
      $desc   = $curso[$desc_key] ?? $curso['descripcion_es'];
      $activo = $curso['disponible'];
      $clases = 'curso-card' . (!$activo ? ' curso-inactivo' : '') . ($curso['orden'] == 1 ? ' curso-destacado' : '');
    ?>
    <div class="<?= $clases ?>">
      <?php if ($curso['orden'] == 1): ?>
        <span class="estrella-destacado">★ <?= $lbl['disponible'] ?></span>
      <?php endif; ?>
      <div class="curso-header">
        <?php
$iconos_svg = [
  'libro' => '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>',
  'cruz'  => '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><line x1="12" y1="2" x2="12" y2="22"/><line x1="2" y1="8" x2="22" y2="8"/></svg>',
  'alfa'  => '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 20 L12 4 L20 20 M7 14 h10"/></svg>',
];
// fallback para emojis que quedaron en DB
$icono_key = match(true) {
  str_contains($curso['icono'], 'libro') || str_contains($curso['icono'], '📖') => 'libro',
  str_contains($curso['icono'], 'cruz')  || str_contains($curso['icono'], '✝')  => 'cruz',
  default => 'alfa',
};
?>
<span class="curso-icono"><?= $iconos_svg[$icono_key] ?></span>
        <p class="curso-nombre-gr"><?= htmlspecialchars($curso['nombre_gr']) ?></p>
        <h2 class="curso-nombre"><?= htmlspecialchars($nombre) ?></h2>
      </div>
      <div class="curso-body">
        <p class="curso-desc"><?= htmlspecialchars($desc) ?></p>
      </div>
      <div class="curso-footer">
        <?php if (!$activo): ?>
          <span class="badge-disponible badge-pronto"><?= $lbl['proxim'] ?></span><br>
          <button class="btn-curso btn-curso-deshabilitado" disabled><?= $lbl['proxim'] ?></button>
        <?php elseif ($curso['es_premium'] && $user['plan'] === 'free'): ?>
          <span class="badge-disponible badge-premium"><?= $lbl['premium'] ?></span><br>
          <button class="btn-curso btn-curso-deshabilitado" disabled>🔒 <?= $lbl['premium'] ?></button>
        <?php else: ?>
          <span class="badge-disponible badge-libre"><?= $lbl['disponible'] ?></span><br>
          <a href="/curso/<?= $curso['slug'] ?>" class="btn-curso btn-curso-activo"><?= $lbl['empezar'] ?> →</a>
        <?php endif; ?>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>
</body>
</html>
