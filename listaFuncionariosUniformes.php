<?php 

include("autenticaContent.php");
include("conecta.php");

error_reporting(E_ALL);
ini_set('display_errors', 1);

//verifica se o nivel de acesso é de adm, se n for é exibida mensagem de erro e o resto da página não carrega
if($_SESSION['nivelAcesso'] != 2) {
    echo "Acesso negado!";
    exit;
}


$sqlFunc = "SELECT * FROM funcionarios WHERE status = 1";
$result = $mysqli->query($sqlFunc);

$mysqli->close();

?>


<!DOCTYPE html>
<HTML lang="pt-BR">
    <HEAD>
        <TITLE>PATROL | CONTROLE UNIFORMES</TITLE>
    <?php 
    require "headContent.php";
    require "mascaraContent.php";
    ?>
    </HEAD>

    <body>

    <h4 class="center">Controle de uniformes</h4>

    <br><br><br>

    <div class="row col s12 container">

                            <div class="">
                            <fieldset style="border-radius:10px">
                            <table>
                                    <thead>
                                        <tr>
                                            <th><h5>Funcionário</h5></th>
                                        </tr>
                                    </thead>
                                        <tbody>
                                        <?php
                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                $idFuncionario = $row['idFuncionario'];
                                                $nomeFuncionario = $row['nome'];

                                            echo '<tr>';
                                            echo '<td><p>' . $nomeFuncionario . '</p></td>';
                                            echo '<td><a class="search btn" href="controleEntregaUniformes.php?id='. $idFuncionario .'&nome=' . $nomeFuncionario . '"><i class="material-icons">checkroom</i></a></td>';
                                            echo '</tr>';

                                            }

                                        }else{
                                            echo '<tr><td colspan="2">Nenhum funcionário encontrado</td></tr>';
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