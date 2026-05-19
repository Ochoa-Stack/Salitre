-- Preparación para Data Engineering & ML
-- Nota: Esta migración está diseñada para una sola ejecución.

-- Añadimos la columna para rastrear actualizaciones en la tabla de espacios (CDC)
ALTER TABLE espacios 
ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

-- Añadimos la columna para rastrear actualizaciones en la tabla de reservas (CDC)
ALTER TABLE reservas 
ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

-- Creamos la tabla de normalización para facilitar el One-Hot Encoding de amenidades
CREATE TABLE IF NOT EXISTS espacio_amenidades (
  id INT AUTO_INCREMENT PRIMARY KEY,
  espacio_id INT NOT NULL,
  amenidad VARCHAR(100) NOT NULL,
  FOREIGN KEY (espacio_id) REFERENCES espacios(id) ON DELETE CASCADE,
  UNIQUE KEY unique_espacio_amenidad (espacio_id, amenidad)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Construimos la vista analítica sin sesgo de supervivencia (cuadrícula de tiempo continuo)
CREATE OR REPLACE VIEW v_ocupacion_diaria AS
WITH RECURSIVE calendario AS (
    SELECT DATE_SUB(CURDATE(), INTERVAL 365 DAY) AS fecha
    UNION ALL
    SELECT DATE_ADD(fecha, INTERVAL 1 DAY)
    FROM calendario
    WHERE fecha < DATE_ADD(CURDATE(), INTERVAL 365 DAY)
),
tipos_espacios AS (
    SELECT DISTINCT tipo AS tipo_espacio FROM espacios
)
SELECT 
    c.fecha,
    t.tipo_espacio,
    COALESCE(COUNT(r.id), 0) AS reservas_activas,
    COALESCE(SUM(r.precio_total), 0) AS revenue_diario
FROM calendario c
CROSS JOIN tipos_espacios t
LEFT JOIN espacios e ON e.tipo = t.tipo_espacio
LEFT JOIN reservas r ON r.espacio_id = e.id 
    AND DATE(r.fecha_entrada) = c.fecha 
    AND r.estado = 'confirmada'
GROUP BY c.fecha, t.tipo_espacio;
