<?php 
include("configuracoes.php");
include("autenticaContent.php");
include("conecta.php");

//verifica se o nivel de acesso é de adm, se n for é exibida mensagem de erro e o resto da página não carrega
if($_SESSION['nivelAcesso'] != 2) {
    echo "Acesso negado!";
    exit;
}

$func = "SELECT * FROM funcionarios WHERE status = 1 ORDER BY nome ASC";
$result1 = $mysqli->query($func);

if(isset($_GET['procura'])){

    $procura = $_GET['procura'];

    $folga = "SELECT controlefolgatrabalhada.*, funcionarios.nome
    FROM controlefolgatrabalhada
    LEFT JOIN funcionarios ON controlefolgatrabalhada.funcionarioID = funcionarios.idFuncionario
    WHERE controlefolgatrabalhada.status = 1 AND controlefolgatrabalhada.nomeFuncionario LIKE '%$procura%' OR funcionarios.nome LIKE '%$procura%'
    ORDER BY controlefolgatrabalhada.dataCadastro DESC";
    $resultFolga = $mysqli->query($folga);

}else{

    $folga = "SELECT controlefolgatrabalhada.*, funcionarios.nome
    FROM controlefolgatrabalhada
    LEFT JOIN funcionarios ON controlefolgatrabalhada.funcionarioID = funcionarios.idFuncionario
    WHERE controlefolgatrabalhada.status = 1 ORDER BY controlefolgatrabalhada.dataCadastro DESC";
    $resultFolga = $mysqli->query($folga);

}



if($_POST && $_POST['form_id'] == 1){

    $mysqli->begin_transaction();

date_default_timezone_set('America/Sao_Paulo');
$dataHoraAtual = new DateTime();

if($_POST['daCasa'] == 1){
    $daCasa = 1;
    $funcionario = 0;
    $funcionarioID = $_POST['funcionario_selecionadoID'];
}else{
    $daCasa = 0;
    $funcionario = $_POST['funcionario_selecionado'];;
    $funcionarioID = 0;
}

$dataTrabalhada = $_POST['dataTrabalhada'];
$folgaObs = $_POST['folgaObservacoes'];
$dataCadastro = $dataHoraAtual->format('Y-m-d H:i:s'); //variável com a hora atual
$responsavelCadastro = $_SESSION['nomeCompleto'];

$inserir = $mysqli->prepare("INSERT INTO controlefolgatrabalhada (nomeFuncionario, funcionarioID, daCasa, dataFolgaTrabalhada, folgaObservacao, dataCadastro, responsavelCadastro) VALUES (?, ?, ?, ?, ?, ?, ?)");
$inserir->bind_param("siissss", $funcionario, $funcionarioID, $daCasa, $dataTrabalhada, $folgaObs, $dataCadastro, $responsavelCadastro);



if($inserir->execute()) {
    $mysqli->commit();

    $mysqli->begin_transaction();

    date_default_timezone_set('America/Sao_Paulo');
    $dataHoraAtual = new DateTime();

    $idLogUsuarioResponsavel = $_SESSION['idUsuarioLogado'];
    $nomeUsuarioResponsavel = $_SESSION['nomeCompleto'];
    $dataLogAlteracao = $dataHoraAtual->format('Y-m-d H:i:s'); //variável com a hora atual
    $tipoLog = "Cadastro folga trabalhada";
        $nomeFuncionario = $funcionario;
        $idFuncionario = $funcionarioID;

    $inserirLog = $mysqli->prepare("INSERT INTO logalteracoes (idLogUsuarioResponsavel, tipoLog, dataLogAlteracao, nomeUsuarioResponsavel, nomeAlt, idFuncionarioAlt) VALUES (?, ?, ?, ?, ?, ?)");
    $inserirLog->bind_param("issssi", $idLogUsuarioResponsavel, $tipoLog, $dataLogAlteracao, $nomeUsuarioResponsavel, $nomeFuncionario, $idFuncionario);

    if ($inserirLog->execute()) {
        $mysqli->commit();
        echo "<script>setTimeout(function(){ window.location.href = 'controleFolgaTrabalhada.php'; }, 100);</script>";
        $mysqli->close();
    }else{
        echo "Falha no registro de LOG" . " Erro: " . $mysqli->error;
        $mysqli->rollback();
        $mysqli->close();
    }

    $mysqli->close();
}else{
    $mysqli->rollback();
    $mysqli->close();
}

}

