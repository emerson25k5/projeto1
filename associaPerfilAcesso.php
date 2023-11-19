<?php

include("autenticaContent.php");
require "configuracoes.php";

if($_SESSION['nivelAcesso'] != 2) {
    echo "Acesso negado!";
    exit;
}

date_default_timezone_set('America/Sao_Paulo');
$dataHoraAtual = new DateTime();

$_statusCad = "";
$usuario_selecionado = "";
$perfil_selecionado = "";
$usuarioResponsavelPelaAlteracao = $_SESSION['nomeCompleto'];

include("conecta.php");

if(array_key_exists('usuario_selecionado', $_POST) && array_key_exists('perfil_selecionado', $_POST)){

    $mysqli->begin_transaction();

    $usuario_selecionado = $_POST['usuario_selecionado'];
    $perfil_selecionado = $_POST['perfil_selecionado'];
    $dataHoraAtual = $dataHoraAtual->format('Y-m-d H:i:s');

    $associaPerfil = $mysqli->prepare("INSERT INTO usedperfilacesso (usuarioID, nivelPerfilID, responsavelAssociacao, dataCadNivelPerfil) VALUES (?, ?, ?, ?)");
    $associaPerfil->bind_param("isss", $usuario_selecionado, $perfil_selecionado, $usuarioResponsavelPelaAlteracao, $dataHoraAtual);

    if ($associaPerfil->execute()) {
        $mysqli->commit();
        echo "<script>alert('Usuário associado com sucesso!');</script>";
        echo "<script>setTimeout(function(){ window.location.href = 'associaPerfilAcesso.php'; }, 5);</script>";
    }else {
        $mysqli->rollback();
        echo "<script>alert('Erro ao associar perfil de usuário');</script>";
    }


}else{
    $_statusCad = "Preencha os campos ou exclua e associe um novo perfil de usuário!";
}

$maria = "SELECT usuarios.*, funcionarios.nome
        FROM funcionarios
        LEFT JOIN usuarios ON funcionarios.idFuncionario = usuarios.funcionarioID
        WHERE idUsuario NOT IN (SELECT usuarioID FROM usedperfilacesso) AND funcionarios.status = 1";
$result1 = $mysqli->query($maria);

$jose = "SELECT * FROM perfis WHERE status = 1";
$result2 = $mysqli->query($jose);

if(isset($_GET['procura'])){

    $procura = $_GET['procura'];

    $cleito = "SELECT usedperfilacesso.*, perfis.nomePerfil, funcionarios.nome, usuarios.login, usuarios.idUsuario
    FROM funcionarios
    LEFT JOIN usuarios ON funcionarios.idFuncionario = usuarios.funcionarioID
    LEFT JOIN usedperfilacesso ON usuarios.idUsuario = usedperfilacesso.usuarioID
    LEFT JOIN perfis ON usedperfilacesso.nivelPerfilID = perfis.idNivelPerfil
    WHERE usedperfilacesso.usuarioID IS NOT NULL AND funcionarios.nome LIKE '%$procura%'
    ORDER BY usedperfilacesso.nivelPerfilID DESC";
    $result3 = $mysqli->query($cleito);

}else{

    $cleito = "SELECT usedperfilacesso.*, perfis.nomePerfil, funcionarios.nome, usuarios.login, usuarios.idUsuario
    FROM funcionarios
    LEFT JOIN usuarios ON funcionarios.idFuncionario = usuarios.funcionarioID
    LEFT JOIN usedperfilacesso ON usuarios.idUsuario = usedperfilacesso.usuarioID
    LEFT JOIN perfis ON usedperfilacesso.nivelPerfilID = perfis.idNivelPerfil
    WHERE usedperfilacesso.usuarioID IS NOT NULL
    ORDER BY usedperfilacesso.nivelPerfilID DESC";
    $result3 = $mysqli->query($cleito);

}



$mysqli->close();

?>

