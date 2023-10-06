<?php

include("autenticaContent.php");

if($_SESSION['nivelAcesso'] != 2) {
    echo "Acesso negado!";
    exit;
}

$_statusCad = "";
$usuario_selecionado = "";
$perfil_selecionado = "";
$usuarioResponsavelPelaAlteracao = $_SESSION['nomeUsuario']; 

include("conecta.php");

if(array_key_exists('usuario_selecionado', $_POST) && array_key_exists('perfil_selecionado', $_POST)){


    $mysqli->begin_transaction();

    $usuario_selecionado = $_POST['usuario_selecionado'];
    $perfil_selecionado = $_POST['perfil_selecionado'];

    $associaPerfil = $mysqli->prepare("INSERT INTO usedperfilacesso (usuarioID, nivelPerfilID, responsavelAssociacao) VALUES (?, ?, ?)");
    $associaPerfil->bind_param("iss", $usuario_selecionado, $perfil_selecionado, $usuarioResponsavelPelaAlteracao);

    if ($associaPerfil->execute()) {
        $_statusCad = "Usuário associado com sucesso!";
        $mysqli->commit();
    }else {
        $_statusCad = "Erro ao associar usuário!";
        $mysqli->rollback();
    }


}else{
    $_statusCad = "Preencha os campos ou exclua e associe um novo perfil de usuário!";
}

$maria = "SELECT *
            FROM usuarios
            WHERE idUsuario NOT IN (SELECT usuarioID FROM usedperfilacesso)";
$result1 = $mysqli->query($maria);

$jose = "SELECT * FROM perfis WHERE status = 1";
$result2 = $mysqli->query($jose);

$cleito = "SELECT usedperfilacesso.idAssociaPerfil, usedperfilacesso.dataCadNivelPerfil, usedperfilacesso.responsavelAssociacao, usedperfilacesso.status, perfis.nomePerfil, usuarios.nomeUsuario, usuarios.login, usuarios.idUsuario
            FROM usedperfilacesso
            INNER JOIN perfis ON usedperfilacesso.nivelPerfilID = perfis.idNivelPerfil
            INNER JOIN usuarios ON usedperfilacesso.usuarioID = usuarios.idUsuario
            ORDER BY usedperfilacesso.dataCadNivelPerfil";
$result3 = $mysqli->query($cleito);



?>

