<?php 

include("autenticaContent.php");
include("conecta.php");

//verifica se o nivel de acesso é de adm, se n for é exibida mensagem de erro e o resto da página não carrega
if($_SESSION['nivelAcesso'] != 2) {
    echo "Acesso negado!";
    exit;
}

$_statusCad = "";

//select com base no ID do funcionario para trazer as férias dele
if (isset($_GET["id"])) {
    // Recupere o ID do registro a ser exibido
    $id = $_GET["id"];
}



//post insert para enviar novo cadastro de férias
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    date_default_timezone_set('America/Sao_Paulo');
    $dataHoraAtual = new DateTime();

    $id = $_GET["id"];

    if (isset($_POST["form_id"])) {
        $form_id = $_POST["form_id"];

        if ($form_id == 1) {

            $mysqli->begin_transaction();

            $dataInicioUltFerias = $_POST['dataInicioUltFerias'];
            $dataFimUltFerias = $_POST['dataFimUltFerias'];
            $feriasObservacoes = $_POST['feriasObservacoes'];   
            $nomeResponsavelCadastro = $_SESSION['nomeCompleto'];
            $dataCadFerias = $dataHoraAtual->format('Y-m-d H:i:s'); //variável com a hora atual

            $sql = "INSERT INTO controleferias (dataInicioUltFerias, dataFimUltFerias, funcionarioID, feriasObservacoes, nomeResponsavelCadastro, dataCadastro) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $mysqli->prepare($sql);

            if ($stmt === false) {
                die('Erro na preparação da consulta: ' . $mysqli->error);
            }

            $stmt->bind_param("ssisss", $dataInicioUltFerias, $dataFimUltFerias, $id, $feriasObservacoes, $nomeResponsavelCadastro, $dataCadFerias);


            if ($stmt->execute()) {
                $mysqli->commit();
                $_statusCad = "Cadastro realizado com sucesso";        
            }else {
                $mysqli->rollback();
                $_statusCad = "Erro no cadastro: ".$mysqli->error;
            }

            header("Location: controleferias.php?id=$id");//após atualização/inserção no banco é redirecionado para a mesma página para evitar duplicidade com F5
            exit;
        }

        if ($form_id == 2) {

            $mysqli->begin_transaction();
            
            $idFerias = $_POST['idFerias'];
            $novaFeriasObservacoes = $_POST['novaFeriasObservacoes'];

            $slqUpdateFerias = "UPDATE controleferias SET feriasObservacoes=? WHERE funcionarioID = $id AND idUltFerias = $idFerias";

            $stmtUpdateFerias = $mysqli->prepare($slqUpdateFerias);

            if ($stmtUpdateFerias === false) {
                die('Erro na preparação do UPTADE: ' . $mysqli->error);
            }

            $stmtUpdateFerias->bind_param("s", $novaFeriasObservacoes);

            if ($stmtUpdateFerias->execute()) {
                $mysqli->commit();
                $_statusCad = "Observação atualizada com sucesso";        
            }else {
                $mysqli->rollback();
                $_statusCad = "Erro ao atualizar: ".$mysqli->error;
            }

            header("Location: controleferias.php?id=$id");//após atualização/inserção no banco é redirecionado para a mesma página para evitar duplicidade com F5
            exit;

        }

    }
}

$id = $_GET["id"];

$sqlFerias = "SELECT controleferias.*, funcionarios.nome
                FROM controleferias 
                RIGHT JOIN funcionarios ON controleferias.funcionarioID = funcionarios.idFuncionario
                WHERE funcionarioID = $id ORDER BY STR_TO_DATE(controleferias.dataInicioUltFerias, '%d/%m/%Y') DESC";
$resultFerias = $mysqli->query($sqlFerias);

?>


<!DOCTYPE html>
<HTML lang="pt-BR">
    <HEAD>
        <TITLE>PATROL | CONTROLE FÉRIAS</TITLE>

    <?php 
    require "headContent.php";
    require "mascaraContent.php";
    ?>
    </HEAD>

    <body>

    <h4 class="center">Registro de férias:</h4>

    <br><br><br>

    <?php
    echo '<div class="center">';
    echo '<h5>'.$_statusCad.'</h6>';
    echo '</div>';
    ?>


    <div class="row col s12 container">


                        <form method="post" action="">

                            <input type="hidden" name="form_id" value="1">

                            <label for="dataInicioUltFerias" class="col s6">Data inicio das férias:</label><br>
                            <input type="tel" name="dataInicioUltFerias" id="dataInicioUltFerias" oninput="formatarData(this)" placeholder="DD/MM/AAAA">

                            <label for="dataFimUltFerias" class="col s6">Data fim das férias:</label><br>
                            <input type="tel" name="dataFimUltFerias" id="dataFimUltFerias" oninput="formatarData(this)" placeholder="DD/MM/AAAA"><br><br>

                            <textarea name="feriasObservacoes" data-length="500" class="textareaFerias" style="border-radius:6px" placeholder="OBSERVAÇÕES"></textarea>

                            <div class="center">
                               <button type="submit" class="search" >Adicionar férias</button>
                            </div>

                        </form><br><br><br>

                            <div class="">
                            <fieldset style="border-radius:10px">
                            <table>
                                    <thead>
                                        <tr>
                                            <th>Data Início</th>
                                            <th>Data Fim</th>
                                            <th>Rspnsavel pelo Cadastro</th>
                                            <th>Data e hora cadastro</th>
                                            <th>Editar observação</th>
                                        </tr>
                                    </thead>
                                        <tbody>
                                        <?php
                                        if ($resultFerias->num_rows > 0) {
                                            while ($row = $resultFerias->fetch_assoc()) {
                                                $idUltFerias = $row['idUltFerias'];
                                                $dataInicioUltFerias = $row['dataInicioUltFerias'];
                                                $dataFimUltFerias = $row['dataFimUltFerias'];
                                                $feriasObservacoes = $row['feriasObservacoes'];
                                                $responsavelCadastro = $row['nomeResponsavelCadastro'];
                                                $dataCadastroFerias = $row['dataCadastro'];                                             

                                            echo '<tr>';
                                            echo '<td><p>' . $dataInicioUltFerias . '</p></td>';
                                            echo '<td><p>' . $dataFimUltFerias . '</p></td>';
                                            echo '<td><p>' . $responsavelCadastro . '</p></td>';
                                            echo '<td><p>' . date('d/m/Y H:i:s', strtotime($dataCadastroFerias)) . '</p></td>';
                                            echo '<form method="post" id="form" action="">';
                                            echo '<input type="hidden" name="form_id" value="2">';
                                            echo '<input type="hidden" name="idFerias" value="' . $idUltFerias . '">';
                                            echo '<td><input type="text" name="novaFeriasObservacoes" value="' . $feriasObservacoes . '"></td>';
                                            echo '<td><button type="submit" class="search btn" name="salvar_alteracoes"><i class="material-icons">check</i></button></td>';
                                            echo '</form>';
                                            echo '</tr>';
                                            }

                                        }else{
                                            echo '<tr><td colspan="2">Nenhum registro de férias encontrato</td></tr>';
                                        }
                                        ?>
                                        </tbody>
                                </table>
                                </fieldset>
                            </div>

    </div>


    </body>

    <?php require "footerContent.php"; ?>

</HTML>