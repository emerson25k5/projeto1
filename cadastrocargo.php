<?php

session_start();

if (!isset($_SESSION["authenticated"]) || $_SESSION["authenticated"] !== true) {
    header("Location: index.php");
    exit;
}

include("conecta.php");

$_statusCadCargo = "";

$_POST;
if($_POST){

$nomeCargo = $_POST["nomeCargo"];

$cadastrarCargo = $mysqli->query("INSERT INTO cargos (nomeCargo) VALUES ('$nomeCargo')");

$_statusCadCargo = ($cadastrarCargo) ? "Cadastro realizado com sucesso!" . $mysqli->error : "Falha ao realizar o cadastro!" ." Código do erro:  " . $mysqli->error;
}

$sql = "SELECT * FROM cargos ORDER BY nomeCargo";
$result = $mysqli->query($sql);

?>

<!DOCTYPE html>
<HTML lang="pt-BR">
    <HEAD>
        <TITLE>EBDS | Cadastro Cargo</TITLE>

        <?php include("headContent.php"); ?>

    </HEAD>
    <body>
        <main class="box container">


            <div class="container center">

                <h4>CADASTRO DE CARGOS</h4>

                <BR>

                <form method="post" action="">

                    <div class="input-field col s12 left">
                    <i class="material-icons prefix">maps_home_work</i>
                    <input type="text" name="nomeCargo" id="nomeCargo" maxlength="25" class="validate" required autofocus>
                    <label for="nomeCargo">Nome do cargo</label>
                    </div>
                    
                    <br><br><br><br>

                    <input type="submit" name="entrar" value="Adicionar" class="btnopcao1 btn">


                    <br><br>

                    <div class="statusCadCargo center" id="statusCadCargo"> <!-- div para exibir se o cadastro foi realizado ou não e então exibir o erro -->
                        <?php echo $_statusCadCargo;?>
                    </div>

                </form>
                
            </div>

            <BR>

            <div class="mostraCargo col s12">
                <table>
                    <thead>
                        <tr>
                            <th>Nome do Cargo</th>
                            <th>Data de cadastro</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                        <tbody>
                            <?php
                            // Verifique se há registros e gere as linhas da tabela
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $nome = $row['nomeCargo'];
                                    $dataCadastro = $row['dataCadastro'];
                                    $status = $row['status'];
                                    echo '<tr>';
                                    echo '<td>' . $nome . '</td>';
                                    echo '<td>' . $dataCadastro . '</td>';
                                    if($status == 1){
                                        echo '<td>Ativo</td>';
                                    }else {
                                        echo '<td>Inativo</td>';
                                    };
                                    echo '</tr>';
                                }
                            } else {
                                echo '<tr><td colspan="2">Nenhum cargo encontrado</td></tr>';
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