<!DOCTYPE html>
<HTML lang="pt-BR">
    <HEAD>
        <TITLE>EBDS | Associar Perfis Acesso</TITLE>

        <?php 
        include("headContent.php"); 
        include("funcoes.php"); 
        ?>

        <script>
                //JS para inicializar das listas suspensas SELECT (uniformes, cargos e unidade)
                document.addEventListener('DOMContentLoaded', function() {
                var elems = document.querySelectorAll('select');
                var instances = M.FormSelect.init(elems);
                });
        </script>

    </HEAD>
    <body>
        <main class="box container">


            <div class="container center col s12">

                <h4>ASSOCIAR PERFIS DE ACESSO</h4>

                <br><br>

                <?php 
                    if($_statusCad = "Usuário associado com sucesso!"){
                        echo '<h5 style="color:green">' . $_statusCad . '</h5>';
                    }else {
                        echo '<h5 style="color:red">' . $_statusCad . '</h5>';
                    }
                ?>

                <br><br>


                    <!-- Form para associar usuário com determinado perfil de acesso -->

                <form method="post" action="">

                            <div class="usuarios col s6" >
                                <label for="usuario_selecionado" class="labSelect">Usuario:</label>
                                <select name="usuario_selecionado" id="usuario_selecionado" required>
                                <option value="" selected>Selecione o usuário</option>
                                    <?php
                                    // Verifique se há registros e gere as opções do select
                                    if ($result1->num_rows > 0) {
                                        while ($row = $result1->fetch_assoc()) {
                                            $idUsuario = $row['idUsuario'];
                                            $nomeUsuario = $row['nomeUsuario'];
                                            echo '<option value="' . $idUsuario . '">'. $nomeUsuario.'</option>';
                                        }
                                    } else {
                                        echo '<option value="" disabled selected>Nenhum usuário sem perfil atribuído</option>';
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="perfis col s6" >
                                <label for="perfil_selecionado" class="labSelect">Perfil de acesso:</label>
                                <select name="perfil_selecionado" id="perfil_selecionado">
                                <option value="" selected>Selecione o perfil</option>
                                    <?php
                                    // Verifique se há registros e gere as opções do select
                                    if ($result2->num_rows > 0) {
                                        while ($row = $result2->fetch_assoc()) {
                                            $idNivelPerfil = $row['idNivelPerfil'];
                                            $nomePerfil = $row['nomePerfil'];
                                            echo '<option value="' . $idNivelPerfil . '">'. $nomePerfil.'</option>';
                                        }
                                    } else {
                                        echo '<option value="" disabled selected>Nenhum perfil de acesso cadastrado</option>';
                                    }
                                    ?>
                                </select>
                            </div>

                            <br><br><br>

                            <button name="associa" value="associa" class="search">Salvar alterações</button> 
                </form>

                <br>

                <p><b>Administrador</b> visualizar e editar todos os dados e parâmetros (cargos e unidades).</p>
            <p><b>Comum</b> visualizar apenas os seus próríos dados.</p>

                            
                            
                <br><br><br>

                <h5>Perfis associados (exclua para associar novamente):</h5><br>

                <!-- Exibir associações já realizadas -->


                <div class="col s12">
                <table class="responsive-table">
                    <thead>
                        <tr>
                            <th>Nome de usuário</th>
                            <th>Tipo de perfil</th>
                            <th>Login</th>
                            <th>Responsável alteração</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                        <tbody>
                            <?php
                            // Verifique se há registros e gere as linhas da tabela
                            if ($result3->num_rows > 0) {
                                while ($row = $result3->fetch_assoc()) {
                                    $idAssocia = $row['idAssociaPerfil'];
                                    $nomeUsuario = $row['nomeUsuario'];
                                    $perfil = $row['nomePerfil'];
                                    $responsavelAssociacao = $row['responsavelAssociacao'];
                                    $dataCadNivelPerfil = $row['dataCadNivelPerfil'];
                                    $login = $row['login'];
                                    $status = $row['status'];
                                    $modalId = 'modal_' . $idAssocia;


                                    echo '<tr>';
                                    echo '<td>' . $nomeUsuario . '</td>';                                    
                                    echo '<td>' . $perfil . '</td>';
                                    echo '<td>' . $login . '</td>';
                                    echo '<td>' . $responsavelAssociacao . '</td>';
                                    echo '<td>'. traduz_status($status) .'</td>';
                                    echo '<td><a class="opt waves-effect waves-light modal-trigger col s4" href="#'.$modalId.'"><i class="btnopcao material-icons">delete</i></a></td>';
                                    echo '</tr>';



                                    //modal de confirmação antes de excluir
                                    echo '<div id="'.$modalId.'" class="modal">';
                                    echo '<div class="modal-content">';
                                    echo '<h4>Exclusão de perfil de acesso para <br>'.$nomeUsuario.'?</h4>';
                                    echo '<p>Você poderá ssociar um novo perfil de acesso depois!</p>';
                                    echo '</div>';
                                    echo '<div class="modal-footer">';
                                    echo '<a href="excluiPerfilAcesso.php?idAssocia='.$idAssocia.'" class="modal-close waves-effect waves-green btn-flat">EXCLUIR</a>';
                                    echo '<a href="#" class="modal-close waves-effect waves-green btn-flat">CANCELAR</a>';
                                    echo '</div>';
                                    echo '</div>';
                                }
                            } else {
                                echo '<tr><td colspan="2">Nenhum perfil associado</td></tr>';
                            }
                            ?>
                        </tbody>
                </table>
            </div>


                    <script>//js para iniciar o MODAL
                        document.addEventListener('DOMContentLoaded', function() {
                        var elems = document.querySelectorAll('.modal');
                        var instances = M.Modal.init(elems);
                        });
                    </script>


            <br><br>




            
            </div>

        </main>

        <?php include("footerContent.php");?> <!--adiciona o conteúdo do rodapé de modo modular usando o INCLUDE em PHP-->

    </body>
</HTML>