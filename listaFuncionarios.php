<?php

include("autenticaContent.php");

include("conecta.php");

$sql = "SELECT funcionarios.idFuncionario, funcionarios.nome, funcionarios.status, cargos.nomeCargo, unidades.nomeUnidade
        FROM funcionarios
        LEFT JOIN usedunidades ON funcionarios.idFuncionario = usedunidades.funcionarioID
        LEFT JOIN usedcargos ON funcionarios.idFuncionario = usedcargos.funcionarioID
        LEFT JOIN unidades ON usedunidades.unidadeID = unidades.idUnidade
        LEFT JOIN cargos ON usedcargos.cargoID = cargos.idCargo";
$result = $mysqli->query($sql);

?>

<!DOCTYPE html>
<HTML lang="pt-BR">
    <HEAD>
        <TITLE>PATROL | Funcionários </TITLE>

        <?php include("headContent.php"); ?>

    </HEAD>
    <body>

        <BR><BR>
        
    <div class="center">
    <form action="" method="post">
    <input class="center" type="text" id="busca" name="busca" placeholder="Buscar funcionários" style="width:50%; background-color:;"><br>
    <input class="search center btn" type="submit" id="submit" value="Buscar (em desenvolvimento)">
    </form>
    </div>

        <main class="box container">

            <div>
                <table class="highlight">
                    <thead>
                    <tr>
                        <th></th>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Cargo</th>
                        <th>Unidade</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $idFuncionario = $row['idFuncionario'];
                        $cargo = $row['nomeCargo'];
                        $unidade = $row['nomeUnidade'];
                        $nome = $row['nome'];
                        $status = $row['status'];
                        $modalId = 'modal_' . $idFuncionario;

                        echo '<tr>';
                        echo '<td><a class="waves-effect waves-light modal-trigger" href="userInfo.php?id=' . $idFuncionario . '"><i class="btnopcao material-icons left">search</i></a><a class="waves-effect waves-light modal-trigger" href="editaFuncionario.php?id=' . $idFuncionario . '"><i class="btnopcao material-icons left">edit</i></a><a class="waves-effect waves-light modal-trigger" href="#'. $modalId . '"><i class="btnopcao material-icons left">delete</i></a></td>';
                        echo '<td>'. $idFuncionario .'</td>';
                        echo '<td>'. $nome .'</td>';
                        echo '<td>'. $cargo .'</td>';
                        echo '<td>'. $unidade .'</td>';
                        if($status == 1){
                            echo '<td>Ativo</td>';
                        }else{
                            echo '<td>Inativo</td>';
                        }
                        echo '</tr>';

                        echo '<div id="' . $modalId . '" class="modal">';
                        echo '<div class="modal-content">';
                        echo '<h4>Exclusão de funcionário</h4>';
                        echo '<p>Tem certeza que deseja prosseguir? Esta ação não poderá ser desfeita.</p>';
                        echo '</div>';
                        echo '<div class="modal-footer">';
                        echo '<a href="excluiFuncionario.php?idFuncionario=' . $idFuncionario . '" class="modal-close waves-effect waves-green btn-flat">EXCLUIR</a>';
                        echo '<a href="#" class="modal-close waves-effect waves-green btn-flat">CANCELAR</a>';
                        echo '</div>';
                        echo '</div>';

                    }
                }else {
                    echo '<p colspan="2">Nenhum funcionário encontrado</p>';
                }                   
                ?>

                    <script>
                    document.addEventListener('DOMContentLoaded', function() {
                    var elems = document.querySelectorAll('.modal');
                    var instances = M.Modal.init(elems);
                });
                    </script>

                <tbody>
                </table>

        </main>

        <?php include("footerContent.php");?> <!--adiciona o conteúdo do rodapé de modo modular usando o INCLUDE em PHP-->

    </body>
</HTML>