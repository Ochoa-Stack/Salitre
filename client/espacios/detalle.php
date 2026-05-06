<?php
/* Definimos la pagina de detalle de espacio - 'client/espacios/detalle.php' */
session_start();
require_once dirname(__DIR__) . "/../config/database.php";
require_once dirname(__DIR__) . "/../config/constants.php";

$slug = filter_var($_GET["slug"] ?? "", FILTER_SANITIZE_SPECIAL_CHARS);
if (empty($slug)) { header("Location: index.php"); exit; }

$pdo = conectarDB();
$stmt = $pdo->prepare("SELECT * FROM espacios WHERE slug = ? AND activo = 1");
$stmt->execute([$slug]);
$espacio = $stmt->fetch();

if (!$espacio) { header("Location: index.php"); exit; }

$page_title = $espacio["nombre"] . " — Salitre";
$extra_stylesheets = ["assets/css/client/espacios.css"];
$extra_scripts = ["assets/js/client/espacios.js"];

require_once dirname(__DIR__) . "/includes/header.php";
require_once dirname(__DIR__) . "/includes/nav.php";
$base = BASE_URL;

// Procesamos las amenidades y fotos (parse data)
$amenidades = json_decode((string)$espacio['amenidades'], true) ?? [];
$fotos_galeria = !empty($espacio['fotos_galeria']) ? json_decode((string)$espacio['fotos_galeria'], true) : [];
if (!is_array($fotos_galeria)) $fotos_galeria = [];

$foto_primaria = !empty($espacio['foto_principal']) ? $espacio['foto_principal'] : null;

// Preparar lista total de fotos para la galeria
$todas_fotos = [];
if ($foto_primaria) $todas_fotos[] = $foto_primaria;
foreach ($fotos_galeria as $f) {
    if (!empty($f)) $todas_fotos[] = $f;
}
require __DIR__ . '/detalle.view.php';
?>
