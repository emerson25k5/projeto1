<?php

require "autenticaContent.php";
require "conecta.php";


if($_SESSION['nivelAcesso'] != 2) {
    echo "Acesso negado!";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $_statusCadCargo = "";
    $novoStatus = "";

    //processa a inserção de um novo cargo e insere no BD
    if (isset($_POST["form_id"])) {
        $form_id = $_POST["form_id"];

        if ($form_id == 1) {
            
            try{//try para verificar se existe cargo com este nome

            if (isset($_POST["insere_cargo"])) {

                $nomeCargo = $_POST["nomeCargo"];

                $cadastrarCargo = $mysqli->query("INSERT INTO cargos (nomeCargo) VALUES ('$nomeCargo')");

                $_statusCadCargo = ($cadastrarCargo) ? "Cadastro realizado com sucesso!" . $mysqli->error : "Falha ao realizar o cadastro!" ." Código do erro:  " . $mysqli->error;
            }

            header("Location: cadastroCargo.php");//após atualização/inserção no banco é redirecionado para a mesma página para evitar duplicidade com F5
            exit;

        }catch (mysqli_sql_exception $e) {//catch para exibir alert caso tenha cargo com o mesmo nome cadastrada
            if ($e->getCode() == 1062) {
                echo '<script>alert("Já existe um cargo com este nome!");</script>';
                echo "<script>setTimeout(function(){ window.location.href = 'cadastroCargo.php'; }, 100);</script>";
            } else {
                echo '<script>alert("Falha ao atualizar dados: "</script>'.$e->getMessage();
            }
        }
        }

        //processa a atualização dos cargos individualmente
        if ($form_id == 2) {

            try{

            if (isset($_POST["salvar_alteracoes"])) {

                $mysqli->begin_transaction();

                // Processar os dados do formulário
                $idCargo = $_POST['idCargo'];
                $novoNomeCargo = $_POST['novoNomeCargo'];

                if(isset($_POST['novoStatus'])){    //verifica o valor enviado pelo check box (switch) se for checked, envia 1 para o banco, se não envia 0 para o banco na coluna status
                    if($_POST['novoStatus'] == "on"){
                        $novoStatus = 1;
                    }else{
                        $novoStatus = 0;
                    }
                }


                
                // Executar a atualização no banco de dados
                $sql = "UPDATE cargos SET nomeCargo=?, status=? WHERE idCargo = $idCargo";

                $stmt = $mysqli->prepare($sql);

                if ($stmt === false) {
                    die($_statusCadCargo = 'Erro na preparação da consulta: ' . $mysqli->error);
                }

                $stmt->bind_param("si", $novoNomeCargo, $novoStatus);

                if ($stmt->execute()) {
                    $mysqli->commit();
                    $_statusCadCargo = "Cadastro atualizado com sucesso!" . $mysqli->error;
                } else {
                    $mysqli->rollback();
                    $_statusCadCargo = "Falha ao realizar o cadastro!" ." Código do erro:  " . $mysqli->error;
                }

                header("Location: cadastroCargo.php");//após atualização/inserção no banco é redirecionado para a mesma página para evitar duplicidade com F5
                exit;

            }

        }catch (mysqli_sql_exception $e) {//catch para exibir alert caso tenha cargo com o mesmo nome cadastrada
            if ($e->getCode() == 1062) {
                echo '<script>alert("Já existe um cargo com este nome!");</script>';
                echo "<script>setTimeout(function(){ window.location.href = 'cadastroCargo.php'; }, 100);</script>";
            } else {
                echo '<script>alert("Falha ao atualizar dados: "</script>'.$e->getMessage();
            }
        }

        }
    }

}

$sql = "SELECT * FROM cargos ORDER BY nomeCargo";
$result = $mysqli->query($sql);

$mysqli->close();

?>

<!DOCTYPE html>
<HTML lang="pt-BR">
    <HEAD>
        <TITLE>EBDS | Cadastro Cargo</TITLE>

        <?php 

        require "headContent.php"; 
        require "funcoes.php";
        
        ?>

    </HEAD>
    <body>
        <main class="box container">


            <div class="container center">

                <h4>CADASTRO DE CARGOS</h4>

                <BR>

                <form method="post" action="">

                    <div class="input-field col s12">
                    <i class="material-icons prefix">engineering</i>
                    <input type="hidden" name="form_id" value="1">
                    <input type="text" name="nomeCargo" id="nomeCargo" maxlength="25" class="validate" oninput="converterParaCaixaAlta(this)" required>
                    <label for="nomeCargo">Nome do novo cargo</label>
                    </div>
                    
                    <br><br>

                    <button type="submit" name="insere_cargo" value="insere_cargo" class="search btn">ADICIONAR NOVO CARGO</button>

                    <br><br>

                </form>
                
            </div>

            <BR>

            <div class="mostraCargo col s12">
                <table>
                    <thead>
                        <tr>
                            <th>Nome do Cargo</th>
                            <th>Desativar/Ativar</th>
                        </tr>
                    </thead>
                        <tbody>
                            <?php
                            // Verifique se há registros e gere as linhas da tabela
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $idCargo = $row['idCargo'];
                                    $nome = $row['nomeCargo'];
                                    $dataCadastro = $row['dataCadastro'];
                                    $status = $row['status'];
                                    echo '<tr>';
                                    echo '<form method="post" action="">';
                                    echo '<input type="hidden" name="form_id" value="2">';
                                    echo '<input type="hidden" name="idCargo" value="'. $idCargo.'">';
                                    echo '<td><input type="text" name="novoNomeCargo" value="' . $nome . '" oninput="converterParaCaixaAlta(this)"></td>';
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
                                    echo '<tr>';
                                }
                            } else {
                                echo '<tr><td colspan="2">Nenhum cargo encontrado</td></tr>';
                            }
                            ?>
                        </tbody>
                </table>
            </div>

            <br>

            <h6><b>DICAS:</b></h6>
            <p><b>Inativar cargos:</b> Ao inativar um cargo, não será possível associar este cargo a nenhum funcionário novo. Os que foram anteriormente associados permanecerão com ele atribuído.
            Para alterar o cargo de um funcionário, acesse a opção <a href="listaFuncionarios.php"><b>Funcionarios</b></a> no menu à esquerda.</p>
            <p><b>Nome do cargo:</b> Ao atualizar o nome de um cargo, TODOS os Funcionarios a ele associados serão afetados pela atualização.</p>

            <br><br><br>

        </main>

        <?php 
        require("mascaraContent.php"); //adiciona o conteúdo JS para caixa alta dentre outros
        require "footerContent.php";
        ?> <!--adiciona o conteúdo do rodapé de modo modular usando o INCLUDE em PHP-->

    </body>
</HTML>