-- Migración: Corrección de bug en vista v_ocupacion_diaria
-- Problema: El JOIN original solo contaba reservas en su fecha_entrada exacta.
-- Solución: Evaluar si la fecha del calendario cae en el rango [fecha_entrada, fecha_salida).
-- Nota: Se respeta el agrupamiento original por tipo_espacio para análisis macro.

DROP VIEW IF EXISTS v_ocupacion_diaria;

CREATE VIEW v_ocupacion_diaria AS
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
    AND c.fecha >= r.fecha_entrada 
    AND c.fecha < r.fecha_salida 
    AND r.estado = 'confirmada'
GROUP BY c.fecha, t.tipo_espacio;
