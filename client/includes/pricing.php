<?php
declare(strict_types=1);

/* Centralizamos la lógica de negocio para los cálculos del carrito */

function calcularTotalesCarrito(array $carrito, PDO $db): array {
    $esp_id = (int) ($carrito["espacio_id"] ?? 0);
    $noches = (int) ($carrito["noches"] ?? 0);
    
    if ($esp_id <= 0 || $noches <= 0) {
        return [];
    }

    $stmt = $db->prepare("SELECT precio_noche FROM espacios WHERE id = ? AND activo = 1");
    $stmt->execute([$esp_id]);
    $espacio = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$espacio) {
        return [];
    }

    $precio_noche = (float) $espacio["precio_noche"];
    $subtotal = $precio_noche * $noches;
    $iva = $subtotal * IVA;
    $total = $subtotal + LIMPIEZA_FEE + $iva;

    return [
        "subtotal" => $subtotal,
        "iva"      => $iva,
        "limpieza" => LIMPIEZA_FEE,
        "total"    => $total
    ];
}
