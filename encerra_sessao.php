<?php
session_start();

// Limpar a variável "authenticated"
$_SESSION["authenticated"] = false;

// Destruir a sessão
session_destroy();

header("Location: index.php");
exit();
?>