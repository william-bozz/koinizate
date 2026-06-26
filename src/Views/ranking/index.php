<?php
$idioma = $user['idioma'] ?? 'es';
$lbl = [
  'es' => ['titulo'=>'Tabla de honor','semana'=>'Esta semana','mes'=>'Este mes','total'=>'Siempre','pais'=>'Mi país','mundial'=>'Mundial','nivel'=>'Nivel','racha'=>'Racha','xp'=>'XP','tu'=>'Tú','posicion'=>'Tu posición','premium'=>'Premium'],
  'en' => ['titulo'=>'Hall of honor','semana'=>'This week','mes'=>'This month','total'=>'All time','pais'=>'My country','mundial'=>'Global','nivel'=>'Level','racha'=>'Streak','xp'=>'XP','tu'=>'You','posicion'=>'Your position','premium'=>'Premium'],
  'pt' => ['titulo'=>'Tabela de honra','semana'=>'Esta semana','mes'=>'Este mês','total'=>'Sempre','pais'=>'Meu país','mundial'=>'Mundial','nivel'=>'Nível','racha'=>'Sequência','xp'=>'XP','tu'=>'Você','posicion'=>'Sua posição','premium'=>'Premium'],
  'fr' => ['titulo'=>'Tableau d\'honneur','semana'=>'Cette semaine','mes'=>'Ce mois','total'=>'Toujours','pais'=>'Mon pays','mundial'=>'Mondial','nivel'=>'Niveau','racha'=>'Série','xp'=>'XP','tu'=>'Vous','posicion'=>'Votre position','premium'=>'Premium'],
][$idioma] ?? [];

$periodo_actual = $_GET['periodo'] ?? 'semana';
$pais_actual    = $_GET['pais'] ?? '';

$nombres_pais = [
  'AR'=>'Argentina','BO'=>'Bolivia','BR'=>'Brasil','CL'=>'Chile','CO'=>'Colombia',
  'CR'=>'Costa Rica','CU'=>'Cuba','DO'=>'Rep. Dominicana','EC'=>'Ecuador',
  'SV'=>'El Salvador','GT'=>'Guatemala','HN'=>'Honduras','MX'=>'México',
  'NI'=>'Nicaragua','PA'=>'Panamá','PY'=>'Paraguay','PE'=>'Perú',
  'PR'=>'Puerto Rico','UY'=>'Uruguay','VE'=>'Venezuela',
  'ES'=>'España','US'=>'Estados Unidos','CA'=>'Canadá','FR'=>'Francia',
  'DE'=>'Alemania','IT'=>'Italia','PT'=>'Portugal','GB'=>'Reino Unido',
  'GR'=>'Grecia','NG'=>'Nigeria','KE'=>'Kenia','ZA'=>'Sudáfrica',
  'PH'=>'Filipinas','IN'=>'India','AU'=>'Australia',
];

