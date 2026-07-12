<?php

declare(strict_types=1);

namespace Tests\Integration;

use PHPUnit\Framework\TestCase;
use PDO;
use DateTimeImmutable;

final class VistaOcupacionDiariaTest extends TestCase
{
    private static PDO $pdo;

    public static function setUpBeforeClass(): void
    {
        $host = getenv('DB_HOST') ?: 'db';
        $port = getenv('DB_PORT') ?: '3306';
        $dbname = getenv('DB_NAME') ?: 'salitre_db';
        $user = getenv('DB_USER') ?: 'root';
        $pass = getenv('DB_PASS') ?: 'secret';

        self::$pdo = new PDO(
            "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4",
            $user,
            $pass,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );
    }

    public function testReservaAbarcaTodaSuEstanciaPorTipoEspacio(): void
    {
        $tipoEspacio = 'villa';
        $hoy = new DateTimeImmutable();
        $fechaEntrada = $hoy->format('Y-m-d');
        $fechaSalida = $hoy->modify('+3 days')->format('Y-m-d');
        $precioTotal = 5000.00;

        // Se obtiene línea base (Delta approach para ignorar datos seed existentes)
        $stmtBase = self::$pdo->prepare(
            'SELECT reservas_activas, revenue_diario FROM v_ocupacion_diaria 
             WHERE tipo_espacio = :tipo AND fecha = :fecha'
        );
        $stmtBase->execute(['tipo' => $tipoEspacio, 'fecha' => $fechaEntrada]);
        $base = $stmtBase->fetch() ?: ['reservas_activas' => 0, 'revenue_diario' => 0];

        // Se crean los datos de prueba
        self::$pdo->beginTransaction();
        try {
            $stmtEsp = self::$pdo->prepare(
                'INSERT INTO espacios (nombre, slug, tipo, precio_noche, capacidad) 
                 VALUES (:nombre, :slug, :tipo, :precio, 1)'
            );
            $stmtEsp->execute([
                'nombre' => 'Villa Test Ocupacion',
                'slug' => 'villa-test-ocupacion-' . uniqid(),
                'tipo' => $tipoEspacio,
                'precio' => 1000.00
            ]);
            $espacioId = (int) self::$pdo->lastInsertId();

            $stmtRes = self::$pdo->prepare(
                'INSERT INTO reservas (cliente_id, espacio_id, fecha_entrada, fecha_salida, noches, precio_total, estado) 
                 VALUES (1, :espacio, :entrada, :salida, 3, :precio, "confirmada")'
            );
            $stmtRes->execute([
                'espacio' => $espacioId,
                'entrada' => $fechaEntrada,
                'salida' => $fechaSalida,
                'precio' => $precioTotal
            ]);

            // Se valida día de entrada (Debe sumar +1 reserva y +precio a la base)
            $stmtBase->execute(['tipo' => $tipoEspacio, 'fecha' => $fechaEntrada]);
            $dia1 = $stmtBase->fetch();
            $this->assertEquals($base['reservas_activas'] + 1, $dia1['reservas_activas'], 'Día de entrada debe sumar 1 reserva');
            $this->assertEquals($base['revenue_diario'] + $precioTotal, $dia1['revenue_diario'], 'Día de entrada debe sumar revenue');

            // Se valida día intermedio (La reserva sigue activa)
            $fechaIntermedia = $hoy->modify('+1 day')->format('Y-m-d');
            $stmtBase->execute(['tipo' => $tipoEspacio, 'fecha' => $fechaIntermedia]);
            $dia2 = $stmtBase->fetch();
            $this->assertEquals($base['reservas_activas'] + 1, $dia2['reservas_activas'], 'Día intermedio debe mantener la reserva activa');

            // Se valida día de salida (Fecha exclusiva, la reserva ya no cuenta)
            $stmtBase->execute(['tipo' => $tipoEspacio, 'fecha' => $fechaSalida]);
            $diaSalida = $stmtBase->fetch();
            $this->assertEquals($base['reservas_activas'], $diaSalida['reservas_activas'], 'Día de salida no debe contar la reserva');

            self::$pdo->rollBack();
        } catch (\Exception $e) {
            self::$pdo->rollBack();
            throw $e;
        }
    }
}
