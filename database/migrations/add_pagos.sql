CREATE TABLE IF NOT EXISTS pagos (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  reserva_id    INT NOT NULL,
  monto         DECIMAL(10,2) NOT NULL,
  metodo        ENUM('tarjeta','oxxo','paypal') NOT NULL DEFAULT 'tarjeta',
  estado        ENUM('pendiente','aprobado','rechazado') NOT NULL DEFAULT 'pendiente',
  ultimos4      CHAR(4) DEFAULT NULL,
  marca_tarjeta VARCHAR(20) DEFAULT NULL,
  procesado_en  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (reserva_id) REFERENCES reservas(id) ON DELETE CASCADE
);

ALTER TABLE reservas ADD COLUMN IF NOT EXISTS estado_pago 
  ENUM('pendiente','pagado') NOT NULL DEFAULT 'pendiente';
