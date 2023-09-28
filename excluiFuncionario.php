<?php



include("conecta.php");

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["idFuncionario"])) {
    // Recupere o ID do registro a ser excluído
    $idFuncionario = $_GET["idFuncionario"];


    $sql = "DELETE FROM funcionarios WHERE idFuncionario = $idFuncionario";

    if ($mysqli->query($sql) === TRUE) {
        $exclusao = "Funcionário exluido!";
        echo '<script>alert("Cadastro excluido com sucesso!");</script>';
        echo '<script>
            setTimeout(function() {
                window.location.href = "listaFuncionarios.php";
            }, 1);
          </script>';
        exit;
    } else {
        echo '<script>alert("Erro ao excluir cadastro:'.$mysqli->error.'");</script>';
        echo '<script>
            setTimeout(function() {
                window.location.href = "listaFuncionarios.php";
            }, 1);
          </script>';
    }

}
?>

