<?php
$idioma  = $user['idioma'] ?? 'es';
$lbl = [
  'es' => [
    'bienvenido'  => 'Bienvenido',
    'continuar'   => 'Continuar aprendiendo',
    'nivel'       => 'Nivel',
    'racha'       => 'días seguidos',
    'obolos'      => 'Óbolos',
    'posicion'    => 'Posición global',
    'logros'      => 'Logros',
    'bloqueado'   => 'Bloqueado',
    'progreso'    => 'Tu progreso',
    'completado'  => 'Completado',
    'pendiente'   => 'Pendiente',
    'tienda'      => 'Tienda',
    'escudo'      => 'Escudo de racha',
    'escudo_desc' => 'Protege tu racha por un día',
    'corazon'     => 'Corazón extra',
    'corazon_desc'=> 'Un corazón adicional en ejercicios',
    'comprar'     => 'Comprar',
    'no_logros'   => 'Aún no tienes logros. ¡Completa tu primera lección!',
    'proximo'     => 'Próxima lección',
    'empezar'     => 'Empezar',
    'xp_semana'   => 'XP esta semana',
  ],
  'en' => [
    'bienvenido'  => 'Welcome',
    'continuar'   => 'Continue learning',
    'nivel'       => 'Level',
    'racha'       => 'day streak',
    'obolos'      => 'Obols',
    'posicion'    => 'Global rank',
    'logros'      => 'Achievements',
    'bloqueado'   => 'Locked',
    'progreso'    => 'Your progress',
    'completado'  => 'Completed',
    'pendiente'   => 'Pending',
    'tienda'      => 'Store',
    'escudo'      => 'Streak shield',
    'escudo_desc' => 'Protect your streak for one day',
    'corazon'     => 'Extra heart',
    'corazon_desc'=> 'One additional heart in exercises',
    'comprar'     => 'Buy',
    'no_logros'   => 'No achievements yet. Complete your first lesson!',
    'proximo'     => 'Next lesson',
    'empezar'     => 'Start',
    'xp_semana'   => 'XP this week',
  ],
  'pt' => [
    'bienvenido'  => 'Bem-vindo',
    'continuar'   => 'Continuar aprendendo',
    'nivel'       => 'Nível',
    'racha'       => 'dias seguidos',
    'obolos'      => 'Óbolos',
    'posicion'    => 'Posição global',
    'logros'      => 'Conquistas',
    'bloqueado'   => 'Bloqueado',
    'progreso'    => 'Seu progresso',
    'completado'  => 'Concluído',
    'pendiente'   => 'Pendente',
    'tienda'      => 'Loja',
    'escudo'      => 'Escudo de sequência',
    'escudo_desc' => 'Proteja sua sequência por um dia',
    'corazon'     => 'Coração extra',
    'corazon_desc'=> 'Um coração adicional nos exercícios',
    'comprar'     => 'Comprar',
    'no_logros'   => 'Ainda sem conquistas. Complete sua primeira lição!',
    'proximo'     => 'Próxima lição',
    'empezar'     => 'Começar',
    'xp_semana'   => 'XP esta semana',
  ],
  'fr' => [
    'bienvenido'  => 'Bienvenue',
    'continuar'   => 'Continuer à apprendre',
    'nivel'       => 'Niveau',
    'racha'       => 'jours consécutifs',
    'obolos'      => 'Oboles',
    'posicion'    => 'Classement mondial',
    'logros'      => 'Succès',
    'bloqueado'   => 'Verrouillé',
    'progreso'    => 'Votre progression',
    'completado'  => 'Terminé',
    'pendiente'   => 'En attente',
    'tienda'      => 'Boutique',
    'escudo'      => 'Bouclier de série',
    'escudo_desc' => 'Protégez votre série pour un jour',
    'corazon'     => 'Cœur supplémentaire',
    'corazon_desc'=> 'Un cœur additionnel dans les exercices',
    'comprar'     => 'Acheter',
    'no_logros'   => 'Pas encore de succès. Terminez votre première leçon !',
    'proximo'     => 'Prochaine leçon',
    'empezar'     => 'Commencer',
    'xp_semana'   => 'XP cette semaine',
  ],
][$idioma] ?? [];

