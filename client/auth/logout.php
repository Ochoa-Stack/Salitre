<?php
/* 'client/auth/logout.php' es la página que maneja el cierre de sesión de los clientes */
session_start();
require_once dirname(__DIR__) . "/../config/constants.php";

session_destroy();
header("Location: " . BASE_URL . "client/index.php");
exit;
