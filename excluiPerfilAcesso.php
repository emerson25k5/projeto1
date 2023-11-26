<?php
session_start();
include("config.php");
include("conecta.php");

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["idAssocia"])) {
    // Recupere o ID do registro a ser excluído
    $idAssocia = $_GET["idAssocia"];


    $sql = "DELETE FROM usedperfilacesso WHERE idAssociaPerfil = $idAssocia";
    

    if ($mysqli->query($sql) === TRUE) {
        $exclusao = "Funcionário exluido!";
        echo '<script>alert("Perfil associado excluido com sucesso!");</script>';
        echo '<script>
            setTimeout(function() {
                window.location.href = "associaPerfilAcesso.php";
            }, 1);
          </script>';
        exit;
    } else {
        echo '<script>alert("Erro ao excluir cadastro:'.$mysqli->error.'");</script>';
        echo '<script>
            setTimeout(function() {
                window.location.href = "associaPerfilAcesso.php";
            }, 1);
          </script>';
        }
}

$mysqli->close();


?>