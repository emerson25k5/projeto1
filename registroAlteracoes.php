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

    $sql = "SELECT * FROM logalteracoes WHERE nomeUsuarioResponsavel LIKE '%$procura%' ORDER BY dataLogAlteracao DESC";
    $result = $mysqli->query($sql);

}else{

    $sql = "SELECT * FROM logalteracoes ORDER BY dataLogAlteracao DESC";
    $result = $mysqli->query($sql);

}

$mysqli->close();

?>

<!DOCTYPE html>
<HTML lang="pt-BR">
    <HEAD>
            <TITLE><?php echo NOME_EMPRESA; ?> | AUDITORIA </TITLE>

        <?php require "headContent.php"; ?>

        <script>//inicializador do modal
            
            document.addEventListener('DOMContentLoaded', function() {
            var elems = document.querySelectorAll('.modal');
            var instances = M.Modal.init(elems);
        
        });
            
        </script>

    </HEAD>

    <body >

    <h4 class="center">Registro alterações:</h4>


    <div class="col s12 container">

    <?php require "campoBuscaFuncionarioContent.php"; ?>


                        <table>
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Responsável</th>
                                    <th>Tipo</th>
                                </tr>

                                    <?php
                                    if($result->num_rows > 0){
                                    while ($row = $result->fetch_assoc()) {
                                        $idLog = $row['idLog'];
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
                                        $modalId = 'modal' . $idLog;

                                        
                                        if($idFuncionarioAlt != 0){
                                            require "conecta.php";
                                            $sql2 = "SELECT nome FROM funcionarios WHERE idFuncionario = $idFuncionarioAlt";
                                            $result2 = $mysqli->query($sql2);

                                            if($result2->num_rows > 0){
                                                $row = $result2->fetch_assoc();
                                                $nomeAlt = $row['nome'];
                                            }
                                            $mysqli->close();
                                        }

                                        
                                            //coloca corzinha e personaliza para cad tipo de log
                                            if($tipoLog == "Exlusão registro de funcionário"){
                                                $descricao = '<b style="color:red;">DADOS EXCLUIDOS</b>';
                                            }elseif($tipoLog == "Alteração dados gerais funcionario"){
                                                $descricao = '<b style="color:orange;">DADOS ANTES DA ALTERAÇÃO</b>';
                                            }elseif($tipoLog == "Cadastro novo funcionario"){
                                                $descricao = '<b style="color:green;">DADOS DO NOVO FUNCIONÁRIO</b>';
                                            }elseif($tipoLog == "Cadastro folga trabalhada"){
                                                $descricao = '<b style="color:green;">DADOS CADASTRADOS</b>';
                                            }else{
                                                $descricao = '<b style="color:orange;">DADOS ATUALIZADOS</b>';
                                            }
                                            
                                            //exibe na lista da tabela
                                            echo '<tr>';
                                            echo '<td>'.date('d/m/Y H:i:s', strtotime($dataLogAlteracao)).'</td>';
                                            echo '<td>'.$nomeUsuarioResponsavel.'</td>';
                                            echo '<td>'.$tipoLog.'</td>';
                                            echo '<td><button class="search modal-trigger" href="#'. $modalId .'"><i class="material-icons">search</i></button></td>';
                                            echo '</tr>';

                                            echo '<div id="'. $modalId . '" class="modal" style="border-radius: 10px">';
                                            echo '<div class="modal-content">';
                                            echo '<p class="center"><b>REPONSÁVEL</b></p>';
                                            echo '<p><b>Data e hora alteração:</b> '.date('d/m/Y H:i:s', strtotime($dataLogAlteracao)).'</p>';
                                            echo '<p><b>ID:</b> '.$idLogUsuarioResponsavel.'</p>';
                                            echo '<p><b>Nome:</b> '.$nomeUsuarioResponsavel.'</p>';
                                            echo '<p><b>Tipo:</b> '.$tipoLog.'</p>';

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
                                                echo '</div>';
                                                echo '<div class="modal-footer">';
                                                echo '<button class="search modal-close">Fechar</button>';
                                                echo '</div>';
                                                echo '</div>';
                                            }else{
                                                echo '<fieldset style="border-radius:10px;">';
                                                echo '<p class="center"><b>' . $descricao . '</b></p>';
                                                echo '<p><b>ID:</b> '.$idFuncionarioAlt.'</p>';
                                                echo '<p><b>Nome completo:</b> '.$nomeAlt.'</p>';
                                                echo '</fieldset>';
                                                echo '</div>';
                                                echo '<div class="modal-footer">';
                                                echo '<button class="search modal-close">Fechar</button>';
                                                echo '</div>';
                                                echo '</div>';
                                            }
                                    }
                                    }else{
                                        echo '<td colspan="2">Nenhuma alteração encontrada</td>';
                                    }         
                                    ?>
                                    <br><br>
                                </table>


    </div>


    </body>

    <?php require "footerContent.php"; ?>

</HTML>