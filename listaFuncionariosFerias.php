<?php 

include("autenticaContent.php");
include("conecta.php");
require "configuracoes.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

//verifica se o nivel de acesso é de adm, se n for é exibida mensagem de erro e o resto da página não carrega
if($_SESSION['nivelAcesso'] != 2) {
    echo "Acesso negado!";
    exit;
}

if(isset($_GET['procura'])){

    $procura = $_GET['procura'];

    $sqlFunc = "SELECT * FROM funcionarios WHERE status = 1 AND nome LIKE '%$procura%'";
    $result = $mysqli->query($sqlFunc);
    
}else{

$sqlFunc = "SELECT * FROM funcionarios WHERE status = 1";
$result = $mysqli->query($sqlFunc);

}

$mysqli->close();

?>


<!DOCTYPE html>
<HTML lang="pt-BR">
    <HEAD>
        <TITLE><?php echo $_SESSION['nomeEmpresa']; ?> | FÉRIAS</TITLE>
    <?php 
    require "headContent.php";
    require "mascaraContent.php";
    ?>
    </HEAD>

    <body>

    <h4 class="center">Controle de férias</h4>

    <br><br><br>

    <div class="row col s12 container">

         <?php require "campoBuscaFuncionarioContent.php"; ?>

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
                                            echo '<td><a class="search btn" href="controleFerias.php?id='. $idFuncionario .'&nome='.$nomeFuncionario.'"><i class="material-icons">beach_access</i></a></td>';
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