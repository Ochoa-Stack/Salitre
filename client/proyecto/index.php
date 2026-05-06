<?php
/* Definimos las variables para la información del proyecto - 'client/proyecto/index.php' */

session_start();
require_once "../../config/database.php";
require_once "../../config/constants.php";

$page_title = "Proyecto - " . SITE_NAME;
$extra_stylesheets = ["assets/css/client/proyecto.css"];

require_once "../includes/header.php";
require_once "../includes/nav.php";
require __DIR__ . '/index.view.php';
?>
