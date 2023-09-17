<?php

include("conecta.php");

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
    // Recupere o ID do registro a ser excluído
    $id = $_GET["id"];

    $sql = "DELETE FROM usuarios WHERE idUsuario = $id";

    if ($mysqli->query($sql) === TRUE) {
        $esclusão = "Usuário exluido!";
        echo '<script>alert("Cadastro excluido com sucesso!");</script>';
        echo '<script>
            setTimeout(function() {
                window.location.href = "listausuarios.php";
            }, 1);
          </script>';
        exit;
    } else {
        echo '<script>alert("Erro ao excluir cadastro:");</script>';
        echo '<script>
            setTimeout(function() {
                window.location.href = "listausuarios.php";
            }, 1);
          </script>';
    }

}
?>

