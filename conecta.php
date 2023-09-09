<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "projeto1";

// Conectar ao banco de dados usando MySQLi
$mysqli = new mysqli($host, $username, $password, $database);

// Verificar a conexão
if ($mysqli->connect_error) {
    die("Erro na conexão: " . $mysqli->connect_error);
}

// Agora você está conectado ao banco de dados e pode realizar consultas e operações nele.
?>