<!DOCTYPE html>
<HTML lang="pt-BR">
    <HEAD>
        <TITLE><?php echo NOME_EMPRESA; ?> | Associar Perfis Acesso</TITLE>

        <?php 
        include("headContent.php"); 
        include("funcoes.php"); 
        ?>

        <style>

            fieldset {
                border-radius: 10px;
            }

        </style>

    </HEAD>
    <body>
        <main class="box container">


            <div class="container col s12">

                <h4>ASSOCIAR PERFIS DE ACESSO</h4>

                <br><br>

                <fieldset>

                <br>

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
                                            $nomeUsuario = $row['nome'];
                                            echo '<option value="' . $idUsuario . '">'. $nomeUsuario.'</option>';
                                        }
                                    } else {
                                        echo '<option value="" disabled selected>Nenhum usuário sem perfil</option>';
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="perfis col s6" >
                                <label for="perfil_selecionado" class="labSelect">Perfil de acesso:</label>
                                <select name="perfil_selecionado" id="perfil_selecionado">
                                <option value="" selected>Selecione o nível de acesso</option>
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

                            <br>

                            <div class="center">
                            <button name="associa" value="associa" class="search">Salvar alterações</button> 
                            </div>
                </form>

                <br>

                <p><b>Administrador</b> visualizar e editar todos os dados e parâmetros (cargos e unidades).</p>
                <p><b>Comum</b> visualizar apenas os seus próprios dados.</p>
                </fieldset>


                            
                            
                <br><br>

                <h5>Perfis associados</h5><br>

                <?php require "campoBuscaFuncionarioContent.php"; ?>

                <fieldset>

                <!-- Exibir associações já realizadas -->


                <div class="col s12">

                <table class="">
                    <thead>
                        <tr>
                            <th>Nome de usuário</th>
                            <th>Tipo de perfil</th>
                        </tr>
                    </thead>
                        <tbody>
                            <?php
                            // Verifique se há registros e gere as linhas da tabela
                            if ($result3->num_rows > 0) {
                                while ($row = $result3->fetch_assoc()) {
                                    $idAssocia = $row['idAssociaPerfil'];
                                    $nomeUsuario = $row['nome'];
                                    $perfil = $row['nomePerfil'];
                                    $responsavelAssociacao = $row['responsavelAssociacao'];
                                    $dataCadNivelPerfil = $row['dataCadNivelPerfil'];
                                    $login = $row['login'];
                                    $status = $row['status'];
                                    $modalId = 'modal' . $idAssocia;
                                    $modalIdEx = 'modal_' . $idAssocia;


                                    echo '<tr>';
                                    echo '<td>' . $nomeUsuario . '</td>';                                    
                                    echo '<td>' . $perfil . '</td>';
                                    echo '<td><button class="search waves-effect waves-light modal-trigger col s4" href="#'.$modalId.'"><i class="material-icons">search</i></button></td>';
                                    echo '</tr>';

                                    #modal para exibir mais informações
                                    echo '<div id="'. $modalId . '" class="modal" style="border-radius: 10px">';
                                    echo '<div class="modal-content">';
                                    echo '<h6>' . $nomeUsuario . '</h6><br>';
                                    echo '<div class="divider"></div><br>';
                                    echo '<label for="tipoAcesso">Tipo de acesso</label>';
                                    echo '<p>' . $perfil . '</p>';
                                    echo '<label for="tipoAcesso">E-mail (login)</label>';
                                    echo '<p>' . $login . '</p>';
                                    echo '<div class="divider"></div><br>';
                                    echo '<label for="tipoAcesso">Responsável por associar o perfil</label>';
                                    echo '<p>' . $responsavelAssociacao . '</p>';
                                    echo '<label for="tipoAcesso">Data e hora associação</label>';
                                    echo '<p>' . date('d/m/Y H:i:s', strtotime($dataCadNivelPerfil)) . '</p>';
                                    echo '</div>';
                                    echo '<div class="modal-footer">';
                                    echo '<a class="waves-effect waves-light modal-trigger left" href="#'.$modalIdEx.'"><i class="btnopcao material-icons">delete</i></a>';
                                    echo '<button href="#!" class="search modal-close">Fechar</button>';
                                    echo '</div>';
                                    echo '</div>';


                                    //modal de confirmação antes de excluir
                                    echo '<div id="'.$modalIdEx.'" class="modal" style="border-radius: 10px">';
                                    echo '<div class="modal-content">';
                                    echo '<h6>Excluir perfil <b>'.$perfil.'</b> para <b>'.$nomeUsuario.'</b>?</h6>';
                                    echo '<p>Você terá que ssociar um novo perfil de acesso!</p>';
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

                </fieldset>
            </div>


                    <script>

                        //js para iniciar o MODAL
                        document.addEventListener('DOMContentLoaded', function() {
                        var elems = document.querySelectorAll('.modal');
                        var instances = M.Modal.init(elems);
                        });

                        //JS para inicializar das listas suspensas SELECT (uniformes, cargos e unidade)
                        document.addEventListener('DOMContentLoaded', function() {
                        var elems = document.querySelectorAll('select');
                        var instances = M.FormSelect.init(elems);
                        });

                    </script>


            <br><br>

            
            </div>

        </main>

        <?php include("footerContent.php");?> <!--adiciona o conteúdo do rodapé de modo modular usando o INCLUDE em PHP-->

    </body>
</HTML>