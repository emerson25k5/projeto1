<?php

include("autenticaContent.php");

if ($_SESSION['nivelAcesso'] != 3) {
    echo "acesso negado!";
    header("Location: cadastroFuncionario.php");
    exit;
}

$_statusCad = "";
$usuario_selecionado = "";
$perfil_selecionado = "";

include("conecta.php");

if($_POST){

    $mysqli->begin_transaction();

    $usuario_selecionado = $_POST['usuario_selecionado'];
    $perfil_selecionado = $_POST['perfil_selecionado'];

    $associaPerfil = $mysqli->prepare("INSERT INTO usedperfilacesso (usuarioID, nivelPerfilID) VALUES (?, ?)");
    $associaPerfil->bind_param("is", $usuario_selecionado, $perfil_selecionado);

    if ($associaPerfil->execute()) {
        $_statusCad = "Usuário associado com sucesso!";
        $mysqli->commit();
    }else {
        $_statusCad = "Erro ao associar usuário!";
        $mysqli->rollback();
    }


}

$maria = "SELECT * FROM usuarios";
$result1 = $mysqli->query($maria);

$jose = "SELECT * FROM perfis";
$result2 = $mysqli->query($jose);

$cleito = "SELECT usedperfilacesso.dataCadNivelPerfil, usedperfilacesso.status, perfis.nomePerfil, usuarios.nomeUsuario, usuarios.login
            FROM usedperfilacesso
            INNER JOIN perfis ON usedperfilacesso.nivelPerfilID = perfis.idNivelPerfil
            INNER JOIN usuarios ON usedperfilacesso.usuarioID = usuarios.idUsuario";
$result3 = $mysqli->query($cleito);



?>

<!DOCTYPE html>
<HTML lang="pt-BR">
    <HEAD>
        <TITLE>EBDS | Cadastro de Perfis</TITLE>

        <?php 
        include("headContent.php"); 
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



                <?php 
                    if($_statusCad = "Usuário associado com sucesso!"){
                        echo '<h5 style="color:green">' . $_statusCad . '</h5>';
                    }else {
                        echo '<h5 style="color:red">' . $_statusCad . '</h5>';
                    }
                
                ?>


                    <!-- Form para associar usuário com determinado perfil de acesso -->

                <form method="post" action="">

                            <div class="usuarios col s6" >
                                <label for="usuario_selecionado" class="labSelect">Usuario:</label>
                                <select name="usuario_selecionado" id="usuario_selecionado">
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
                                        echo '<option value="" disabled selected>Nenhum usuário encontrado</option>';
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
                                        echo '<option value="" disabled selected>Nenhum usuário encontrado</option>';
                                    }
                                    ?>
                                </select>
                            </div>

                            <button name="associa" value="associa" class="search">Salvar alterações</button> 
                </form>

                            
                            
                <br><br><br>

                <h5>Perfis associados:</h5><br>

                <!-- Exibir associações já realizadas -->


                <div class="responsive-table col s12">
                <table class="responsive-table">
                    <thead>
                        <tr>
                            <th>Nome de usuário</th>
                            <th>Tipo de perfil</th>
                            <th>Login</th>
                            <th>Data cadastro</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                        <tbody>
                            <?php
                            // Verifique se há registros e gere as linhas da tabela
                            if ($result3->num_rows > 0) {
                                while ($row = $result3->fetch_assoc()) {
                                    $nomeUsuario = $row['nomeUsuario'];
                                    $perfil = $row['nomePerfil'];
                                    $dataCadNivelPerfil = $row['dataCadNivelPerfil'];
                                    $login = $row['login'];
                                    $statusPerfil = $row['status'];
                                    echo '<tr>';
                                    echo '<td>' . $nomeUsuario . '</td>';                                    
                                    echo '<td>' . $perfil . '</td>';
                                    echo '<td>' . $login . '</td>';
                                    echo '<td>' . $dataCadNivelPerfil . '</td>';
                                    if($statusPerfil == 1){
                                        echo '<td>Ativo</td>';
                                    }else {
                                        echo '<td>Inativo</td>';
                                    };
                                    echo '</tr>';
                                }
                            } else {
                                echo '<tr><td colspan="2">Nenhum perfil associado</td></tr>';
                            }
                            ?>
                        </tbody>
                </table>
            </div>


            <br><br><br><br>


            <p>Perfil 1 = Administrador (visualizar e editar todos os dados e parâmetros (cargos e unidades)).</p>
            <p>Perfil 2 = Supervisor (Visualizar todos os dados e editar dados dos funcionários).</p>
            <p>Perfil 3 = Funcionário (Visualizar e atualizar parte dos seus dados).</p>

            
            </div>

        </main>

        <?php include("footerContent.php");?> <!--adiciona o conteúdo do rodapé de modo modular usando o INCLUDE em PHP-->

    </body>
</HTML>