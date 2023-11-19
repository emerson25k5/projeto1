<?php 

include("autenticaContent.php");
include("conecta.php");
require "configuracoes.php";

//verifica se o nivel de acesso é de adm, se n for é exibida mensagem de erro e o resto da página não carrega
if($_SESSION['nivelAcesso'] != 2) {
    echo "Acesso negado!";
    exit;
}

//select com base no ID do funcionario para trazer as férias dele
if (isset($_GET["id"])) {
    // Recupere o ID do registro a ser exibido
    $id = $_GET["id"];
    $nome = $_GET["nome"];
}

//post insert para enviar novo cadastro de férias
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    date_default_timezone_set('America/Sao_Paulo');
    $dataHoraAtual = new DateTime();

    $id = $_GET["id"];

    if (isset($_POST["form_id"]) || (isset($_POST['salvar_alteracoes']))){
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

        }

    }
}

$id = $_GET["id"];

$sqlFerias = "SELECT controleferias.*, funcionarios.nome
                FROM controleferias 
                RIGHT JOIN funcionarios ON controleferias.funcionarioID = funcionarios.idFuncionario
                WHERE funcionarioID = $id ORDER BY STR_TO_DATE(controleferias.dataInicioUltFerias, '%d/%m/%Y') DESC";
$resultFerias = $mysqli->query($sqlFerias);

$mysqli->close();

?>


<!DOCTYPE html>
<HTML lang="pt-BR">
    <HEAD>
        <TITLE><?php echo NOME_EMPRESA; ?> | CONTROLE FÉRIAS</TITLE>
    <?php 
    require "headContent.php";
    require "mascaraContent.php";
    ?>

    <script>//inicializador do modal
        
        document.addEventListener('DOMContentLoaded', function() {
        var elems = document.querySelectorAll('.modal');
        var instances = M.Modal.init(elems);
    
      });
        
    </script>
    
    <style>

        fieldset {
            border-radius: 10px;
            align-items: center;
        }

        textarea {
            border-radius: 10px;
            height: 40px !important;
        }
    </style>

    </HEAD>

    <body>

    <h4 class="center">Registro de férias</h4>

    <br><br>

    <div class="row col s12 container">

                    <fieldset>

                    <h5 class="center"><?php echo $nome;?></h5>

                    <br>

                        <form method="post" action="">

                            <input type="hidden" name="form_id" value="1">

                            <label for="dataInicioUltFerias" class="col s6">Data inicial:</label><br>
                            <input type="tel" name="dataInicioUltFerias" id="dataInicioUltFerias" oninput="formatarData(this)" placeholder="DD/MM/AAAA" required>

                            <label for="dataFimUltFerias" class="col s6">Data final:</label><br>
                            <input type="tel" name="dataFimUltFerias" id="dataFimUltFerias" oninput="formatarData(this)" placeholder="DD/MM/AAAA" required><br><br>

                            <textarea name="feriasObservacoes" data-length="500" class="textareaFerias" style="border-radius:6px; height: 100px" placeholder="OBSERVAÇÕES"></textarea>
                            <br><br>

                            <div class="center">
                               <button type="submit" class="search">Adicionar período de férias</button>
                            </div>

                        </form><br><br><br>

                    </fieldset>

                            <div class="">

                            <br>

                                    <h5>Histórico de férias</h5>

                                    <br>

                            <fieldset style="border-radius:10px">
                            <table>
                                    <thead>
                                        <tr>
                                            <th>Funcionário</th>
                                            <th>Data Início</th>
                                            <th>Data Fim</th>
                                        </tr>
                                    </thead>
                                        <tbody>
                                        <?php
                                        if ($resultFerias->num_rows > 0) {
                                            while ($row = $resultFerias->fetch_assoc()) {
                                                $idUltFerias = $row['idUltFerias'];
                                                $nomeFuncionario = $row['nome'];
                                                $dataInicioUltFerias = $row['dataInicioUltFerias'];
                                                $dataFimUltFerias = $row['dataFimUltFerias'];
                                                $feriasObservacoes = $row['feriasObservacoes'];
                                                $responsavelCadastro = $row['nomeResponsavelCadastro'];
                                                $dataCadastroFerias = $row['dataCadastro'];
                                                $modalId = 'modal' . $idUltFerias;

                                            echo '<tr>';
                                            echo '<td><p>' . $nomeFuncionario . '</p></td>';
                                            echo '<td><p>' . $dataInicioUltFerias . '</p></td>';
                                            echo '<td><p>' . $dataFimUltFerias . '</p></td>';
                                            echo '<td><button class="search modal-trigger" href="#'. $modalId .'"><i class="material-icons">search</i></button></td>';
                                            echo '</tr>';

                                                                                #modal para exibir as informações de forma individual
                                            echo '<div id="'. $modalId . '" class="modal" style="border-radius: 10px">';
                                            echo '<div class="modal-content">';
                                            echo '<h5>' . $nomeFuncionario . '</h5>';
                                            echo '<form method="post" id="form" action="">';
                                            echo '<input type="hidden" name="form_id" value="2">';
                                            echo '<input type="hidden" name="idFerias" value="' . $idUltFerias . '">';
                                            echo '<p><b>Data inicial: </b>' . $dataInicioUltFerias . '</p>';
                                            echo '<p><b>Data final: </b>' . $dataFimUltFerias . '</p>';
                                            echo '<b>Observações:</b><textarea name="novaFeriasObservacoes">' . $feriasObservacoes. '</textarea>';
                                            echo '<p><b>Responsável pelo cadastro:</b></p>';
                                            echo '<p>' . $responsavelCadastro . '</p>';
                                            echo '<p>' . date('d/m/Y H:i:s', strtotime($dataCadastroFerias)) . '</p>';
                                            echo '</div>';
                                            echo '<div class="modal-footer">';
                                            echo '<button type="submit" href="#!" class="search modal-close" name="salvar_alteracoes">Salvar</button>';
                                            echo '</div>';
                                            echo '</form>';
                                            echo '</div>';
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