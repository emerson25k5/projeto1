<?php

require("autenticaContent.php");
require("conecta.php");

if($_SESSION['nivelAcesso'] != 2) {
    echo "Acesso negado!";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $_statusCad = "";
    $novoStatus = "";

    if (isset($_POST["form_id"])) {
        $form_id = $_POST["form_id"];

        if ($form_id == 1) {

            if (isset($_POST["insere_unidade"])) {

                $nomeUnidade = $_POST["nomeUnidade"];

                $cadastrarUnidade = $mysqli->query("INSERT INTO unidades (nomeUnidade) VALUES ('$nomeUnidade')");

                $_statusCad = ($cadastrarUnidade) ? "Cadastro realizado com sucesso!" . $mysqli->error : "Falha ao realizar o cadastro!" ." Código do erro:  " . $mysqli->error;
            }
        }

            //processa a atualização da unidades individualmente
        if ($form_id == 2) {

            if (isset($_POST["salvar_alteracoes"])) {
    
                    $mysqli->begin_transaction();
    
                    // Processar os dados do formulário
                    $idUnidade = $_POST['idUnidade'];
                    $novoNomeUnidade = $_POST['novoNomeUnidade'];
    
                    if(isset($_POST['novoStatus'])){    //verifica o valor enviado pelo check box (switch) se for "on", a varíavel ganha valor 1 se não 0
                        if($_POST['novoStatus'] == "on"){
                            $novoStatus = 1;
                        }else{
                            $novoStatus = 0;
                        }
                    }


                // Executar a atualização no banco de dados
                $sql = "UPDATE unidades SET nomeUnidade=?, status=? WHERE idUnidade = $idUnidade";

                $stmt = $mysqli->prepare($sql);

                if ($stmt === false) {
                    die($_statusCad = 'Erro na preparação da consulta: ' . $mysqli->error);
                }

                $stmt->bind_param("si", $novoNomeUnidade, $novoStatus);

                if ($stmt->execute()) {
                    $mysqli->commit();
                    $_statusCad = "Cadastro atualizado com sucesso!";
                } else {
                    $mysqli->rollback();
                    $_statusCad = "Falha ao realizar o cadastro!" ." Código do erro:  " . $mysqli->error;
                }

            }
        }
    }

}


$sql = "SELECT * FROM unidades ORDER BY nomeUnidade";
$result = $mysqli->query($sql);

?>

<!DOCTYPE html>
<HTML lang="pt-BR">
    <HEAD>
        <TITLE>EBDS | Cadastro Unidade</TITLE>

        <?php 
        
        require "headContent.php";
        require "funcoes.php";

        ?>

    </HEAD>
    <body>
        <main class="box container">


            <div class="container center">

                <h4>CADASTRO DE UNIDADES</h4>

                <BR>

                <form method="post" action="">

                    <div class="input-field col s12">
                    <i class="material-icons prefix">maps_home_work</i>
                    <input type="hidden" name="form_id" value="1">
                    <input type="text" name="nomeUnidade" id="nomeUnidade" maxlength="25" class="validate" required>
                    <label for="nomeUnidade">Nome da nova Unidade</label>
                    </div>
                    
                    <br><br>

                    <button type="submit" name="insere_unidade" value="insere_unidade" class="search btn">ADICIONAR NOVA UNIDADE</button>

                    <br><br>

                </form>
                
            </div>

            <BR>

            <div class="mostraUnidade col s12">
                <table>
                    <thead>
                        <tr>
                            <th>Nome da Unidade</th>
                            <th>Desativar/Ativar</th>
                        </tr>
                    </thead>
                        <tbody>
                            <?php
                            // Verifique se há registros e gere as linhas da tabela
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $idUnidade = $row['idUnidade'];
                                    $nome = $row['nomeUnidade'];
                                    $dataCadastro = $row['dataCadastro'];
                                    $status = $row['status'];
                                    echo '<tr>';
                                    echo '<form method="post" action="">';
                                    echo '<input type="hidden" name="form_id" value="2">';
                                    echo '<input type="hidden" name="idUnidade" value="'. $idUnidade.'">';
                                    echo '<td><input type="text" name="novoNomeUnidade" value="' . $nome . '"></td>';
                                    echo '<td>';
                                    echo '<div class="switch">';
                                    echo '<label>Inativo';
                                    switch ($status){
                                        case 1:
                                            echo '<input type="checkbox" name="novoStatus" checked>';
                                            break;
                                        case 0:
                                            echo '<input type="checkbox" name="novoStatus">';
                                            break;
                                    }
                                    echo '<span class="lever"></span>';
                                    echo 'Ativo</label>';
                                    echo '</div>';
                                    echo '</td>';
                                    echo '<td><button type="submit" class="search btn" name="salvar_alteracoes"><i class="material-icons">check</i></button></td>';
                                    echo '</form>';
                                    echo '</tr>';
                                }
                            } else {
                                echo '<tr><td colspan="2">Nenhum Unidade encontrada</td></tr>';
                            }
                            ?>
                        </tbody>
                </table>
            </div>

            <br>

            <h6><b>DICAS:</b></h6>
            <p><b>Inativar unidades:</b> Ao inativar um unidade, não será possível associar esta unidade a nenhum funcionário novo. Os que foram anteriormente associados permanecerão com ela atribuída.
            Para alterar a unidade de um funcionário, acesse a opção <a href="listaFuncionarios.php"><b>Funcionarios</b></a> no menu à esquerda.</p>
            <p><b>Nome da unidade:</b> Ao atualizar o nome de uma unidade, TODOS os Funcionarios a ela associados serão afetados pela atualização.</p>

            <br><br><br>

        </main>

        <?php include("footerContent.php");?> <!--adiciona o conteúdo do rodapé de modo modular usando o INCLUDE em PHP-->

    </body>
</HTML>