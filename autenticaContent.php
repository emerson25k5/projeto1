<?php
// Defina o tempo de expiração da sessão para 15 minutos (em segundos)
$sessionTimeout = 15 * 60; // 15 minutos * 60 segundos por minuto

ini_set('session.cookie_lifetime', 0);
ini_set('session.use_strict_mode', 1);

// Defina a diretiva gc_maxlifetime para o tempo de expiração desejado
ini_set('session.gc_maxlifetime', $sessionTimeout);

session_start();
if (!isset($_SESSION["authenticated"]) || $_SESSION["authenticated"] !== true) {
    header("Location: index.php");
    exit;
}