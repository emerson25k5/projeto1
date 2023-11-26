<?php
// Conectar ao banco de dados usando MySQLi
$mysqli = new mysqli(BD_HOST, BD_USERNAME, BD_PASSWORD, BD_DATABASE);
// Verificar a conexão
if ($mysqli->connect_error) {
    die("Erro na conexão: " . $mysqli->connect_error);
}