<?php 
include("config.php");
include("autenticaContent.php");
include("conecta.php");

if($_SESSION['nivelAcesso'] != 2) {
    echo "Acesso negado!";
    exit;
}

if(isset($_GET['procura'])){
    $procura = $_GET['procura'];

    $sql = "SELECT historicoacesso.*, funcionarios.nome
    FROM funcionarios
    LEFT JOIN historicoacesso ON funcionarios.idFuncionario = historicoacesso.funcionarioID
    WHERE historicoacesso.tipoacesso IS NOT NULL AND funcionarios.nome LIKE '%$procura%'
    ORDER BY dataCadastro DESC";

    $result = $mysqli->query($sql);

}else{

$sql = "SELECT historicoacesso.*, funcionarios.nome
        FROM funcionarios
        LEFT JOIN historicoacesso ON funcionarios.idFuncionario = historicoacesso.funcionarioID
        WHERE historicoacesso.tipoacesso IS NOT NULL ORDER BY dataCadastro DESC";

$result = $mysqli->query($sql);

}

$mysqli->close();

?>

<!DOCTYPE html>
<HTML lang="pt-BR">
    <HEAD>
            <TITLE><?php echo NOME_EMPRESA; ?> | HISTÓRICO DE ACESSOS</TITLE>

        <?php require "headContent.php"; ?>

    </HEAD>

    <body >

    <h4 class="center">Registro de acessos:</h4>


    <div class="col s12 container">

    <?php require "campoBuscaFuncionarioContent.php"; ?>


                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Data acesso</th>
                                    <th>Funcionário</th>
                                    <th>Tipo</th>
                                    <th>Dispositivo</th>
                                </tr>

                                    <?php
                                    if($result->num_rows > 0){
                                    while ($row = $result->fetch_assoc()) {
                                        $idAcesso = $row['idAcesso'];
                                        $dataCadastro = $row['dataCadastro'];
                                        $nome = $row['nome'];
                                        $tipoAcesso = $row['tipoAcesso'];
                                        $ip = $row['enderecoIp'];
                                        $dispositivo = $row['dispositivo'];
                                                                                    
                                            //exibe na lista da tabela
                                            echo '<tr>';
                                            echo '<td>' . $idAcesso . '</td>';
                                            echo '<td>' . date('d/m/Y H:i:s', strtotime($dataCadastro)) . '</td>';
                                            echo '<td>' . $nome . '</td>';
                                            echo '<td>'. $tipoAcesso.'</td>';
                                            echo '<td>' . $dispositivo .'</td>';
                                            echo '</tr>';
                                    }
                                    }else{
                                        echo '<p colspan="2">Nenhum(a) acesso/tentativa registrado(a)</p>';
                                    }         
                                    ?>
                                    <br><br>
                                </table>


    </div>


    </body>

    <?php require "footerContent.php"; ?>

</HTML>