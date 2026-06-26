<?php
$labels = [
  'es'=>['empezar'=>'Empezar','continuar'=>'Continuar','completado'=>'Completado','bloqueado'=>'Bloqueado','libre'=>'Libre','volver'=>'← Cursos'],
  'en'=>['empezar'=>'Start','continuar'=>'Continue','completado'=>'Completed','bloqueado'=>'Locked','libre'=>'Free','volver'=>'← Courses'],
  'pt'=>['empezar'=>'Começar','continuar'=>'Continuar','completado'=>'Concluído','bloqueado'=>'Bloqueado','libre'=>'Livre','volver'=>'← Cursos'],
  'fr'=>['empezar'=>'Commencer','continuar'=>'Continuer','completado'=>'Terminé','bloqueado'=>'Verrouillé','libre'=>'Libre','volver'=>'← Cours'],
];
$lbl        = $labels[$idioma] ?? $labels['es'];
$nom_key    = 'nombre_' . $idioma;
$tit_key    = 'titulo_' . $idioma;
$curso_nom  = $curso[$nom_key] ?? $curso['nombre_es'];
$curso_gr   = $curso['nombre_gr'];
?>
<!DOCTYPE html>
<html lang="<?= $idioma ?>">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= htmlspecialchars($curso_nom) ?> — Koinízate</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,600;0,700&family=Noto+Sans:wght@400;500&display=swap" rel="stylesheet">
<style>
*{box-sizing:border-box;margin:0;padding:0}
:root{--egeo:#1B3A5C;--gold:#C9A84C;--ivory:#F5F0E8;--ivory-dark:#EAE3D5;--terra:#8B4A3A;--stone:#5A6473;--ink:#1A1614;--serif:'Cormorant Garamond',serif;--sans:'Noto Sans',sans-serif}
body{background:var(--ivory);font-family:var(--sans)}
.mosaic{height:10px;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='42' height='10'%3E%3Crect x='0' width='12' height='10' fill='%231B3A5C'/%3E%3Crect x='12' width='2' height='10' fill='%23C9A84C'/%3E%3Crect x='14' width='12' height='10' fill='%23EAE3D5'/%3E%3Crect x='26' width='2' height='10' fill='%238B4A3A'/%3E%3Crect x='28' width='12' height='10' fill='%23EAE3D5'/%3E%3Crect x='40' width='2' height='10' fill='%23C9A84C'/%3E%3C/svg%3E");background-repeat:repeat-x;background-size:42px 10px}
nav{background:var(--egeo);padding:0 2rem;height:56px;display:flex;align-items:center;justify-content:space-between}
.nav-logo{font-family:var(--serif);font-size:1.5rem;font-weight:700;color:var(--gold);text-decoration:none}
.nav-back{color:rgba(245,240,232,.6);font-size:.82rem;text-decoration:none}
.nav-back:hover{color:var(--gold)}
.nav-user{font-family:var(--sans);font-size:.78rem;color:rgba(245,240,232,.5)}
.hero{background:var(--egeo);padding:2.5rem 2rem;text-align:center}
.hero-gr{font-family:var(--serif);font-style:italic;font-size:1rem;color:rgba(245,240,232,.45);margin-bottom:.3rem}
.hero h1{font-family:var(--serif);font-size:2.2rem;font-weight:700;color:var(--ivory)}
.container{max-width:860px;margin:0 auto;padding:2.5rem 1.5rem}
.grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:1.5rem}
.cap-card{background:#fff;border:1px solid var(--ivory-dark);padding:1.8rem;transition:border-color .2s}
.cap-card:hover:not(.bloqueado-card){border-color:var(--egeo)}
.cap-card.completado-card{border-left:4px solid var(--gold)}
.cap-card.bloqueado-card{opacity:.6}
.cap-numero{font-family:var(--sans);font-size:.7rem;letter-spacing:.14em;color:var(--stone);text-transform:uppercase;margin-bottom:.4rem}
.cap-titulo{font-family:var(--serif);font-size:1.4rem;font-weight:700;color:var(--egeo);margin-bottom:.2rem}
.cap-titulo-gr{font-family:var(--serif);font-style:italic;color:var(--stone);font-size:.95rem;margin-bottom:1rem}
.badge{display:inline-block;font-family:var(--sans);font-size:.65rem;letter-spacing:.08em;text-transform:uppercase;padding:.15rem .5rem;margin-bottom:1rem}
.badge-libre{background:rgba(27,58,92,.08);color:var(--egeo)}
.badge-completado{background:rgba(201,168,76,.2);color:#7a5c0a}
.btn-cap{display:block;width:100%;text-align:center;background:var(--egeo);color:var(--ivory);font-family:var(--sans);font-size:.82rem;font-weight:500;padding:.7rem;text-decoration:none;letter-spacing:.04em;transition:background .2s;border:none;cursor:pointer}
.btn-cap:hover{background:#2A5280}
.btn-bloqueado{background:var(--stone);cursor:not-allowed}
</style>
</head>
<body>
<div class="mosaic"></div>
<nav>
  <a href="/cursos" class="nav-back"><?= $lbl['volver'] ?></a>
  <a href="/" class="nav-logo">Κοινίζατε</a>
  <span class="nav-user"><?= htmlspecialchars($user['nombre']) ?></span>
</nav>
<div class="hero">
  <p class="hero-gr"><?= htmlspecialchars($curso_gr) ?></p>
  <h1><?= htmlspecialchars($curso_nom) ?></h1>
</div>
<div class="mosaic" style="transform:scaleX(-1)"></div>
<div class="container">
  <div class="grid">
    <?php foreach ($capitulos as $cap):
      $titulo     = $cap[$tit_key] ?? $cap['titulo_es'];
      $completado = $cap['completado'];
      $bloqueado  = $cap['es_premium'] && $user['plan'] === 'free';
      $clases     = 'cap-card' . ($completado ? ' completado-card' : '') . ($bloqueado ? ' bloqueado-card' : '');
    ?>
    <div class="<?= $clases ?>">
      <p class="cap-numero">Κεφάλαιον <?= $cap['numero'] ?></p>
      <h2 class="cap-titulo"><?= htmlspecialchars($titulo) ?></h2>
      <p class="cap-titulo-gr">Ὁ Κόσμος</p>
      <?php if ($completado): ?>
        <span class="badge badge-completado">✓ <?= $lbl['completado'] ?></span>
      <?php else: ?>
        <span class="badge badge-libre"><?= $lbl['libre'] ?></span>
      <?php endif; ?>
      <?php if ($bloqueado): ?>
        <button class="btn-cap btn-bloqueado" disabled>🔒 <?= $lbl['bloqueado'] ?></button>
      <?php else: ?>
        <a href="/leccion/<?= $cap['slug'] ?>" class="btn-cap">
          <?= $completado ? $lbl['continuar'] : $lbl['empezar'] ?> →
        </a>
      <?php endif; ?>
    </div>
    <?php endforeach; ?>
  </div>
</div>
</body>
</html>
