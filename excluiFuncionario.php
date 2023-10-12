<?php
require "autenticaContent.php";

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["idFuncionario"])) {
    // Recupere o ID do registro a ser excluído
    $idFuncionario = $_GET["idFuncionario"];

    include("conecta.php");

    date_default_timezone_set('America/Sao_Paulo'); //obtem a data e hora
    $dataHoraAtual = new DateTime();

    //antes de excluir guarda as informações no LOG
    $idLogUsuarioResponsavel = $_SESSION['idUsuarioLogado'];
    $nomeUsuarioResponsavel = $_SESSION['nomeCompleto'];

    $buscaRegistroLog =("SELECT cpf, dataCadFuncionario, email, funcObservacoes, genero, idFuncionario, nascimento, nome, rg, status, telefone FROM funcionarios WHERE idFuncionario = $idFuncionario");

    $result = $mysqli->query($buscaRegistroLog);

    if($result->num_rows > 0){

        $mysqli->begin_transaction();

        $row = $result->fetch_assoc();
        $tipoLog = "Exlusão registro de funcionário";
        $cpfAlt = $row['cpf'];
        $dataCadFuncionarioAlt = $row['dataCadFuncionario'];
        $emailAlt = $row['email'];
        $funcObservacoesAlt = $row['funcObservacoes'];
        $generoAlt = $row['genero'];
        $idFuncionarioAlt = $row['idFuncionario'];
        $nascimentoAlt = $row['nascimento'];
        $nomeAlt = $row['nome'];
        $rgAlt = $row['rg'];
        $statusAlt = $row['status'];
        $telefoneAlt = $row['telefone'];
        $dataLogAlteracao = $dataHoraAtual->format('Y-m-d H:i:s');

        $insertLog = $mysqli->prepare("INSERT INTO logalteracoes (idLogUsuarioResponsavel, tipoLog, dataLogAlteracao, nomeUsuarioResponsavel, cpfAlt, dataCadFuncionarioAlt, emailAlt, funcObservacoesAlt, generoAlt,
         idFuncionarioAlt, nascimentoAlt, nomeAlt, rgAlt, statusAlt, telefoneAlt) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $insertLog->bind_param("isssssssissssss", $idLogUsuarioResponsavel, $tipoLog, $dataLogAlteracao, $nomeUsuarioResponsavel, $cpfAlt, $dataCadFuncionarioAlt, $emailAlt, $funcObservacoesAlt, $generoAlt, $idFuncionarioAlt,
        $nascimentoAlt, $nomeAlt, $rgAlt, $statusAlt, $telefoneAlt);

        if ($insertLog->execute()) {
            $mysqli->commit();
        }else{
            echo "Falha no registro de LOG" . " Erro: " . $mysqli->error;
            $mysqli->rollback();
            $mysqli->close();
        }
    }

    if($insertLog){ //se inserir no log prossiga com a exclusão

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
}

$mysqli->close();
?>

