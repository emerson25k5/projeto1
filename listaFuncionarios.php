<?php
session_start();

if (!isset($_SESSION["authenticated"]) || $_SESSION["authenticated"] !== true) {
    header("Location: index.php");
    exit;
}


include("conecta.php");

$sql = "SELECT * FROM funcionarios ORDER BY nome";
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
    <input class="serach center btn" type="submit" id="submit" value="Buscar">
    </form>
    </div>

        <main class="box container">

            <div>
                <table class="highlight">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Nome</th>
                        <th>CPF</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $idFuncionario = $row['idFuncionario'];
                        $nome = $row['nome'];
                        $cpf = $row['cpf'];
                        $status = $row['status'];

                        echo '<tr>';
                        echo '<td><a class="waves-effect waves-light modal-trigger" href="userInfo.php?id=' . $idFuncionario . '"><i class="btnopcao material-icons left">search</i></a><a class="waves-effect waves-light modal-trigger" href="editaFuncionario.php?id=' . $idFuncionario . '""><i class="btnopcao material-icons left">edit</i></a><a class="waves-effect waves-light modal-trigger" href="#modal1"><i class="btnopcao material-icons left">delete</i></a></td>';
                        echo '<td>'. $nome .'</td>';
                        echo '<td>'. $cpf .'</td>';
                        if($status == 1){
                            echo '<td>Ativo</td>';
                        }else{
                            echo '<td>Inativo</td>';
                        }
                        echo '</tr>';

                        echo '<div id="modal1" class="modal">';
                        echo '<div class="modal-content">';
                        echo '<h4>Exclusão de funcionário</h4>';
                        echo '<p>Tem certeza que deseja prosseguir? Esta ação não poderá ser desfeita.</p>';
                        echo '</div>';
                        echo '<div class="modal-footer">';
                        echo '<a href="excluiFuncionario.php?id=' . $idFuncionario . '" class="modal-close waves-effect waves-green btn-flat">EXCLUIR</a>';
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