$tit_key   = 'titulo_' . $idioma;
$nom_key   = 'nombre_' . $idioma;
$xp_total  = $exp['xp_total'] ?? 0;
$nivel     = $exp['nivel'] ?? 1;
$xp_semana = $exp['xp_semana'] ?? 0;
$racha_actual = $racha['racha_actual'] ?? 0;
$escudos   = $racha['escudos'] ?? 0;

// XP necesario para siguiente nivel (escala sqrt)
$xp_siguiente = (int)(pow(($nivel) * 10 / 3, 2));
$xp_actual_nivel = $xp_total - (int)(pow(($nivel - 1) * 10 / 3, 2));
$xp_para_nivel   = max(1, $xp_siguiente - (int)(pow(($nivel - 1) * 10 / 3, 2)));
$pct_nivel = min(100, round($xp_actual_nivel / $xp_para_nivel * 100));

$logros_ids = array_column($logros_obtenidos, 'id');

// Tienda items
$tienda = [
    ['id'=>'escudo',  'icono'=>'🛡', 'nombre'=>$lbl['escudo'],  'desc'=>$lbl['escudo_desc'],  'precio'=>100],
    ['id'=>'corazon', 'icono'=>'❤', 'nombre'=>$lbl['corazon'], 'desc'=>$lbl['corazon_desc'], 'precio'=>50],
];
?>
<!DOCTYPE html>
<html lang="<?= $idioma ?>">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Dashboard — Koinízate</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=Noto+Sans:wght@400;500&display=swap" rel="stylesheet">
<style>
*{box-sizing:border-box;margin:0;padding:0}
:root{--egeo:#1B3A5C;--egeo-light:#2A5280;--gold:#C9A84C;--gold-light:#E8CC7A;--ivory:#F5F0E8;--ivory-dark:#EAE3D5;--terra:#8B4A3A;--stone:#5A6473;--ink:#1A1614;--verde:#2D6A4F;--serif:'Cormorant Garamond',serif;--sans:'Noto Sans',sans-serif}
body{background:var(--ivory);font-family:var(--sans);min-height:100vh}
.mosaic{height:10px;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='42' height='10'%3E%3Crect x='0' width='12' height='10' fill='%231B3A5C'/%3E%3Crect x='12' width='2' height='10' fill='%23C9A84C'/%3E%3Crect x='14' width='12' height='10' fill='%23EAE3D5'/%3E%3Crect x='26' width='2' height='10' fill='%238B4A3A'/%3E%3Crect x='28' width='12' height='10' fill='%23EAE3D5'/%3E%3Crect x='40' width='2' height='10' fill='%23C9A84C'/%3E%3C/svg%3E");background-repeat:repeat-x;background-size:42px 10px}

nav{background:var(--egeo);padding:0 2rem;height:56px;display:flex;align-items:center;justify-content:space-between}
.nav-logo{font-family:var(--serif);font-size:1.5rem;font-weight:700;color:var(--gold);text-decoration:none}
.nav-right{display:flex;align-items:center;gap:1.5rem}
.nav-link{color:rgba(245,240,232,.5);text-decoration:none;font-size:.78rem;font-family:var(--sans)}
.nav-link:hover{color:var(--gold)}
.nav-obolos{font-family:var(--serif);font-size:1rem;color:var(--gold);font-weight:600}

.container{max-width:1000px;margin:0 auto;padding:2.5rem 1.5rem}

/* Header del usuario */
.user-header{display:grid;grid-template-columns:auto 1fr auto;gap:2rem;align-items:center;background:#fff;border:1px solid var(--ivory-dark);padding:1.8rem 2rem;margin-bottom:2rem;border-top:4px solid var(--egeo)}
.user-avatar{width:64px;height:64px;border-radius:50%;background:var(--egeo);display:flex;align-items:center;justify-content:center;font-family:var(--serif);font-size:1.8rem;font-weight:700;color:var(--gold);flex-shrink:0}
.user-info h1{font-family:var(--serif);font-size:1.8rem;font-weight:700;color:var(--egeo);line-height:1}
.user-info p{font-family:var(--sans);font-size:.78rem;color:var(--stone);margin-top:.3rem}
.user-nivel{text-align:right}
.nivel-num{font-family:var(--serif);font-size:2.5rem;font-weight:700;color:var(--egeo);line-height:1}
.nivel-label{font-family:var(--sans);font-size:.68rem;color:var(--stone);letter-spacing:.1em;text-transform:uppercase}
.nivel-barra{width:120px;height:4px;background:var(--ivory-dark);margin-top:.5rem;border-radius:2px;overflow:hidden}
.nivel-fill{height:100%;background:var(--gold);transition:width .5s}

/* Stats row */
.stats-row{display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:2rem}
.stat-card{background:#fff;border:1px solid var(--ivory-dark);padding:1.2rem;text-align:center}
.stat-num{font-family:var(--serif);font-size:2rem;font-weight:700;color:var(--egeo);line-height:1}
.stat-num.gold{color:var(--gold)}
.stat-num.terra{color:var(--terra)}
.stat-label{font-family:var(--sans);font-size:.68rem;color:var(--stone);letter-spacing:.08em;text-transform:uppercase;margin-top:.3rem}

/* Grid principal */
.main-grid{display:grid;grid-template-columns:1fr 320px;gap:2rem}

/* Próxima lección */
.proximo-card{background:var(--egeo);padding:2rem;margin-bottom:1.5rem;display:flex;align-items:center;justify-content:space-between;gap:1.5rem}
.proximo-info p{font-family:var(--sans);font-size:.72rem;letter-spacing:.12em;color:rgba(245,240,232,.5);text-transform:uppercase;margin-bottom:.3rem}
.proximo-info h2{font-family:var(--serif);font-size:1.6rem;font-weight:700;color:var(--ivory)}
.proximo-info .gr{font-family:var(--serif);font-style:italic;color:rgba(245,240,232,.45);font-size:.95rem;margin-top:.2rem}
.btn-proximo{background:var(--gold);color:var(--egeo);font-family:var(--sans);font-size:.85rem;font-weight:500;padding:.75rem 1.5rem;text-decoration:none;white-space:nowrap;letter-spacing:.04em;flex-shrink:0;transition:background .2s}
.btn-proximo:hover{background:var(--gold-light)}

/* Progreso capítulos */
.seccion-titulo{font-family:var(--serif);font-size:1.3rem;font-weight:700;color:var(--egeo);margin-bottom:1rem}
.cap-progreso-item{display:flex;align-items:center;gap:.8rem;padding:.7rem 0;border-bottom:1px solid var(--ivory-dark)}
.cap-progreso-item:last-child{border-bottom:none}
.cap-dot{width:10px;height:10px;border-radius:50%;background:var(--ivory-dark);flex-shrink:0}
.cap-dot.hecho{background:var(--gold)}
.cap-dot.activo{background:var(--egeo)}
.cap-nombre{font-family:var(--sans);font-size:.85rem;color:var(--ink);flex:1}
.cap-xp{font-family:var(--serif);font-size:.85rem;color:var(--stone)}

/* Panel lateral */
/* Logros */
.logros-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:.6rem;margin-bottom:1.5rem}
.logro-item{background:#fff;border:1px solid var(--ivory-dark);padding:.8rem .5rem;text-align:center;position:relative}
.logro-item.bloqueado{opacity:.4;filter:grayscale(1)}
.logro-icono{font-size:1.3rem;margin-bottom:.3rem}
.logro-nombre{font-family:var(--serif);font-size:.75rem;color:var(--egeo);line-height:1.2}
.logro-gr{font-family:var(--serif);font-style:italic;font-size:.7rem;color:var(--stone)}

/* Tienda */
.tienda-item{background:#fff;border:1px solid var(--ivory-dark);padding:1rem;display:flex;align-items:center;gap:.8rem;margin-bottom:.6rem}
.tienda-icono{font-size:1.5rem;flex-shrink:0}
.tienda-info{flex:1}
.tienda-nombre{font-family:var(--sans);font-size:.85rem;font-weight:500;color:var(--ink)}
.tienda-desc{font-family:var(--sans);font-size:.72rem;color:var(--stone);margin-top:.1rem}
.tienda-precio{font-family:var(--serif);font-size:.9rem;font-weight:700;color:var(--gold);white-space:nowrap}
.btn-comprar{background:var(--egeo);color:var(--ivory);font-family:var(--sans);font-size:.72rem;padding:.35rem .7rem;border:none;cursor:pointer;letter-spacing:.04em;transition:background .2s;white-space:nowrap}
.btn-comprar:hover{background:var(--egeo-light)}
.btn-comprar:disabled{background:var(--stone);cursor:not-allowed}

.seccion-sep{margin-bottom:1.5rem}

@media(max-width:700px){
  .stats-row{grid-template-columns:repeat(2,1fr)}
  .main-grid{grid-template-columns:1fr}
  .user-header{grid-template-columns:auto 1fr;gap:1rem}
  .user-nivel{display:none}
}
</style>
</head>
<body>
<div class="mosaic"></div>
<nav>
  <a href="/" class="nav-logo">Κοινίζατε</a>
  <div class="nav-right">
    <span class="nav-obolos">⊙ <?= number_format($obolos) ?></span>
    <a href="/ranking" class="nav-link">Ranking</a>
    <a href="/ranking" class="nav-link"><?= $idioma==='es'?'Ranking':'Ranking' ?></a>
    <a href="/cursos" class="nav-link"><?= $idioma==='es'?'Cursos':($idioma==='en'?'Courses':($idioma==='pt'?'Cursos':'Cours')) ?></a>
    <a href="/logout" class="nav-link"><?= $idioma==='es'?'Salir':($idioma==='en'?'Sign out':($idioma==='pt'?'Sair':'Quitter')) ?></a>
  </div>
</nav>

<div class="container">

  <!-- Header usuario -->
  <div class="user-header">
    <div class="user-avatar"><?= mb_strtoupper(mb_substr($user['nombre'], 0, 1)) ?></div>
    <div class="user-info">
      <h1><?= htmlspecialchars($user['nombre'] . ' ' . $user['apellido']) ?></h1>
      <p><?= $lbl['bienvenido'] ?> · <?= $user['plan'] === 'premium' ? 'Πλήρης' : 'Libre' ?><?= $user['pais'] ? ' · ' . $user['pais'] : '' ?></p>
    </div>
    <div class="user-nivel">
      <div class="nivel-num"><?= $nivel ?></div>
      <div class="nivel-label"><?= $lbl['nivel'] ?></div>
      <div class="nivel-barra"><div class="nivel-fill" style="width:<?= $pct_nivel ?>%"></div></div>
    </div>
  </div>

  <!-- Stats -->
  <div class="stats-row">
    <div class="stat-card">
      <div class="stat-num gold"><?= number_format($xp_total) ?></div>
      <div class="stat-label">XP <?= $lbl['nivel'] ?></div>
    </div>
    <div class="stat-card">
      <div class="stat-num"><?= number_format($xp_semana) ?></div>
      <div class="stat-label"><?= $lbl['xp_semana'] ?></div>
    </div>
    <div class="stat-card">
      <div class="stat-num terra">🔥 <?= $racha_actual ?></div>
      <div class="stat-label"><?= $lbl['racha'] ?></div>
    </div>
    <div class="stat-card">
      <div class="stat-num">#<?= $mi_posicion ?></div>
      <div class="stat-label"><?= $lbl['posicion'] ?></div>
    </div>
  </div>

  <div class="main-grid">

    <!-- Columna principal -->
    <div>

      <!-- Próxima lección -->
      <?php if ($proximo): ?>
      <div class="proximo-card">
        <div class="proximo-info">
          <p><?= $lbl['proximo'] ?></p>
          <h2><?= htmlspecialchars($proximo[$tit_key] ?? $proximo['titulo_es']) ?></h2>
          <p class="gr">Κεφάλαιον <?= $proximo['numero'] ?></p>
        </div>
        <a href="/leccion/<?= $proximo['slug'] ?>" class="btn-proximo"><?= $lbl['empezar'] ?> →</a>
      </div>
      <?php endif; ?>

      <!-- Progreso -->
      <div class="seccion-sep">
        <p class="seccion-titulo"><?= $lbl['progreso'] ?></p>
        <?php foreach ($capitulos as $cap): ?>
        <div class="cap-progreso-item">
          <div class="cap-dot <?= $cap['completado'] ? 'hecho' : ($proximo && $cap['id'] == $proximo['id'] ? 'activo' : '') ?>"></div>
          <span class="cap-nombre"><?= htmlspecialchars($cap[$tit_key] ?? $cap['titulo_es']) ?></span>
          <span class="cap-xp"><?= $cap['completado'] ? '+' . $cap['xp_ganado'] . ' XP' : $lbl['pendiente'] ?></span>
        </div>
        <?php endforeach; ?>
      </div>

    </div>

    <!-- Panel lateral -->
    <div>

      <!-- Logros -->
      <div class="seccion-sep">
        <p class="seccion-titulo"><?= $lbl['logros'] ?></p>
        <?php if (empty($logros_obtenidos) && empty($todos_logros)): ?>
          <p style="font-family:var(--sans);font-size:.85rem;color:var(--stone)"><?= $lbl['no_logros'] ?></p>
        <?php else: ?>
        <div class="logros-grid">
          <?php foreach ($todos_logros as $logro):
            $obtenido = in_array($logro['id'], $logros_ids);
            $nom_key_l = 'nombre_' . $idioma;
          ?>
          <div class="logro-item <?= !$obtenido ? 'bloqueado' : '' ?>" title="<?= htmlspecialchars($logro[$nom_key_l] ?? $logro['nombre_es']) ?>">
            <div class="logro-icono"><?= $obtenido ? '🏆' : '🔒' ?></div>
            <div class="logro-gr"><?= htmlspecialchars($logro['nombre_griego']) ?></div>
            <div class="logro-nombre"><?= htmlspecialchars($logro[$nom_key_l] ?? $logro['nombre_es']) ?></div>
          </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>

      <!-- Tienda de óbolos -->
      <div class="seccion-sep">
        <p class="seccion-titulo"><?= $lbl['tienda'] ?> <span style="font-family:var(--serif);font-style:italic;font-size:.9rem;color:var(--stone)">— ⊙ <?= number_format($obolos) ?> <?= $lbl['obolos'] ?></span></p>
        <?php foreach ($tienda as $item): ?>
        <div class="tienda-item">
          <div class="tienda-icono"><?= $item['icono'] ?></div>
          <div class="tienda-info">
            <div class="tienda-nombre"><?= htmlspecialchars($item['nombre']) ?></div>
            <div class="tienda-desc"><?= htmlspecialchars($item['desc']) ?></div>
          </div>
          <div style="display:flex;flex-direction:column;align-items:flex-end;gap:.3rem">
            <div class="tienda-precio">⊙ <?= $item['precio'] ?></div>
            <button class="btn-comprar"
              onclick="comprar('<?= $item['id'] ?>', <?= $item['precio'] ?>)"
              <?= $obolos < $item['precio'] ? 'disabled' : '' ?>>
              <?= $lbl['comprar'] ?>
            </button>
          </div>
        </div>
        <?php endforeach; ?>
      </div>

    </div>
  </div>
</div>

<script>
function comprar(tipo, precio) {
  if (!confirm('<?= $idioma==="es"?"¿Confirmas la compra?":($idioma==="en"?"Confirm purchase?":($idioma==="pt"?"Confirmar compra?":"Confirmer l'achat ?")) ?>')) return;

  fetch('/tienda/comprar', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({tipo, precio})
  })
  .then(r => r.json())
  .then(data => {
    if (data.success) {
      window.location.reload();
    } else {
      alert(data.message || 'Error');
    }
  });
}
</script>
</body>
</html>
