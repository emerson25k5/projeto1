<?php

include("autenticaContent.php");
include("conecta.php");

if($_SESSION['nivelAcesso'] != 2) {
    echo "Acesso negado!";
    exit;
}

$funcionarioLogado = $_SESSION['idFuncionarioLogado'];

if($_SESSION['nivelAcesso'] == 2) {

$sql = "SELECT funcionarios.idFuncionario, funcionarios.nome, funcionarios.status, cargos.nomeCargo, unidades.nomeUnidade
        FROM funcionarios
        LEFT JOIN usedunidades ON funcionarios.idFuncionario = usedunidades.funcionarioID
        LEFT JOIN usedcargos ON funcionarios.idFuncionario = usedcargos.funcionarioID
        LEFT JOIN unidades ON usedunidades.unidadeID = unidades.idUnidade
        LEFT JOIN cargos ON usedcargos.cargoID = cargos.idCargo ORDER BY funcionarios.nome";
$result = $mysqli->query($sql);
}else{
    $sql = "SELECT funcionarios.idFuncionario, funcionarios.nome, funcionarios.status, cargos.nomeCargo, unidades.nomeUnidade
    FROM funcionarios
    LEFT JOIN usedunidades ON usedunidades.funcionarioID = funcionarios.idFuncionario
    LEFT JOIN usedcargos ON usedcargos.funcionarioID = funcionarios.idFuncionario
    LEFT JOIN unidades ON unidades.idUnidade = usedunidades.unidadeID
    LEFT JOIN cargos ON cargos.idCargo = usedcargos.cargoID
    WHERE funcionarios.idFuncionario = $funcionarioLogado ORDER BY funcionarios.nome";
    $result = $mysqli->query($sql);
}

$mysqli->close();

?>

<!DOCTYPE html>
<HTML lang="pt-BR">
    <HEAD>
        <TITLE>PATROL | Funcionários </TITLE>

        <?php
        require "headContent.php"; 
        require "funcoes.php";
        ?>


    </HEAD>
    <body>

        <BR>
        

        <main class="box container">
            
            <h4>Funcionarios</h4>

            <div>
                <table class="highlight">
                    <thead>
                    <tr>
                        <th></th>
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


                        //exibe os dados
                        echo '<tr>';
                        echo '<td><a class="dropdown-trigger" href="#" data-target="drop'.$idFuncionario.'"><i class="btnopcao material-icons">more_vert</i></a></td>';
                        echo '<td>'. $nome .'</td>';
                        echo '<td>'. $cargo .'</td>';
                        echo '<td>'. $unidade .'</td>';
                        echo '<td>'.traduz_status($status).'</td>';
                        echo '</tr>';


                        //modal de confirmação antes de excluir
                        echo '<div id="'. $modalId .'" class="modal" style="border-radius: 10px">';
                        echo '<div class="modal-content">';
                        echo '<h4>Exclusão de funcionário</h4>';
                        echo '<p>Tem certeza que deseja prosseguir? Esta ação não poderá ser desfeita.</p>';
                        echo '</div>';
                        echo '<div class="modal-footer">';
                        echo '<a href="excluiFuncionario.php?idFuncionario=' . $idFuncionario . '" class="modal-close waves-effect waves-green btn-flat">EXCLUIR</a>';
                        echo '<a href="#" class="modal-close waves-effect waves-green btn-flat">CANCELAR</a>';
                        echo '</div>';
                        echo '</div>';

                        //dropdown da lista para excluir, editar e exibir informações dos funcionarios
                        echo '<ul id="drop'.$idFuncionario.'" class="options_list_user dropdown-content z-depth-4"';
                        echo '<li>';
                        echo '<a class="opt" href="userInfo.php?id='.$idFuncionario.'"><i class="opt material-icons">person_search</i></a>';
                        echo '<a class="opt" href="editaFuncionario.php?id='.$idFuncionario.'"><i class="opt material-icons">edit</i></a>';
                        echo '<a class="opt modal-trigger" href="#'.$modalId.'"><i class="opt material-icons">delete_forever</i></a>';
                        echo '</li>';
                        echo '</ul>';

                    }
                }else {
                    echo '<p colspan="2">Nenhum funcionário encontrado</p>';
                }                   
                ?>

                    <script>//js para iniciar o MODAL
                        document.addEventListener('DOMContentLoaded', function() {
                        var elems = document.querySelectorAll('.modal');
                        var instances = M.Modal.init(elems);
                        });
                        
                    //js para iniciar o MODAL
                    document.addEventListener('DOMContentLoaded', function() {
                        var elems = document.querySelectorAll('.fixed-action-btn');
                        var instances = M.FloatingActionButton.init(elems, {
                        direction: 'left',
                        hoverEnabled: false
                        });
                    });
                    </script>

                <tbody>
                </table>

        </main>

        <?php include("footerContent.php");?> <!--adiciona o conteúdo do rodapé de modo modular usando o INCLUDE em PHP-->

    </body>
</HTML>