if($_POST && $_POST['checkPag'] == 1){

    $mysqli->begin_transaction();

    if (isset($_POST['sttCheckPag'])) {
        $sstCheckPag = 1;
        // Faça algo aqui
    } else {
        $sstCheckPag = 0;
    }

$idPagFolga = $_POST['idPagFolga'];

$attPag = "UPDATE controlefolgatrabalhada SET pagamentoRealizado=? WHERE idFolgaTrabalhada = $idPagFolga";
$stmt = $mysqli->prepare($attPag);

if ($stmt === false) {
    die('Erro na preparação da consulta: ' . $mysqli->error);
}

$stmt->bind_param("i", $sstCheckPag);

if ($stmt->execute()) {
    $mysqli->commit();

    $mysqli->begin_transaction();

    date_default_timezone_set('America/Sao_Paulo');
    $dataHoraAtual = new DateTime();

    $idLogUsuarioResponsavel = $_SESSION['idUsuarioLogado'];
    $nomeUsuarioResponsavel = $_SESSION['nomeCompleto'];
    $dataLogAlteracao = $dataHoraAtual->format('Y-m-d H:i:s'); //variável com a hora atual
    $tipoLog = "Pagamento folga trabalhada";
    $idPagFolga;

    $dados = "SELECT nomeFuncionario, funcionarioID FROM controlefolgatrabalhada WHERE idFolgaTrabalhada = $idPagFolga";
    $res = $mysqli->query($dados);
    if ($res) {
        $row = $res->fetch_assoc();
        $nomeFuncionario = $row['nomeFuncionario'];
        $idFuncionario = $row['funcionarioID'];
    }

    $inserirLog = $mysqli->prepare("INSERT INTO logalteracoes (idLogUsuarioResponsavel, tipoLog, dataLogAlteracao, nomeUsuarioResponsavel, nomeAlt, idFuncionarioAlt) VALUES (?, ?, ?, ?, ?, ?)");
    $inserirLog->bind_param("issssi", $idLogUsuarioResponsavel, $tipoLog, $dataLogAlteracao, $nomeUsuarioResponsavel, $nomeFuncionario, $idFuncionario);

    if ($inserirLog->execute()) {
        $mysqli->commit();
        echo "<script>setTimeout(function(){ window.location.href = 'controleFolgaTrabalhada.php'; }, 100);</script>";
        $mysqli->close();
    }else{
        echo "Falha no registro de LOG" . " Erro: " . $mysqli->error;
        $mysqli->rollback();
        $mysqli->close();
    }

} else {
    $mysqli->rollback();
    echo '<script>alert("Erro ao atualizar dados:");</script>' . $stmt->error;
}

}



?>


