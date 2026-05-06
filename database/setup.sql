-- Base de datos para Salitre

DROP DATABASE IF EXISTS salitre_db;

CREATE DATABASE salitre_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE salitre_db;

CREATE TABLE clientes (
  id        INT AUTO_INCREMENT PRIMARY KEY,
  nombre    VARCHAR(100) NOT NULL,
  email     VARCHAR(150) NOT NULL,
  password  VARCHAR(255) NOT NULL,
  telefono  VARCHAR(20),
  creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_clientes_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE staff (
  id        INT AUTO_INCREMENT PRIMARY KEY,
  nombre    VARCHAR(100) NOT NULL,
  email     VARCHAR(150) NOT NULL,
  password  VARCHAR(255) NOT NULL,
  rol       ENUM('admin','superadmin') NOT NULL DEFAULT 'admin',
  activo    TINYINT(1) NOT NULL DEFAULT 1,
  creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_staff_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE espacios (
  id             INT AUTO_INCREMENT PRIMARY KEY,
  nombre         VARCHAR(100) NOT NULL,
  slug           VARCHAR(100) NOT NULL,
  tipo           ENUM('estudio','loft','suite','villa') NOT NULL,
  descripcion    TEXT,
  precio_noche   DECIMAL(8,2) NOT NULL,
  capacidad      TINYINT UNSIGNED NOT NULL DEFAULT 1,
  foto_principal VARCHAR(255),
  fotos_galeria  JSON,
  amenidades     JSON,
  activo         TINYINT(1) NOT NULL DEFAULT 1,
  creado_en      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_espacios_slug (slug),
  KEY idx_tipo (tipo),
  KEY idx_precio (precio_noche)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE reservas (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  cliente_id    INT NOT NULL,
  espacio_id    INT NOT NULL,
  fecha_entrada DATE NOT NULL,
  fecha_salida  DATE NOT NULL,
  noches        TINYINT UNSIGNED NOT NULL,
  precio_total  DECIMAL(10,2) NOT NULL,
  estado        ENUM('pendiente','confirmada','cancelada','completada') NOT NULL DEFAULT 'pendiente',
  notas         TEXT,
  creado_en     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_reservas_cliente FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE,
  CONSTRAINT fk_reservas_espacio FOREIGN KEY (espacio_id) REFERENCES espacios(id) ON DELETE RESTRICT,
  KEY idx_estado (estado),
  KEY idx_fechas (fecha_entrada, fecha_salida)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE eventos (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  titulo       VARCHAR(150) NOT NULL,
  descripcion  TEXT,
  fecha_evento DATE NOT NULL,
  hora_inicio  TIME NULL,
  hora_fin     TIME NULL,
  cupo         SMALLINT UNSIGNED NULL,
  activo       TINYINT(1) NOT NULL DEFAULT 1,
  creado_en    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  KEY idx_fecha (fecha_evento)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE contacto (
  id        INT AUTO_INCREMENT PRIMARY KEY,
  nombre    VARCHAR(100) NOT NULL,
  email     VARCHAR(150) NOT NULL,
  mensaje   TEXT NOT NULL,
  leido     TINYINT(1) NOT NULL DEFAULT 0,
  creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  KEY idx_leido (leido)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Inyectamos los datos iniciales para las pruebas
INSERT INTO staff (nombre, email, password, rol, activo) VALUES
('Admin Salitre', 'admin@salitre.mx',
 '$2y$10$H7V.X/i7otiJ.g.PWTRpVOdW.5dhloqheWlzUbDQ.VA4XaTbk1FTS',
 'superadmin', 1);

INSERT INTO espacios (nombre, slug, tipo, descripcion, precio_noche, capacidad, amenidades, activo) VALUES
('Estudio Marea', 'estudio-marea', 'estudio', 'El espacio de entrada. Funcionalidad sin adornos.', 89.00, 1, '["WiFi fibra óptica","Monitor 27 pulgadas","USB-C hub","A/C","Cafetera Nespresso"]', 1),
('Loft Creativo', 'loft-creativo', 'loft', 'Para el nómada visual. Luz, espacio y pantalla 43 pulgadas.', 129.00, 2, '["WiFi fibra óptica","Pantalla 43 pulgadas 4K","Luz regulable","Balcón","Mini bar"]', 1),
('Suite Salitre', 'suite-salitre', 'suite', 'Vista al mar, zona de trabajo separada, doble escritorio.', 149.00, 2, '["WiFi fibra óptica","Doble monitor","Vista al mar","Balcón privado","Bañera"]', 1),
('Villa Conexión', 'villa-conexion', 'villa', 'Para equipos. Terraza privada, cocina, sala para 4.', 199.00, 4, '["WiFi dedicada","Mesa para 4","Monitor 55 pulgadas","Cocina equipada","Terraza"]', 1);

INSERT INTO eventos (titulo, descripcion, fecha_evento, hora_inicio, hora_fin, cupo, activo) VALUES
('Yoga frente al mar', 'Sesión grupal en terraza. Sin costo para huéspedes.', DATE_ADD(CURDATE(), INTERVAL 3 DAY), '06:30:00', '07:30:00', 20, 1),
('Clases de surf', 'Con instructor. Incluidas en estancias de 3+ noches.', DATE_ADD(CURDATE(), INTERVAL 5 DAY), '07:00:00', '09:00:00', 8, 1),
('Noche de networking', 'Conecta con otros nómadas del hotel.', DATE_ADD(CURDATE(), INTERVAL 10 DAY), '19:00:00', '21:00:00', 30, 1);

-- Asignamos las fotos principales a los espacios
UPDATE espacios SET foto_principal = 'assets/img/client/espacios/estudio-marea.webp' WHERE slug = 'estudio-marea';
UPDATE espacios SET foto_principal = 'assets/img/client/espacios/loft-creativo.webp' WHERE slug = 'loft-creativo';
UPDATE espacios SET foto_principal = 'assets/img/client/espacios/suite-salitre.webp' WHERE slug = 'suite-salitre';
UPDATE espacios SET foto_principal = 'assets/img/client/espacios/villa-conexion.webp' WHERE slug = 'villa-conexion';

-- CLIENTE DE PRUEBA (password -> cliente123)
INSERT INTO clientes (nombre, email, password, telefono) VALUES
('Cliente Prueba', 'cliente@prueba.mx',
 '$2y$10$YJ0fE1w2OASK0jXnJzFCYeWbYXbRx2Z4qR2kX0fvj5xN6MqT7bKGW',
 '5512345678');

-- Fin del script de configuración inicial
