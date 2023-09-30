<?php

include("autenticaContent.php");

include("conecta.php");

$_statusCadUnidade = "";

$_POST;
if($_POST){

$nomeUnidade = $_POST["nomeUnidade"];

$cadastrarUnidade = $mysqli->query("INSERT INTO unidades (nomeUnidade) VALUES ('$nomeUnidade')");

$_statusCadUnidade = ($cadastrarUnidade) ? "Cadastro realizado com sucesso!" . $mysqli->error : "Falha ao realizar o cadastro!" ." Código do erro:  " . $mysqli->error;
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

                    <div class="input-field col s12 left">
                    <i class="material-icons prefix">maps_home_work</i>
                    <input type="text" name="nomeUnidade" id="nomeUnidade" maxlength="25" class="validate" required autofocus>
                    <label for="nomeUnidade">Nome da Unidade</label>
                    </div>
                    
                    <br><br><br><br>

                    <input type="submit" name="entrar" value="Adicionar" class="btnopcao1 btn">


                    <br><br>

                    <div class="statusCadUnidade center" id="statusCadUnidade"> <!-- div para exibir se o cadastro foi realizado ou não e então exibir o erro -->
                        <?php echo $_statusCadUnidade;?>
                    </div>

                </form>
                
            </div>

            <BR>

            <div class="mostraUnidade col s12">
                <table>
                    <thead>
                        <tr>
                            <th>Nome da Unidade</th>
                            <th>Data de cadastro</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                        <tbody>
                            <?php
                            // Verifique se há registros e gere as linhas da tabela
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $nome = $row['nomeUnidade'];
                                    $dataCadastro = $row['dataCadastro'];
                                    $status = $row['status'];
                                    echo '<tr>';
                                    echo '<td>' . $nome . '</td>';
                                    echo '<td>' . $dataCadastro . '</td>';
                                    echo '<td>' . traduz_status($status) . '</td>';
                                    echo '</tr>';
                                }
                            } else {
                                echo '<tr><td colspan="2">Nenhum Unidade encontrada</td></tr>';
                            }
                            ?>
                        </tbody>
                </table>
            </div>

            <br><br><br>

        </main>

        <?php include("footerContent.php");?> <!--adiciona o conteúdo do rodapé de modo modular usando o INCLUDE em PHP-->

    </body>
</HTML>