<!DOCTYPE html>
<HTML lang="pt-BR">
    <HEAD>
        <TITLE><?php echo NOME_EMPRESA; ?> | CONTROLE FOLGA TRABALHADA</TITLE>
    <?php 
    require "headContent.php";
    require "mascaraContent.php";
    ?>

    <script>//inicializador do modal
        
        document.addEventListener('DOMContentLoaded', function() {
        var elems = document.querySelectorAll('.modal');
        var instances = M.Modal.init(elems);
    
      });
        
    </script>
    
    <style>
        fieldset {
            border-radius: 10px;
            align-items: center;
        }

        textarea {
            border-radius: 10px;
            height: 100px !important;
        }
    </style>

    </HEAD>

    <body>

    <h4 class="center">Folgas trabalhadas</h4>

    <br><br>

    <div class="row col s12 container">

                    <fieldset>

                    <h5 class="center">Inserir folga trabalhada</h5>

                    <br>

                        <form method="post" action="">

                            <input type="hidden" name="form_id" value="1">

                            <p>
                                <label>
                                    <input type="checkbox" name="daCasa" value="1" class="filled-in" id="checkboxFuncionario"/>
                                    <span>Já cadastrado?</span>
                                </label>
                            </p>
                          
                            <label for="funcionario_selecionadoID" class="labSelect">Funcionário:</label>
                            <div class="funsuarios col s12" id="divSelect" style="display: none;">
                                <select name="funcionario_selecionadoID" id="funcionario_selecionadoID">
                                <option value="" selected>Selecione o funcionário</option>
                                    <?php
                                    // Verifique se há registros e gere as opções do select
                                    if ($result1->num_rows > 0) {
                                        while ($row = $result1->fetch_assoc()) {
                                            $idFuncionario = $row['idFuncionario'];
                                            $nomeFunc = $row['nome'];
                                            echo '<option value="' . $idFuncionario . '">'. $nomeFunc .'</option>';
                                        }
                                    } else {
                                        echo '<option value="" disabled selected>Nenhum funcionário encontrado</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            
                            <div id="divInput">
                            <input type="text" name="funcionario_selecionado" id="funcionario_selecionado" oninput="converterParaCaixaAlta(this)" placeholder="Digite o nome" required>
                            </div>

                            <label for="dataTrabalhada" class="col s12">Data:</label><br>
                            <input type="tel" name="dataTrabalhada" id="dataTrabalhada" oninput="formatarData(this)" placeholder="DD/MM/AAAA" required>

                            <textarea name="folgaObservacoes" data-length="500" style="border-radius:6px; height: 100px" placeholder="Obervação"></textarea>
                            <br><br>

                            <div class="center">
                               <button type="submit" class="search">Adicionar folga trabalhada</button>
                            </div>

                        </form><br><br><br>


                    </fieldset>

                            <div class="">

                            <br>

                                    <h5>Histórico de folgas trabalhadas</h5>

                                    <br>

                                    <?php require "campoBuscaFuncionarioContent.php"; ?>

                            <fieldset style="border-radius:10px">
                            <table>
                                    <thead>
                                        <tr>
                                            <th><i class="material-icons">person</i></th>                                            
                                            <th><i class="material-icons">event</i></th>
                                            <th><i class="material-icons">monetization_on</i></th>
                                        </tr>
                                    </thead>
                                        <tbody>
                                        <?php
                                        if ($resultFolga->num_rows > 0) {
                                            while ($row = $resultFolga->fetch_assoc()) {
                                                $idFolga = $row['idFolgaTrabalhada'];                                           
                                                $dataFolga = $row['dataFolgaTrabalhada'];
                                                $pagamento = $row['pagamentoRealizado'];
                                                $daCasa = $row['daCasa'];
                                                $funcionario = $row['nomeFuncionario'];
                                                ($daCasa == 0) ? $funcionario = $row['nomeFuncionario'] : $funcionario = $row['nome'];

                                                if($pagamento == 0){
                                                    $pagamento = '';
                                                    $sttPag = "";
                                                    $spanPag = "<p style='color:orange;'><b>Pendente</b></p>";
                                                }else{
                                                    $pagamento = 'checked="checked"';
                                                    $sttPag = "disabled";
                                                    $spanPag = "<p style='color:green;'><b>Pago</b></p>";
                                                }

                                                $checkPag = "checkPag".$idFolga;
                                                $formCheck = "formCheck".$idFolga;                                            
                                                    

                                            echo '<tr>';
                                            echo '<td><p>' . $funcionario . '</p></td>';
                                            echo '<td><p>' . $dataFolga . '</p></td>';
                                            echo '<form method="post" action="" id="'.$formCheck.'">';
                                            echo '<input type="hidden" name="checkPag" value="1">';
                                            echo '<input type="hidden" name="idPagFolga" value="'.$idFolga.'">';
                                            echo '<td><p><label><input type="checkbox" name="sttCheckPag" id="'.$checkPag.'" class="filled-in" '.$pagamento.' '.$sttPag.'/><span>'.$spanPag.'</span></p></label></td>';
                                            echo '</form>';
                                            echo '</tr>';

                                            echo '<script>
                                                //envia o pagamento quitado para o bd assim que é clicado
                                                    var form' . $idFolga . ' = document.getElementById("'.$formCheck.'");
                                                    var checkbox' . $idFolga . ' = document.getElementById("'.$checkPag.'");

                                                    checkbox' . $idFolga . '.addEventListener("change", function() {
                                                        // Simula o envio do formulário quando o estado do checkbox muda
                                                        form' . $idFolga . '.submit();
                                                    });
                                            </script>';
                                            }

                                        }else{
                                            echo '<tr><td colspan="2">Nenhum registro de trabalho encontrato</td></tr>';
                                        }
                                        ?>
                                        </tbody>
                                </table>
                                </fieldset>
                            </div>


    </div>

                    <script>                                    

                        // Adiciona um ouvinte de evento ao checkbox
                        document.getElementById('checkboxFuncionario').addEventListener('change', function() {
                            // Obtém a referência aos elementos
                            var divSelect = document.getElementById('divSelect');
                            var divInput = document.getElementById('divInput');

                            // Alterna a visibilidade dos elementos com base no estado do checkbox
                            divSelect.style.display = this.checked ? 'block' : 'none';
                            divInput.style.display = this.checked ? 'none' : 'block';

                            if (this.checked) {
                            // Torna o campo 'funcionario_selecionadoID' obrigatório e o habilita
                            document.getElementById('funcionario_selecionadoID').setAttribute('required', 'required');
                            document.getElementById('funcionario_selecionadoID').disabled = false;

                            // Remove o atributo 'required' do campo 'funcionario_selecionado' se estiver presente
                            document.getElementById('funcionario_selecionado').removeAttribute('required');
                            document.getElementById('funcionario_selecionado').disabled = true;
                        } else {
                            // Torna o campo 'funcionario_selecionado' obrigatório e o habilita
                            document.getElementById('funcionario_selecionado').setAttribute('required', 'required');
                            document.getElementById('funcionario_selecionado').disabled = false;

                            // Remove o atributo 'required' do campo 'funcionario_selecionadoID' se estiver presente
                            document.getElementById('funcionario_selecionadoID').removeAttribute('required');
                            document.getElementById('funcionario_selecionadoID').disabled = true;
                        }
                            
                        });

                        

                        //JS para inicializar das listas suspensas SELECT (uniformes, cargos e unidade)
                        document.addEventListener('DOMContentLoaded', function() {
                        var elems = document.querySelectorAll('select');
                        var instances = M.FormSelect.init(elems);
                        });

                    </script>


    </body>

    <?php require "footerContent.php"; ?>

</HTML>