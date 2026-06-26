<?php
$idiomas = ['es' => 'Español', 'en' => 'English', 'pt' => 'Português', 'fr' => 'Français'];
$motivos = [
    'es' => [
        'biblia'     => 'Leer el Nuevo Testamento en griego original',
        'teologia'   => 'Estudios teológicos o seminario',
        'historia'   => 'Interés en la historia y cultura antigua',
        'linguistica'=> 'Curiosidad lingüística',
        'otro'       => 'Otro motivo',
    ],
    'en' => [
        'biblia'     => 'Read the New Testament in original Greek',
        'teologia'   => 'Theological studies or seminary',
        'historia'   => 'Interest in ancient history and culture',
        'linguistica'=> 'Linguistic curiosity',
        'otro'       => 'Other reason',
    ],
    'pt' => [
        'biblia'     => 'Ler o Novo Testamento em grego original',
        'teologia'   => 'Estudos teológicos ou seminário',
        'historia'   => 'Interesse na história e cultura antigas',
        'linguistica'=> 'Curiosidade linguística',
        'outro'      => 'Outro motivo',
    ],
    'fr' => [
        'biblia'     => 'Lire le Nouveau Testament en grec original',
        'teologia'   => 'Études théologiques ou séminaire',
        'historia'   => 'Intérêt pour l\'histoire et la culture antiques',
        'linguistica'=> 'Curiosité linguistique',
        'autre'      => 'Autre raison',
    ],
];
$paises = [
    'AR'=>'Argentina','BO'=>'Bolivia','BR'=>'Brasil','CL'=>'Chile','CO'=>'Colombia',
    'CR'=>'Costa Rica','CU'=>'Cuba','DO'=>'Rep. Dominicana','EC'=>'Ecuador',
    'SV'=>'El Salvador','GT'=>'Guatemala','HN'=>'Honduras','MX'=>'México',
    'NI'=>'Nicaragua','PA'=>'Panamá','PY'=>'Paraguay','PE'=>'Perú',
    'PR'=>'Puerto Rico','UY'=>'Uruguay','VE'=>'Venezuela',
    'ES'=>'España','US'=>'Estados Unidos','CA'=>'Canadá','FR'=>'Francia',
    'DE'=>'Alemania','IT'=>'Italia','PT'=>'Portugal','GB'=>'Reino Unido',
    'GR'=>'Grecia','NG'=>'Nigeria','KE'=>'Kenia','ZA'=>'Sudáfrica',
    'PH'=>'Filipinas','IN'=>'India','AU'=>'Australia','OTHER'=>'Otro',
];
$error = $_SESSION['error'] ?? null;
unset($_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="es" id="html-root">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Crear cuenta — Koinízate</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=Noto+Sans:wght@400;500&display=swap" rel="stylesheet">
<style>
*{box-sizing:border-box;margin:0;padding:0}
:root{
  --egeo:#1B3A5C;--egeo-light:#2A5280;--gold:#C9A84C;--gold-light:#E8CC7A;
  --ivory:#F5F0E8;--ivory-dark:#EAE3D5;--terra:#8B4A3A;--stone:#5A6473;--ink:#1A1614;
  --serif:'Cormorant Garamond',serif;--sans:'Noto Sans',sans-serif;
}
body{background:var(--ivory);font-family:var(--sans);min-height:100vh;display:flex;flex-direction:column}
.mosaic{height:10px;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='42' height='10'%3E%3Crect x='0' width='12' height='10' fill='%231B3A5C'/%3E%3Crect x='12' width='2' height='10' fill='%23C9A84C'/%3E%3Crect x='14' width='12' height='10' fill='%23EAE3D5'/%3E%3Crect x='26' width='2' height='10' fill='%238B4A3A'/%3E%3Crect x='28' width='12' height='10' fill='%23EAE3D5'/%3E%3Crect x='40' width='2' height='10' fill='%23C9A84C'/%3E%3C/svg%3E");background-repeat:repeat-x;background-size:42px 10px}
nav{background:var(--egeo);padding:0 2rem;height:56px;display:flex;align-items:center;justify-content:space-between}
.nav-logo{font-family:var(--serif);font-size:1.5rem;font-weight:700;color:var(--gold);text-decoration:none}
.nav-logo span{color:rgba(245,240,232,.5);font-weight:400;font-style:italic;font-size:.85rem;margin-left:.4rem}
.lang-switcher{display:flex;gap:.3rem}
.lang-btn{background:transparent;border:1px solid rgba(255,255,255,.2);color:rgba(255,255,255,.6);font-family:var(--sans);font-size:.72rem;padding:.25rem .5rem;cursor:pointer;letter-spacing:.04em;transition:all .2s}
.lang-btn.active,.lang-btn:hover{border-color:var(--gold);color:var(--gold)}

.page{flex:1;display:flex;align-items:center;justify-content:center;padding:3rem 1rem}
.card{background:#fff;border:1px solid var(--ivory-dark);width:100%;max-width:540px;border-top:4px solid var(--egeo)}
.card-header{background:var(--egeo);padding:2rem 2.5rem 1.5rem;text-align:center}
.card-header h1{font-family:var(--serif);font-size:2rem;font-weight:600;color:var(--ivory);margin-bottom:.25rem}
.card-header p{font-family:var(--sans);font-size:.82rem;color:rgba(245,240,232,.55);font-style:italic}
.card-body{padding:2rem 2.5rem}

.error-box{background:#fdf2f2;border:1px solid #e8c4c4;border-left:4px solid var(--terra);padding:.9rem 1.2rem;margin-bottom:1.5rem;font-size:.85rem;color:var(--terra)}

.form-row{display:grid;grid-template-columns:1fr 1fr;gap:1rem}
.form-group{margin-bottom:1.2rem}
.form-group label{display:block;font-size:.75rem;font-weight:500;color:var(--stone);letter-spacing:.06em;text-transform:uppercase;margin-bottom:.4rem}
.form-group label .req{color:var(--terra)}
.form-group input,
.form-group select,
.form-group textarea{
  width:100%;padding:.7rem .9rem;border:1px solid var(--ivory-dark);
  background:var(--ivory);font-family:var(--sans);font-size:.9rem;color:var(--ink);
  outline:none;transition:border-color .2s;appearance:none;
}
.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus{border-color:var(--egeo);background:#fff}
.form-group textarea{resize:none;height:80px}
.select-wrap{position:relative}
.select-wrap::after{content:'▾';position:absolute;right:.9rem;top:50%;transform:translateY(-50%);color:var(--stone);pointer-events:none;font-size:.8rem}

.gender-group{display:grid;grid-template-columns:repeat(4,1fr);gap:.5rem}
.gender-btn{padding:.5rem .3rem;border:1px solid var(--ivory-dark);background:var(--ivory);font-family:var(--sans);font-size:.72rem;color:var(--stone);cursor:pointer;text-align:center;transition:all .2s}
.gender-btn:hover,.gender-btn.active{border-color:var(--egeo);background:var(--egeo);color:#fff}
input[name="genero"]{display:none}

.divider{border:none;border-top:1px solid var(--ivory-dark);margin:1.5rem 0}
.section-label{font-family:var(--serif);font-size:1.1rem;color:var(--egeo);font-weight:600;margin-bottom:1rem}

.btn-submit{width:100%;background:var(--egeo);color:var(--ivory);font-family:var(--sans);font-size:.9rem;font-weight:500;padding:.9rem;border:none;cursor:pointer;letter-spacing:.05em;transition:background .2s;margin-top:.5rem}
.btn-submit:hover{background:var(--egeo-light)}

.social-divider{display:flex;align-items:center;gap:1rem;margin:1.2rem 0}
.social-divider span{font-size:.75rem;color:var(--stone);white-space:nowrap}
.social-divider::before,.social-divider::after{content:'';flex:1;border-top:1px solid var(--ivory-dark)}
.btn-social{width:100%;padding:.75rem;border:1px solid var(--ivory-dark);background:#fff;font-family:var(--sans);font-size:.85rem;color:var(--ink);cursor:pointer;display:flex;align-items:center;justify-content:center;gap:.7rem;transition:border-color .2s;margin-bottom:.6rem}
.btn-social:hover{border-color:var(--egeo)}
.btn-social svg{width:18px;height:18px;flex-shrink:0}

.card-footer{padding:1.2rem 2.5rem;border-top:1px solid var(--ivory-dark);text-align:center;font-size:.82rem;color:var(--stone)}
.card-footer a{color:var(--egeo);text-decoration:none;font-weight:500}
.card-footer a:hover{color:var(--terra)}

.step-indicator{display:flex;align-items:center;justify-content:center;gap:.5rem;margin-bottom:1.5rem}
.step{width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.72rem;font-weight:500;border:1px solid var(--ivory-dark);color:var(--stone);background:var(--ivory)}
.step.active{background:var(--egeo);color:#fff;border-color:var(--egeo)}
.step.done{background:var(--gold);color:var(--egeo);border-color:var(--gold)}
.step-line{height:1px;width:32px;background:var(--ivory-dark)}
</style>
</head>
<body>
<div class="mosaic"></div>
<nav>
  <a href="/" class="nav-logo">Κοινίζατε <span>Koinízate</span></a>
  <div class="lang-switcher">
    <?php foreach($idiomas as $code => $label): ?>
      <button class="lang-btn" data-lang="<?= $code ?>"><?= strtoupper($code) ?></button>
    <?php endforeach; ?>
  </div>
</nav>

<div class="page">
  <div class="card">
    <div class="card-header">
      <h1 id="txt-titulo">Crear cuenta</h1>
      <p id="txt-subtitulo">Comienza tu camino con el griego koiné</p>
    </div>
    <div class="card-body">

      <?php if ($error): ?>
        <div class="error-box"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <div class="step-indicator">
        <div class="step active" id="step1-dot">1</div>
        <div class="step-line"></div>
        <div class="step" id="step2-dot">2</div>
        <div class="step-line"></div>
        <div class="step" id="step3-dot">3</div>
      </div>

      <form method="POST" action="/registro" id="registro-form">
        <input type="hidden" name="idioma" id="campo-idioma" value="es">
        <input type="hidden" name="genero" id="campo-genero" value="">

        <!-- Paso 1: Cuenta -->
        <div id="paso-1">
          <p class="section-label" id="txt-paso1">Datos de acceso</p>
          <div class="form-row">
            <div class="form-group">
              <label id="lbl-nombre">Nombre <span class="req">*</span></label>
              <input type="text" name="nombre" id="campo-nombre" placeholder="" required value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>">
            </div>
            <div class="form-group">
              <label id="lbl-apellido">Apellido <span class="req">*</span></label>
              <input type="text" name="apellido" id="campo-apellido" placeholder="" required value="<?= htmlspecialchars($_POST['apellido'] ?? '') ?>">
            </div>
          </div>
          <div class="form-group">
            <label id="lbl-email">Correo electrónico <span class="req">*</span></label>
            <input type="email" name="email" id="campo-email" placeholder="" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
          </div>
          <div class="form-group">
            <label id="lbl-password">Contraseña <span class="req">*</span></label>
            <input type="password" name="password" id="campo-password" placeholder="" required>
          </div>
          <button type="button" class="btn-submit" id="btn-paso1" onclick="irPaso(2)">
            <span id="txt-siguiente">Continuar</span> →
          </button>
          <div class="social-divider"><span id="txt-o">o regístrate con</span></div>
          <button type="button" class="btn-social" onclick="loginGoogle()">
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
            <span id="txt-google">Continuar con Google</span>
          </button>
        </div>

        <!-- Paso 2: Perfil -->
        <div id="paso-2" style="display:none">
          <p class="section-label" id="txt-paso2">Tu perfil</p>
          <div class="form-row">
            <div class="form-group">
              <label id="lbl-edad">Edad</label>
              <input type="number" name="edad" min="8" max="120" placeholder="30" value="<?= htmlspecialchars($_POST['edad'] ?? '') ?>">
            </div>
            <div class="form-group">
              <label id="lbl-pais">País</label>
              <div class="select-wrap">
                <select name="pais">
                  <option value="" id="opt-pais-default">— Selecciona —</option>
                  <?php foreach($paises as $code => $name): ?>
                    <option value="<?= $code ?>" <?= ($_POST['pais'] ?? '') === $code ? 'selected' : '' ?>><?= $name ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label id="lbl-genero">Género</label>
            <div class="gender-group">
              <button type="button" class="gender-btn" data-val="masculino" id="gbtn-m">♂ <span id="gtxt-m">Hombre</span></button>
              <button type="button" class="gender-btn" data-val="femenino"  id="gbtn-f">♀ <span id="gtxt-f">Mujer</span></button>
              <button type="button" class="gender-btn" data-val="otro"      id="gbtn-o">⚧ <span id="gtxt-o">Otro</span></button>
              <button type="button" class="gender-btn" data-val="prefiero_no_decir" id="gbtn-n">— <span id="gtxt-n">Prefiero no decir</span></button>
            </div>
          </div>
          <div style="display:flex;gap:1rem;margin-top:.5rem">
            <button type="button" class="btn-submit" style="background:var(--stone);width:auto;padding:.9rem 1.5rem" onclick="irPaso(1)">← <span id="txt-atras">Atrás</span></button>
            <button type="button" class="btn-submit" style="flex:1" onclick="irPaso(3)"><span id="txt-siguiente2">Continuar</span> →</button>
          </div>
        </div>

        <!-- Paso 3: Motivación -->
        <div id="paso-3" style="display:none">
          <p class="section-label" id="txt-paso3">¿Por qué aprendes griego?</p>
          <div class="form-group">
            <label id="lbl-motivo">Cuéntanos tu motivación</label>
            <div id="motivos-container" style="display:flex;flex-direction:column;gap:.5rem"></div>
            <input type="hidden" name="motivo" id="campo-motivo" value="">
          </div>
          <div style="display:flex;gap:1rem;margin-top:1rem">
            <button type="button" class="btn-submit" style="background:var(--stone);width:auto;padding:.9rem 1.5rem" onclick="irPaso(2)">← <span id="txt-atras2">Atrás</span></button>
            <button type="submit" class="btn-submit" style="flex:1" id="txt-crear">Crear mi cuenta →</button>
          </div>
        </div>

      </form>
    </div>
    <div class="card-footer">
      <span id="txt-ya-cuenta">¿Ya tienes cuenta?</span> <a href="/login" id="lnk-login">Iniciar sesión</a>
    </div>
  </div>
</div>

<script>
const t = {
  es:{titulo:'Crear cuenta',subtitulo:'Comienza tu camino con el griego koiné',paso1:'Datos de acceso',paso2:'Tu perfil',paso3:'¿Por qué aprendes griego?',nombre:'Nombre',apellido:'Apellido',email:'Correo electrónico',password:'Contraseña',edad:'Edad',pais:'País',genero:'Género',motivo:'Cuéntanos tu motivación',siguiente:'Continuar',atras:'Atrás',crear:'Crear mi cuenta',o:'o regístrate con',google:'Continuar con Google',ya:'¿Ya tienes cuenta?',login:'Iniciar sesión',hombre:'Hombre',mujer:'Mujer',otro:'Otro',prefiero:'Prefiero no decir',pais_default:'— Selecciona —',
    motivos:{biblia:'Leer el Nuevo Testamento en griego original',teologia:'Estudios teológicos o seminario',historia:'Interés en la historia y cultura antigua',linguistica:'Curiosidad lingüística',otro:'Otro motivo'}},
  en:{titulo:'Create account',subtitulo:'Begin your journey with Koine Greek',paso1:'Access details',paso2:'Your profile',paso3:'Why are you learning Greek?',nombre:'First name',apellido:'Last name',email:'Email address',password:'Password',edad:'Age',pais:'Country',genero:'Gender',motivo:'Tell us your motivation',siguiente:'Continue',atras:'Back',crear:'Create my account',o:'or sign up with',google:'Continue with Google',ya:'Already have an account?',login:'Sign in',hombre:'Male',mujer:'Female',otro:'Other',prefiero:"Prefer not to say",pais_default:'— Select —',
    motivos:{biblia:'Read the New Testament in original Greek',teologia:'Theological studies or seminary',historia:'Interest in ancient history and culture',linguistica:'Linguistic curiosity',otro:'Other reason'}},
  pt:{titulo:'Criar conta',subtitulo:'Comece sua jornada com o grego coinê',paso1:'Dados de acesso',paso2:'Seu perfil',paso3:'Por que você aprende grego?',nombre:'Nome',apellido:'Sobrenome',email:'Endereço de e-mail',password:'Senha',edad:'Idade',pais:'País',genero:'Gênero',motivo:'Conte-nos sua motivação',siguiente:'Continuar',atras:'Voltar',crear:'Criar minha conta',o:'ou cadastre-se com',google:'Continuar com Google',ya:'Já tem uma conta?',login:'Entrar',hombre:'Masculino',mujer:'Feminino',otro:'Outro',prefiero:'Prefiro não dizer',pais_default:'— Selecione —',
    motivos:{biblia:'Ler o Novo Testamento em grego original',teologia:'Estudos teológicos ou seminário',historia:'Interesse na história e cultura antigas',linguistica:'Curiosidade linguística',outro:'Outro motivo'}},
  fr:{titulo:'Créer un compte',subtitulo:'Commencez votre parcours avec le grec koinè',paso1:"Données d'accès",paso2:'Votre profil',paso3:'Pourquoi apprenez-vous le grec?',nombre:'Prénom',apellido:'Nom de famille',email:'Adresse e-mail',password:'Mot de passe',edad:'Âge',pais:'Pays',genero:'Genre',motivo:'Parlez-nous de votre motivation',siguiente:'Continuer',atras:'Retour',crear:'Créer mon compte',o:'ou inscrivez-vous avec',google:'Continuer avec Google',ya:'Vous avez déjà un compte?',login:'Se connecter',hombre:'Homme',mujer:'Femme',otro:'Autre',prefiero:'Préfère ne pas dire',pais_default:'— Sélectionnez —',
    motivos:{biblia:'Lire le Nouveau Testament en grec original',teologia:'Études théologiques ou séminaire',historia:"Intérêt pour l'histoire et la culture antiques",linguistica:'Curiosité linguistique',autre:'Autre raison'}}
};

let lang = 'es';

function detectLang(){
  const nav = navigator.language || navigator.userLanguage || 'es';
  const code = nav.split('-')[0].toLowerCase();
  return ['es','en','pt','fr'].includes(code) ? code : 'es';
}

function applyLang(l){
  lang = l;
  document.getElementById('campo-idioma').value = l;
  document.querySelectorAll('.lang-btn').forEach(b => b.classList.toggle('active', b.dataset.lang === l));
  const tx = t[l];
  document.getElementById('txt-titulo').textContent     = tx.titulo;
  document.getElementById('txt-subtitulo').textContent  = tx.subtitulo;
  document.getElementById('txt-paso1').textContent      = tx.paso1;
  document.getElementById('txt-paso2').textContent      = tx.paso2;
  document.getElementById('txt-paso3').textContent      = tx.paso3;
  document.getElementById('lbl-nombre').innerHTML       = tx.nombre + ' <span class="req">*</span>';
  document.getElementById('lbl-apellido').innerHTML     = tx.apellido + ' <span class="req">*</span>';
  document.getElementById('lbl-email').innerHTML        = tx.email + ' <span class="req">*</span>';
  document.getElementById('lbl-password').innerHTML     = tx.password + ' <span class="req">*</span>';
  document.getElementById('lbl-edad').textContent       = tx.edad;
  document.getElementById('lbl-pais').textContent       = tx.pais;
  document.getElementById('lbl-genero').textContent     = tx.genero;
  document.getElementById('lbl-motivo').textContent     = tx.motivo;
  document.getElementById('txt-siguiente').textContent  = tx.siguiente;
  document.getElementById('txt-siguiente2').textContent = tx.siguiente;
  document.getElementById('txt-atras').textContent      = tx.atras;
  document.getElementById('txt-atras2').textContent     = tx.atras;
  document.getElementById('txt-crear').textContent      = tx.crear + ' →';
  document.getElementById('txt-o').textContent          = tx.o;
  document.getElementById('txt-google').textContent     = tx.google;
  document.getElementById('txt-ya-cuenta').textContent  = tx.ya;
  document.getElementById('lnk-login').textContent      = tx.login;
  document.getElementById('gtxt-m').textContent         = tx.hombre;
  document.getElementById('gtxt-f').textContent         = tx.mujer;
  document.getElementById('gtxt-o').textContent         = tx.otro;
  document.getElementById('gtxt-n').textContent         = tx.prefiero;
  document.querySelector('#opt-pais-default').textContent = tx.pais_default;
  renderMotivos(l);
  const ph = {es:{nombre:'Tu nombre',apellido:'Tu apellido',email:'correo@ejemplo.com',password:'Mínimo 8 caracteres'},en:{nombre:'Your name',apellido:'Last name',email:'email@example.com',password:'At least 8 characters'},pt:{nombre:'Seu nome',apellido:'Sobrenome',email:'email@exemplo.com',password:'Mínimo 8 caracteres'},fr:{nombre:'Votre prénom',apellido:'Nom',email:'email@exemple.com',password:'Au moins 8 caractères'}};
  document.getElementById('campo-nombre').placeholder   = ph[l].nombre;
  document.getElementById('campo-apellido').placeholder  = ph[l].apellido;
  document.getElementById('campo-email').placeholder     = ph[l].email;
  document.getElementById('campo-password').placeholder  = ph[l].password;
}

function renderMotivos(l){
  const c = document.getElementById('motivos-container');
  c.innerHTML = '';
  const motivos = t[l].motivos;
  Object.entries(motivos).forEach(([key, label]) => {
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.textContent = label;
    btn.style.cssText = 'padding:.7rem 1rem;border:1px solid var(--ivory-dark);background:var(--ivory);font-family:var(--sans);font-size:.85rem;color:var(--ink);cursor:pointer;text-align:left;transition:all .2s';
    btn.onclick = () => {
      document.querySelectorAll('#motivos-container button').forEach(b => {
        b.style.borderColor = 'var(--ivory-dark)';
        b.style.background  = 'var(--ivory)';
        b.style.color       = 'var(--ink)';
      });
      btn.style.borderColor = 'var(--egeo)';
      btn.style.background  = 'var(--egeo)';
      btn.style.color       = '#fff';
      document.getElementById('campo-motivo').value = key;
    };
    c.appendChild(btn);
  });
}

function irPaso(n){
  if(n === 2){
    const nombre   = document.getElementById('campo-nombre').value.trim();
    const apellido = document.getElementById('campo-apellido').value.trim();
    const email    = document.getElementById('campo-email').value.trim();
    const pass     = document.getElementById('campo-password').value;
    if(!nombre || !apellido || !email || !pass){ alert('Completa todos los campos obligatorios.'); return; }
    if(pass.length < 8){ alert('La contraseña debe tener al menos 8 caracteres.'); return; }
  }
  [1,2,3].forEach(i => {
    document.getElementById('paso-' + i).style.display = i === n ? 'block' : 'none';
    const dot = document.getElementById('step' + i + '-dot');
    dot.classList.toggle('active', i === n);
    dot.classList.toggle('done',   i < n);
  });
}

document.querySelectorAll('.gender-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    document.querySelectorAll('.gender-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('campo-genero').value = btn.dataset.val;
  });
});

document.querySelectorAll('.lang-btn').forEach(btn => {
  btn.addEventListener('click', () => applyLang(btn.dataset.lang));
});

function loginGoogle(){ alert('Google OAuth — próximamente'); }

applyLang(detectLang());
</script>
</body>
</html>
