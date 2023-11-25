<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "swupec92_swupe";

// Conectar ao banco de dados usando MySQLi
$mysqli = new mysqli($host, $username, $password, $database);

// Verificar a conexão
if ($mysqli->connect_error) {
    die("Erro na conexão: " . $mysqli->connect_error);
}

?>