$banderas = [
  'AR'=>'🇦🇷','BO'=>'🇧🇴','BR'=>'🇧🇷','CL'=>'🇨🇱','CO'=>'🇨🇴','CR'=>'🇨🇷',
  'CU'=>'🇨🇺','DO'=>'🇩🇴','EC'=>'🇪🇨','SV'=>'🇸🇻','GT'=>'🇬🇹','HN'=>'🇭🇳',
  'MX'=>'🇲🇽','NI'=>'🇳🇮','PA'=>'🇵🇦','PY'=>'🇵🇾','PE'=>'🇵🇪','PR'=>'🇵🇷',
  'UY'=>'🇺🇾','VE'=>'🇻🇪','ES'=>'🇪🇸','US'=>'🇺🇸','CA'=>'🇨🇦','FR'=>'🇫🇷',
  'DE'=>'🇩🇪','IT'=>'🇮🇹','PT'=>'🇵🇹','GB'=>'🇬🇧','GR'=>'🇬🇷','NG'=>'🇳🇬',
  'KE'=>'🇰🇪','ZA'=>'🇿🇦','PH'=>'🇵🇭','IN'=>'🇮🇳','AU'=>'🇦🇺',
];
?>
<!DOCTYPE html>
<html lang="<?= $idioma ?>">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= $lbl['titulo'] ?> — Koinízate</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700&family=Noto+Sans:wght@400;500&display=swap" rel="stylesheet">
<style>
*{box-sizing:border-box;margin:0;padding:0}
:root{--egeo:#1B3A5C;--egeo-light:#2A5280;--gold:#C9A84C;--gold-light:#E8CC7A;--ivory:#F5F0E8;--ivory-dark:#EAE3D5;--terra:#8B4A3A;--stone:#5A6473;--ink:#1A1614;--serif:'Cormorant Garamond',serif;--sans:'Noto Sans',sans-serif}
body{background:var(--ivory);font-family:var(--sans);min-height:100vh}
.mosaic{height:10px;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='42' height='10'%3E%3Crect x='0' width='12' height='10' fill='%231B3A5C'/%3E%3Crect x='12' width='2' height='10' fill='%23C9A84C'/%3E%3Crect x='14' width='12' height='10' fill='%23EAE3D5'/%3E%3Crect x='26' width='2' height='10' fill='%238B4A3A'/%3E%3Crect x='28' width='12' height='10' fill='%23EAE3D5'/%3E%3Crect x='40' width='2' height='10' fill='%23C9A84C'/%3E%3C/svg%3E");background-repeat:repeat-x;background-size:42px 10px}
nav{background:var(--egeo);padding:0 2rem;height:56px;display:flex;align-items:center;justify-content:space-between}
.nav-logo{font-family:var(--serif);font-size:1.5rem;font-weight:700;color:var(--gold);text-decoration:none}
.nav-right{display:flex;gap:1.5rem;align-items:center}
.nav-link{color:rgba(245,240,232,.5);text-decoration:none;font-size:.78rem;font-family:var(--sans)}
.nav-link:hover{color:var(--gold)}

.hero{background:var(--egeo);padding:2.5rem 2rem;text-align:center}
.hero h1{font-family:var(--serif);font-size:2.5rem;font-weight:700;color:var(--ivory);margin-bottom:.3rem}
.hero-gr{font-family:var(--serif);font-style:italic;color:rgba(245,240,232,.4);font-size:1rem}

.container{max-width:760px;margin:0 auto;padding:2rem 1.5rem}

/* Filtros */
.filtros{display:flex;gap:.5rem;margin-bottom:2rem;flex-wrap:wrap;align-items:center}
.filtro-btn{font-family:var(--sans);font-size:.78rem;padding:.4rem .9rem;border:1px solid var(--ivory-dark);background:#fff;color:var(--stone);cursor:pointer;text-decoration:none;transition:all .2s;letter-spacing:.03em}
.filtro-btn:hover,.filtro-btn.activo{background:var(--egeo);color:var(--ivory);border-color:var(--egeo)}
.filtro-sep{color:var(--ivory-dark);margin:0 .3rem}
.filtro-pais{font-family:var(--sans);font-size:.78rem;padding:.4rem .8rem;border:1px solid var(--ivory-dark);background:#fff;color:var(--stone);cursor:pointer;appearance:none;outline:none}

/* Top 3 */
.top3{display:grid;grid-template-columns:1fr 1.2fr 1fr;gap:1rem;margin-bottom:2rem;align-items:end}
.top-card{background:#fff;border:1px solid var(--ivory-dark);padding:1.5rem 1rem;text-align:center;position:relative}
.top-card.pos-1{border-top:4px solid var(--gold);background:linear-gradient(180deg,rgba(201,168,76,.06) 0%,#fff 100%)}
.top-card.pos-2{border-top:3px solid #9BA4B4}
.top-card.pos-3{border-top:3px solid #A0714F}
.top-medalla{font-size:1.8rem;margin-bottom:.5rem;display:block}
.top-avatar{width:52px;height:52px;border-radius:50%;background:var(--egeo);display:flex;align-items:center;justify-content:center;font-family:var(--serif);font-size:1.3rem;font-weight:700;color:var(--gold);margin:0 auto .6rem}
.top-nombre{font-family:var(--sans);font-size:.85rem;font-weight:500;color:var(--ink);margin-bottom:.2rem}
.top-pais{font-size:.85rem;margin-bottom:.4rem}
.top-xp{font-family:var(--serif);font-size:1.3rem;font-weight:700;color:var(--egeo)}
.top-xp-label{font-family:var(--sans);font-size:.65rem;color:var(--stone);letter-spacing:.08em;text-transform:uppercase}
.top-racha{font-family:var(--sans);font-size:.72rem;color:var(--stone);margin-top:.3rem}

/* Lista ranking */
.ranking-lista{background:#fff;border:1px solid var(--ivory-dark)}
.rk-row{display:flex;align-items:center;gap:.9rem;padding:.85rem 1.2rem;border-bottom:1px solid var(--ivory-dark);transition:background .15s}
.rk-row:last-child{border-bottom:none}
.rk-row:hover{background:rgba(27,58,92,.02)}
.rk-row.yo{background:rgba(201,168,76,.07);border-left:3px solid var(--gold)}
.rk-pos{font-family:var(--serif);font-size:1rem;font-weight:700;color:var(--stone);width:28px;text-align:center;flex-shrink:0}
.rk-pos.oro{color:var(--gold)}
.rk-pos.plata{color:#9BA4B4}
.rk-pos.bronce{color:#A0714F}
.rk-avatar{width:36px;height:36px;border-radius:50%;background:var(--egeo);display:flex;align-items:center;justify-content:center;font-family:var(--serif);font-weight:700;color:var(--gold);font-size:.95rem;flex-shrink:0}
.rk-avatar.premium-av{background:var(--gold);color:var(--egeo)}
.rk-info{flex:1;min-width:0}
.rk-nombre{font-family:var(--sans);font-size:.88rem;font-weight:500;color:var(--ink);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.rk-det{font-family:var(--sans);font-size:.72rem;color:var(--stone);margin-top:.1rem}
.rk-xp{font-family:var(--serif);font-size:1rem;font-weight:700;color:var(--egeo);white-space:nowrap}
.rk-nivel{font-family:var(--sans);font-size:.68rem;color:var(--stone);text-align:right;margin-top:.1rem}

/* Separador "..." */
.rk-sep{text-align:center;padding:.6rem;font-family:var(--serif);color:var(--stone);font-size:1.2rem;border-bottom:1px solid var(--ivory-dark)}

/* Tu posición fija abajo */
.mi-posicion-fija{position:sticky;bottom:1rem;margin-top:1rem}
.mi-pos-card{background:var(--egeo);color:var(--ivory);padding:.9rem 1.2rem;display:flex;align-items:center;gap:.9rem;border-top:3px solid var(--gold)}
.mi-pos-num{font-family:var(--serif);font-size:1.5rem;font-weight:700;color:var(--gold)}
.mi-pos-info{flex:1;font-family:var(--sans);font-size:.85rem}
.mi-pos-xp{font-family:var(--serif);font-size:1rem;font-weight:700;color:var(--gold-light)}
</style>
</head>
<body>
<div class="mosaic"></div>
<nav>
  <a href="/dashboard" class="nav-link">← Dashboard</a>
  <a href="/" class="nav-logo">Κοινίζατε</a>
  <div class="nav-right">
    <a href="/cursos" class="nav-link"><?= $idioma==='es'?'Cursos':($idioma==='en'?'Courses':($idioma==='pt'?'Cursos':'Cours')) ?></a>
    <a href="/logout" class="nav-link"><?= $idioma==='es'?'Salir':($idioma==='en'?'Sign out':($idioma==='pt'?'Sair':'Quitter')) ?></a>
  </div>
</nav>

<div class="hero">
  <h1><?= $lbl['titulo'] ?></h1>
  <p class="hero-gr">Τίς ἐστὶν ὁ πρῶτος;</p>
</div>
<div class="mosaic" style="transform:scaleX(-1)"></div>

<div class="container">

  <!-- Filtros -->
  <div class="filtros">
    <a href="?periodo=semana<?= $pais_actual ? '&pais='.$pais_actual : '' ?>" class="filtro-btn <?= $periodo_actual==='semana'?'activo':'' ?>"><?= $lbl['semana'] ?></a>
    <a href="?periodo=mes<?= $pais_actual ? '&pais='.$pais_actual : '' ?>" class="filtro-btn <?= $periodo_actual==='mes'?'activo':'' ?>"><?= $lbl['mes'] ?></a>
    <a href="?periodo=total<?= $pais_actual ? '&pais='.$pais_actual : '' ?>" class="filtro-btn <?= $periodo_actual==='total'?'activo':'' ?>"><?= $lbl['total'] ?></a>
    <span class="filtro-sep">|</span>
    <select class="filtro-pais" onchange="filtrarPais(this.value)">
      <option value=""><?= $lbl['mundial'] ?></option>
      <?php foreach ($paises_disponibles as $p): ?>
        <option value="<?= $p['pais'] ?>" <?= $pais_actual === $p['pais'] ? 'selected' : '' ?>>
          <?= ($banderas[$p['pais']] ?? '') . ' ' . ($nombres_pais[$p['pais']] ?? $p['pais']) ?> (<?= $p['total'] ?>)
        </option>
      <?php endforeach; ?>
    </select>
  </div>

  <!-- Top 3 -->
  <?php if (count($ranking) >= 3):
    $medallas = ['🥇','🥈','🥉'];
    $clases   = ['pos-1','pos-2','pos-3'];
    $orden_visual = [1, 0, 2]; // plata izq, oro centro, bronce der
  ?>
  <div class="top3">
    <?php foreach ($orden_visual as $idx):
      $r = $ranking[$idx];
      $es_yo = (int)$r['id'] === (int)$user['id'];
    ?>
    <div class="top-card <?= $clases[$idx] ?>">
      <span class="top-medalla"><?= $medallas[$idx] ?></span>
      <div class="top-avatar <?= $r['plan']==='premium'?'premium-av':'' ?>">
        <?= mb_strtoupper(mb_substr($r['nombre'], 0, 1)) ?>
      </div>
      <div class="top-nombre"><?= htmlspecialchars($r['nombre']) ?><?= $es_yo ? ' (' . $lbl['tu'] . ')' : '' ?></div>
      <div class="top-pais"><?= $banderas[$r['pais']] ?? '' ?></div>
      <div class="top-xp"><?= number_format($r['xp_periodo']) ?></div>
      <div class="top-xp-label"><?= $lbl['xp'] ?></div>
      <div class="top-racha">🔥 <?= $r['racha_actual'] ?> <?= $lbl['racha'] ?></div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

  <!-- Lista completa -->
  <div class="ranking-lista">
    <?php
    $mostro_sep  = false;
    $mi_en_lista = false;
    foreach ($ranking as $i => $r):
      $pos    = (int)$r['posicion'];
      $es_yo  = (int)$r['id'] === (int)$user['id'];
      if ($es_yo) $mi_en_lista = true;
      if ($i < 3) continue; // Ya están en el top3

      $cls_pos = match($pos) { 1=>'oro', 2=>'plata', 3=>'bronce', default=>'' };
    ?>
    <div class="rk-row <?= $es_yo ? 'yo' : '' ?>">
      <div class="rk-pos <?= $cls_pos ?>"><?= $pos ?></div>
      <div class="rk-avatar <?= $r['plan']==='premium'?'premium-av':'' ?>">
        <?= mb_strtoupper(mb_substr($r['nombre'], 0, 1)) ?>
      </div>
      <div class="rk-info">
        <div class="rk-nombre">
          <?= htmlspecialchars($r['nombre'] . ' ' . mb_substr($r['apellido'],0,1)) ?>.
          <?= $es_yo ? '<strong style="color:var(--terra)"> ← ' . $lbl['tu'] . '</strong>' : '' ?>
        </div>
        <div class="rk-det">
          <?= $banderas[$r['pais']] ?? '' ?>
          <?= $lbl['nivel'] ?> <?= $r['nivel'] ?> ·
          🔥 <?= $r['racha_actual'] ?>
          <?= $r['plan']==='premium' ? ' · <span style="color:var(--gold)">Πλήρης</span>' : '' ?>
        </div>
      </div>
      <div>
        <div class="rk-xp"><?= number_format($r['xp_periodo']) ?> <?= $lbl['xp'] ?></div>
        <div class="rk-nivel"><?= $lbl['nivel'] ?> <?= $r['nivel'] ?></div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <!-- Tu posición si no está en el top 50 -->
  <?php if (!$mi_en_lista): ?>
  <div class="mi-posicion-fija">
    <div class="mi-pos-card">
      <div class="mi-pos-num">#<?= $mi_posicion ?></div>
      <div class="mi-pos-info">
        <?= htmlspecialchars($user['nombre'] . ' ' . $user['apellido']) ?>
        · <?= $lbl['nivel'] ?> <?= $exp_nivel ?? 1 ?>
      </div>
      <div class="mi-pos-xp"><?= number_format($exp_xp ?? 0) ?> <?= $lbl['xp'] ?></div>
    </div>
  </div>
  <?php endif; ?>

</div>

<script>
function filtrarPais(pais) {
  const url = new URL(window.location);
  if (pais) url.searchParams.set('pais', pais);
  else url.searchParams.delete('pais');
  window.location.href = url.toString();
}
</script>
</body>
</html>
