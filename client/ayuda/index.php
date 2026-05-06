<?php
/* 'client/ayuda/index.php' sera la página de Ayuda - en construcción */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once "../../config/database.php";
require_once "../../config/constants.php";

$page_title = "Ayuda — " . SITE_NAME;
$extra_stylesheets = ["assets/css/client/ayuda.css"];

require_once "../includes/header.php";
require_once "../includes/nav.php";
require __DIR__ . '/index.view.php';
?>
