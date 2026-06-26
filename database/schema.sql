SET NAMES utf8mb4;
SET time_zone = '+00:00';

-- Usuarios
CREATE TABLE usuarios (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE,
    password_hash VARCHAR(255),
    google_id VARCHAR(255) UNIQUE,
    facebook_id VARCHAR(255) UNIQUE,
    edad TINYINT UNSIGNED,
    genero ENUM('masculino','femenino','otro','prefiero_no_decir'),
    pais CHAR(2),
    motivo TEXT,
    idioma ENUM('es','en','pt','fr') DEFAULT 'es',
    plan ENUM('free','premium') DEFAULT 'free',
    stripe_customer_id VARCHAR(255),
    avatar_url VARCHAR(500),
    es_falso TINYINT(1) DEFAULT 0,
    activo TINYINT(1) DEFAULT 1,
    email_verificado TINYINT(1) DEFAULT 0,
    token_verificacion VARCHAR(64),
    token_reset VARCHAR(64),
    token_reset_expira DATETIME,
    creado_en DATETIME DEFAULT CURRENT_TIMESTAMP,
    ultimo_acceso DATETIME
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sesiones
CREATE TABLE sesiones (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT UNSIGNED NOT NULL,
    token VARCHAR(128) NOT NULL UNIQUE,
    expira_en DATETIME NOT NULL,
    ip VARCHAR(45),
    user_agent TEXT,
    creado_en DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Capítulos
CREATE TABLE capitulos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    numero TINYINT UNSIGNED NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    titulo_es VARCHAR(255),
    titulo_en VARCHAR(255),
    titulo_pt VARCHAR(255),
    titulo_fr VARCHAR(255),
    descripcion_es TEXT,
    descripcion_en TEXT,
    descripcion_pt TEXT,
    descripcion_fr TEXT,
    imagen_portada VARCHAR(500),
    es_premium TINYINT(1) DEFAULT 1,
    publicado TINYINT(1) DEFAULT 0,
    orden TINYINT UNSIGNED NOT NULL,
    creado_en DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Escenas (secciones dentro de un capítulo)
CREATE TABLE escenas (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    capitulo_id INT UNSIGNED NOT NULL,
    numero TINYINT UNSIGNED NOT NULL,
    titulo_es VARCHAR(255),
    titulo_en VARCHAR(255),
    contenido_json JSON NOT NULL,
    imagen_url VARCHAR(500),
    audio_url VARCHAR(500),
    FOREIGN KEY (capitulo_id) REFERENCES capitulos(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Palabras (léxico)
CREATE TABLE palabras (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    forma_griega VARCHAR(100) NOT NULL,
    forma_lexica VARCHAR(100),
    definicion_es TEXT,
    definicion_en TEXT,
    definicion_pt TEXT,
    definicion_fr TEXT,
    parte_del_discurso VARCHAR(50),
    notas_morfologicas TEXT,
    audio_url VARCHAR(500)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Ejercicios
CREATE TABLE ejercicios (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    capitulo_id INT UNSIGNED NOT NULL,
    tipo ENUM('escritura','arrastrar','relacionar','seleccion') NOT NULL,
    instruccion_es TEXT,
    instruccion_en TEXT,
    instruccion_pt TEXT,
    instruccion_fr TEXT,
    contenido_json JSON NOT NULL,
    xp_recompensa SMALLINT UNSIGNED DEFAULT 50,
    obolos_recompensa SMALLINT UNSIGNED DEFAULT 10,
    orden TINYINT UNSIGNED DEFAULT 1,
    FOREIGN KEY (capitulo_id) REFERENCES capitulos(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Progreso del usuario por capítulo
CREATE TABLE progreso (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT UNSIGNED NOT NULL,
    capitulo_id INT UNSIGNED NOT NULL,
    completado TINYINT(1) DEFAULT 0,
    intentos TINYINT UNSIGNED DEFAULT 0,
    xp_ganado SMALLINT UNSIGNED DEFAULT 0,
    obolos_ganados SMALLINT UNSIGNED DEFAULT 0,
    iniciado_en DATETIME DEFAULT CURRENT_TIMESTAMP,
    completado_en DATETIME,
    UNIQUE KEY uq_progreso (usuario_id, capitulo_id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (capitulo_id) REFERENCES capitulos(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Rachas
CREATE TABLE rachas (
    usuario_id INT UNSIGNED PRIMARY KEY,
    racha_actual SMALLINT UNSIGNED DEFAULT 0,
    racha_maxima SMALLINT UNSIGNED DEFAULT 0,
    ultima_fecha_estudio DATE,
    escudos TINYINT UNSIGNED DEFAULT 0,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Óbolos (moneda interna)
CREATE TABLE obolos (
    usuario_id INT UNSIGNED PRIMARY KEY,
    cantidad MEDIUMINT UNSIGNED DEFAULT 0,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Historial de óbolos
CREATE TABLE obolos_historial (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT UNSIGNED NOT NULL,
    cantidad INT NOT NULL,
    motivo VARCHAR(100),
    creado_en DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- XP total (para ranking)
CREATE TABLE experiencia (
    usuario_id INT UNSIGNED PRIMARY KEY,
    xp_total MEDIUMINT UNSIGNED DEFAULT 0,
    xp_semana MEDIUMINT UNSIGNED DEFAULT 0,
    xp_mes MEDIUMINT UNSIGNED DEFAULT 0,
    nivel TINYINT UNSIGNED DEFAULT 1,
    semana_actual TINYINT UNSIGNED DEFAULT 0,
    mes_actual TINYINT UNSIGNED DEFAULT 0,
    anio_actual SMALLINT UNSIGNED DEFAULT 0,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Logros definidos
CREATE TABLE logros (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(50) UNIQUE NOT NULL,
    nombre_griego VARCHAR(100),
    nombre_es VARCHAR(100),
    nombre_en VARCHAR(100),
    nombre_pt VARCHAR(100),
    nombre_fr VARCHAR(100),
    descripcion_es TEXT,
    descripcion_en TEXT,
    icono VARCHAR(100),
    xp_bonus SMALLINT UNSIGNED DEFAULT 50,
    obolos_bonus SMALLINT UNSIGNED DEFAULT 20,
    condicion_tipo VARCHAR(50),
    condicion_valor INT UNSIGNED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Logros del usuario
CREATE TABLE logros_usuario (
    usuario_id INT UNSIGNED NOT NULL,
    logro_id INT UNSIGNED NOT NULL,
    obtenido_en DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (usuario_id, logro_id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (logro_id) REFERENCES logros(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Datos semilla de logros
INSERT INTO logros (codigo, nombre_griego, nombre_es, nombre_en, icono, xp_bonus, obolos_bonus, condicion_tipo, condicion_valor) VALUES
('primer_paso',   'Νεόφυτος',   'Primer paso',        'First step',       'ti-footprint',    50,  10, 'capitulos_completados', 1),
('lector',        'Ἀναγνώστης', 'Lector',             'Reader',           'ti-book',        100,  25, 'capitulos_completados', 5),
('escriba',       'Γραμματεύς', 'Escriba',            'Scribe',           'ti-writing',     200,  50, 'capitulos_completados', 15),
('sabio',         'Σοφιστής',   'Sabio',              'Sophist',          'ti-brain',       500, 100, 'capitulos_completados', 30),
('racha_7',       'Ἐγκρατής',   '7 días seguidos',    '7-day streak',     'ti-flame',       200,  50, 'racha', 7),
('racha_30',      'Φιλόπονος',  '30 días seguidos',   '30-day streak',    'ti-flame',       500, 150, 'racha', 30),
('perfecto',      'Ἀκριβής',    'Lección perfecta',   'Perfect lesson',   'ti-star',        100,  30, 'leccion_perfecta', 1),
('madrugador',    'Ὄρθριος',    'Madrugador',         'Early bird',       'ti-sun',          50,  15, 'hora_estudio', 6);
