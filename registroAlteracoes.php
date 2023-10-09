<?php 

include("autenticaContent.php");
include("conecta.php");

if($_SESSION['nivelAcesso'] != 2) {
    echo "Acesso negado!";
    exit;
}

$sql = "SELECT * FROM logalteracoes ORDER BY dataLogAlteracao DESC";

$result = $mysqli->query($sql);

?>

<!DOCTYPE html>
<HTML lang="pt-BR">
    <HEAD>
        <TITLE>PATROL | LOG </TITLE>

    <?php require "headContent.php"; ?>

    <body >

    <h4 class="center">Registro alterações:</h4>


    <div class="col s12 container">

        <?php
        if($result->num_rows > 0){
        while ($row = $result->fetch_assoc()) {
            $dataLogAlteracao = $row['dataLogAlteracao'];
            $idLogUsuarioResponsavel = $row['idLogUsuarioResponsavel'];
            $nomeUsuarioResponsavel = $row['nomeUsuarioResponsavel'];
            $tipoLog = $row['tipoLog'];
            $nomeAlt = $row['nomeAlt'];
            $cpfAlt = $row['cpfAlt'];
            $emailAlt = $row['emailAlt'];
            $telefoneAlt = $row['telefoneAlt'];
            $idFuncionarioAlt = $row['idFuncionarioAlt'];
            $rgAlt = $row['rgAlt'];
            $nascimentoAlt = $row['nascimentoAlt'];
            
                echo '<div class="col s12">';
                echo '<fieldset style="border-radius:10px;">';
                echo '<p class="center"><b>REPONSÁVEL</b></p>';
                echo '<p><b>Data e hora alteração:</b> '.date('d/m/Y H:i:s', strtotime($dataLogAlteracao)).'</p>';
                echo '<p><b>ID:</b> '.$idLogUsuarioResponsavel.'</p>';
                echo '<p><b>Nome:</b> '.$nomeUsuarioResponsavel.'</p>';
                echo '<p><b>Tipo:</b> '.$tipoLog.'</p>';

                if($tipoLog == "Exlusão registro de funcionário"){
                    $descricao = '<b style="color:red;">DADOS EXCLUIDOS</b>';
                }elseif($tipoLog == "Alteração dados gerais funcionario"){
                    $descricao = '<b style="color:orange;">DADOS ANTES DA ALTERAÇÃO</b>';
                }elseif($tipoLog == "Cadastro novo funcionario"){
                    $descricao = '<b style="color:green;">DADOS DO NOVO FUNCIONÁRIO</b>';
                }else{
                    $descricao = '<b style="color:orange;">DADOS ALTERADOS</b>';
                }

                if(($tipoLog == "Alteração dados gerais funcionario") || ($tipoLog == "Exlusão registro de funcionário") || ($tipoLog == "Cadastro novo funcionario")){
                    echo '<fieldset style="border-radius:10px;">';
                    echo '<p class="center">' . $descricao . '</p>';
                    echo '<p><b>ID:</b> '.$idFuncionarioAlt.'</p>';
                    echo '<p><b>Nome completo:</b> '.$nomeAlt.'</p>';
                    echo '<p><b>CPF:</b> '.$cpfAlt.'</p>';
                    echo '<p><b>RG:</b> '.$rgAlt.'</p>';
                    echo '<p><b>E-mail:</b> '.$emailAlt.'</p>';
                    echo '<p><b>Telefone:</b> '.$telefoneAlt.'</p>';
                    echo '<p><b>Data nascimento:</b> '.date('d/m/Y', strtotime($nascimentoAlt)).'</p>';
                    echo '</fieldset>';
                }else{
                    echo '<fieldset style="border-radius:10px;">';
                    echo '<p class="center"><b>' . $descricao . '</b></p>';
                    echo '<p><b>ID:</b> '.$idFuncionarioAlt.'</p>';
                    echo '<p><b>Nome completo:</b> '.$nomeAlt.'</p>';
                    echo '</fieldset>';
                }
                echo '</fieldset>';
                echo '</div><br><br>';

            
        }
        }else{
            echo '<p colspan="2">Nenhum log encontrado</p>';
        }         
        ?>
        <br><br>


    </div>


    </body>

    <?php require "footerContent.php"; ?>

</HTML>