<?php

include("autenticaContent.php");
include("conecta.php");

if($_SESSION['nivelAcesso'] != 2) {
    echo "Acesso negado!";
    exit;
}

$sql = "SELECT idCargo, nomeCargo FROM cargos WHERE status = 1 ORDER BY nomeCargo";
$result = $mysqli->query($sql);

$sql2 = "SELECT idUnidade, nomeUnidade FROM unidades WHERE status = 1 ORDER BY nomeUnidade";
$result2 = $mysqli->query($sql2);

date_default_timezone_set('America/Sao_Paulo');
$dataHoraAtual = new DateTime();

$_statusCad = "";

if ($_POST) {

    $mysqli->begin_transaction();

//dados gerais
$nome = $_POST["nome"];
$email = $_POST["email"];
$cpf = $_POST["cpf"];
$rg = $_POST['rg'];
$telefone = $_POST['telefone'];
$nascimento = $_POST['nascimento'];
$genero = $_POST['genero'];
$responsavelCadastro = $_SESSION['nomeCompleto']; //para enviar para a tabela de cadastro de funcionarios o cara que está logado e que está fazendo a inserção do usuário
$usuarioResponsavelPeloCadastro = $_SESSION['nomeUsuario']; //para enviar a tabela de associa perfil de acesso
$dataCadFuncionario = $dataHoraAtual->format('Y-m-d H:i:s'); //variável com a hora atual

//usuario (login e senha)
$login = $_POST["email"];
$senha = $_POST["cpf"];
$senhaLimpa = str_replace(['.', '-'], '', $senha);
$hashSenha = password_hash($senhaLimpa, PASSWORD_DEFAULT);

//endereço
$cep = $_POST['cep'];
$nomeRua = $_POST['nomeRua'];
$numero = $_POST['numero'];
$cidade = $_POST['cidade'];
$municipio = $_POST['municipio'];
$bairro = $_POST['bairro'];
$complemento = $_POST['complemento'];

//atribuições
$cargo = $_POST["cargo_escolhido"];
$unidade = $_POST["unidade_escolhida"];
$admissao = $_POST['dataAdmissao'];

//uniforme
$tam_tronco_selecionado = $_POST["tam_tronco"];
$tam_perna_selecionado = $_POST["tam_perna"];
$tam_calcado_selecionado = $_POST["tam_calcado"];

    // Inserir o usuário
    $cadastrarFuncionario = $mysqli->prepare("INSERT INTO funcionarios (nome, email, cpf, rg, telefone, nascimento, genero, responsavelCadastro, dataCadFuncionario) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $cadastrarFuncionario->bind_param("sssssssss", $nome, $email, $cpf, $rg, $telefone, $nascimento, $genero, $responsavelCadastro, $dataCadFuncionario);

    if ($cadastrarFuncionario->execute()) {
        $ultimo_id = $mysqli->insert_id;
        $ultimo_id_inserido = $ultimo_id;

        //inserir usuario
        $cadastrarUsuario = $mysqli->prepare("INSERT INTO usuarios (login, senha, funcionarioID) VALUES (?, ?, ?)");
        $cadastrarUsuario->bind_param("sss", $login, $hashSenha, $ultimo_id_inserido);

        if($cadastrarUsuario->execute()) {
            $ultimo_id_usuario = $mysqli->insert_id;//para enviar para a usedperfilacesso

        $cadastrarPerfilAcesso = $mysqli->prepare("INSERT INTO usedperfilacesso (usuarioID, responsavelAssociacao) VALUES (?, ?)");
        $cadastrarPerfilAcesso->bind_param("is", $ultimo_id_usuario, $usuarioResponsavelPeloCadastro);
        }else{
            $_statusCad = "Falha no cadastro do perfil de acesso" . " Erro: " . $mysqli->error;
            $mysqli->rollback();
            $mysqli->close();
        }
        
        if($cadastrarPerfilAcesso->execute()){
            $ultimo_id_inserido;

        // Inserir o endereço
        $cadastrarEndereco = $mysqli->prepare("INSERT INTO usedenderecos (cep, rua, numero, cidade, municipio, bairro, complemento, funcionarioID) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $cadastrarEndereco->bind_param("ssssssss", $cep, $nomeRua, $numero, $cidade, $municipio, $bairro, $complemento, $ultimo_id_inserido);
        }else{
            $_statusCad = "Falha no cadastro do endereço" . " Erro: " . $mysqli->error;
            $mysqli->rollback();
            $mysqli->close();
        }
        //inserir cargo
        if($cadastrarEndereco->execute()) {
            $ultimo_id_inserido;

            $cadastrarCargo = $mysqli->prepare("INSERT INTO usedcargos (cargoID, funcionarioID) VALUES (?, ?)");
            $cadastrarCargo->bind_param("si", $cargo, $ultimo_id_inserido);
        }else{
            $_statusCad = "Falha no cadastro do endereço" . " Erro: " . $mysqli->error;
            $mysqli->rollback();
            $mysqli->close();
        }
        //inserir unidade
        if($cadastrarCargo->execute()) {
            $ultimo_id_inserido;

            $cadastrarUnidade = $mysqli->prepare("INSERT INTO usedunidades (unidadeID, funcionarioID) VALUES (?, ?)");
            $cadastrarUnidade->bind_param("si", $unidade, $ultimo_id_inserido);
        }else{
            $_statusCad = "Falha no cadastro do cargo" . " Erro: " . $mysqli->error;
            $mysqli->rollback();
            $mysqli->close();
        }
        //inserir admissão
        if($cadastrarUnidade->execute()) {
            $ultimo_id_inserido;

            $cadastrarAdmissao = $mysqli->prepare("INSERT INTO admissao (dataAdmissao, funcionarioID) VALUES (?, ?)");
            $cadastrarAdmissao->bind_param("ss", $admissao, $ultimo_id_inserido);
        }else{
            $_statusCad = "Falha no cadastro da data da unidade" . " Erro: " . $mysqli->error;
            $mysqli->rollback();
            $mysqli->close();
        }
        //inserir uniformes
        if($cadastrarAdmissao->execute()) {
            $ultimo_id_inserido;

            $cadastrarUniforme = $mysqli->prepare("INSERT INTO useduniformes (tamTronco, tamPerna, tamCalcado, funcionarioID) VALUES (?, ?, ?, ?)");
            $cadastrarUniforme->bind_param("ssii", $tam_tronco_selecionado, $tam_perna_selecionado, $tam_calcado_selecionado, $ultimo_id_inserido);
        }else{
            $_statusCad = "Falha no cadastro da data de admissao" . " Erro: " . $mysqli->error;
            $mysqli->rollback();
            $mysqli->close();
        }

        if ($cadastrarUniforme->execute()) {
            $mysqli->commit();
            $_statusCad = "Cadastro realizado com sucesso!";
        } else {
            $_statusCad = "Falha no cadastro do uniforme!" . " Erro: " . $mysqli->error;
            $mysqli->rollback();
            $mysqli->close();
        }
}else {
    $_statusCad = "Falha no cadastro do funcionário!" . " Erro: " . $mysqli->error;
    $mysqli->rollback();
    $mysqli->close();
}

        //INSERIR NO LOG A INSERÇÃO DE UM NOVO FUNCIONARIO
        include("conecta.php");

        if($cadastrarUniforme == TRUE){

            $mysqli->begin_transaction();

            $idLogUsuarioResponsavel = $_SESSION['idUsuarioLogado'];
            $nomeUsuarioResponsavel = $_SESSION['nomeCompleto'];
            $tipoLog = "Cadastro novo funcionario";
            $dataLogAlteracao = $dataHoraAtual->format('Y-m-d H:i:s');
            $nome = $_POST["nome"];
            $email = $_POST["email"];
            $cpf = $_POST["cpf"];
            $rg = $_POST['rg'];
            $telefone = $_POST['telefone'];
            $nascimento = $_POST['nascimento'];
            $genero = $_POST['genero'];
            $dataCadFuncionario = $dataHoraAtual->format('Y-m-d H:i:s');
            $ultimo_id_inserido;

            $insertLog = $mysqli->prepare("INSERT INTO logalteracoes (idLogUsuarioResponsavel, tipoLog, dataLogAlteracao, nomeUsuarioResponsavel, cpfAlt, dataCadFuncionarioAlt, emailAlt, generoAlt,
            idFuncionarioAlt, nascimentoAlt, nomeAlt, rgAlt, telefoneAlt) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $insertLog->bind_param("isssssssissss", $idLogUsuarioResponsavel, $tipoLog, $dataLogAlteracao, $nomeUsuarioResponsavel, $cpf, $dataCadFuncionario, $email, $genero, $ultimo_id_inserido,
            $nascimento, $nome, $rg, $telefone);

            if ($insertLog->execute()) {
                $mysqli->commit();
                $mysqli->close();
            }else{
                echo "Falha no registro de LOG" . " Erro: " . $mysqli->error;
                $mysqli->rollback();
                $mysqli->close();
            }
        }else{
            echo "Erro ao salvar no registro de alterações!".$mysqli->error;
            exit();
        }
}

?>

<!DOCTYPE html>
<HTML lang="pt-BR">
    <HEAD>
        <TITLE>PATROL | Cadastro </TITLE>

        <?php include("headContent.php"); ?>

    </HEAD>
    <body>
        <main class="container">

            <div class="center">

                <h4>CADASTRO DE FUNCIONÁRIOS</h4>

                    <?php include("mascaraContent.php");?>

                <div class="statusCad center" id="statusCad"> <!--div para exibir se o cadastro foi realizado ou não e então exibir o erro -->
                    <?php 
                    if($_statusCad == "Cadastro realizado com sucesso!"){
                        echo '<h5 style="color:green;">'.$_statusCad. '</h5>';
                    }else{
                        echo '<h5 style="color:red;">'.$_statusCad. '</h5>';
                    }
                    ?>
                </div>

                <br>

                <form method="post" action="" class="form">

                    <div id="etapa1" class="row col s12">


                    <nav class="barra_etapa">
                        <div class="nav-wrapper">
                        <div class="col s12">
                            <a class="col s4" id="here">Geral</a>
                            <a class="col s4">Endereço</a>
                            <a class="col s4">Mais</a>
                        </div>
                        </div>
                    </nav>

                    <br><br>
                    

                        <div class="input-field col s12">
                        <i class="material-icons prefix">account_circle</i>
                        <input type="text" name="nome" id="nome" maxlength="50" class="validate" oninput="converterParaCaixaAlta(this)" required autofocus>
                        <label for="nome">Nome completo</label>
                        </div>

                        <br>

                        <div class="row container col s12 center">
                        <div class="nascimento col s6">
                        <label for="datepicker" class="left">Data de nascimento:</label>
                        <input type="tel" name="nascimento" id="data" oninput="formatarData(this)" placeholder="DD/MM/AAAA" required>
                        <div class="erro-data"></div>
                        </div>

                        <div class="genero col s6">
                            <label for="genero" class="left">Gênero:</label>
                            <select name="genero" id="genero" class="validate" required>
                                <option value="" disabled selected>Selecione...</option>
                                <option value="m">Masculino</option>
                                <option value="f">Feminino</option>
                            </select>
                        </div>
                        </div>

                        <br>

                        <div class="input-field col s6">
                        <i class="material-icons prefix">pin</i>
                        <input type="text" name="cpf" id="cpfInput" maxlength="14" class="validate" required>
                        <label for="cpfInput">CPF</label>
                        </div>

                        <div class="input-field col s6">
                        <i class="material-icons prefix">assignment_ind</i>
                        <input type="text" name="rg" id="rg" maxlength="14" class="validate" oninput="formatarRG(this)" required>
                        <label for="rg">RG</label>
                        </div>


                        <div class="input-field col s12">
                        <i class="material-icons prefix">email</i>
                        <input type="email" name="email" id="email" maxlength="50" class="validate" required>
                        <label for="email">E-mail</label>
                        </div>

                        <div class="input-field col s12">
                        <i class="material-icons prefix">phone</i>
                        <input type="text" name="telefone" id="telefone" maxlength="15" class="validate" oninput="formatarTelefone(this)" required>
                        <label for="telefone">Telefone</label>
                        </div>

                        <br><br><br><br>

                        <button class="butao btn right col s6" id="proximo" onclick="proximaEtapa()">Próximo<i class="material-icons prefix">keyboard_arrow_right</i></button>

                        

                    </div>

                    <div id="etapa2" class="row col s12" style="display: none;">

                    <nav class="barra_etapa">
                        <div class="nav-wrapper">
                        <div class="col s12">
                            <a class="col s4">Geral</a>
                            <a class="col s4" id="here">Endereço</a>
                            <a class="col s4">Mais</a>
                        </div>
                        </div>
                    </nav>

                    <br><br>

                        <div class="input-field col s8">
                        <i class="material-icons prefix">place</i>
                        <input type="text" name="cep" id="cep" maxlength="9" class="validate" oninput="formatarCEP(this)" required>
                        <label for="cep">CEP</label>
                        </div>

                        <div class="input-field col s8">
                        <i class="material-icons prefix">add_road</i>
                        <input type="text" name="nomeRua" id="nomeRua" maxlength="70" class="validate" oninput="converterParaCaixaAlta(this)" required>
                        <label for="nomeRua">Rua</label>
                        </div>

                        <div class="input-field col s4">
                        <input type="number" name="numero" id="numero" maxlength="10" class="validate" required>
                        <label for="numero">Número</label>
                        </div>

                        <div class="input-field col s6">
                        <i class="material-icons prefix" style="font-size:135%">map</i>
                        <input type="text" name="cidade" id="cidade" maxlength="60" class="validate" oninput="converterParaCaixaAlta(this)" required>
                        <label for="cidade">Cidade</label>
                        </div>

                        <div class="input-field col s6">
                        <i class="material-icons prefix" style="font-size:135%">map</i>
                        <input type="text" name="municipio" id="municipio" maxlength="60" class="validate" oninput="converterParaCaixaAlta(this)" required>
                        <label for="municipio">Município</label>
                        </div>

                        <div class="input-field col s6">
                        <i class="material-icons prefix" style="font-size:135%">map</i>
                        <input type="text" name="bairro" id="bairro" maxlength="60" class="validate" oninput="converterParaCaixaAlta(this)" required>
                        <label for="bairro">Bairro</label>
                        </div>

                        <div class="input-field col s6">
                        <i class="material-icons prefix" style="font-size:135%">edit_note</i>
                        <input type="text" name="complemento" id="complemento" class="validate" oninput="converterParaCaixaAlta(this)">
                        <label for="complemento">Complemento</label>
                        </div>

                        <br><br><br><br><br><br><br><br><br><br>

                        <button class="butao btn left col s6" id="anterior" onclick="anteriorEtapa()"><i class="material-icons prefix">keyboard_arrow_left</i>Anterior</button>
                        <button class="butao btn right col s6" id="proximo" onclick="proximaEtapa()">Próximo<i class="material-icons prefix">keyboard_arrow_right</i></button>

                        <br><br><br><br>

                    </div>

                    

                    <div id="etapa3" class="row col s12" style="display: none;">

                    <nav class="barra_etapa">
                        <div class="nav-wrapper">
                        <div class="col s12">
                            <a class="col s4">Geral</a>
                            <a class="col s4">Endereço</a>
                            <a class="col s4"id="here">Mais</a>
                        </div>
                        </div>
                    </nav>

                    <br><br>

                    <h5 class="col s12 left-align">Atribuições</h5>



                    <br><br><br>

                        <div class="cargo col s6" >
                            <label for="cargo_escolhido" class="left">Cargo:</label>
                            <select name="cargo_escolhido" id="cargo_escolhido">
                            <option value="" disabled selected>Selecione...</option>
                                <?php
                                // Verifique se há registros e gere as opções do select
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        $idCargo = $row['idCargo'];
                                        $nomeCargo = $row['nomeCargo'];
                                        echo '<option value="' . $idCargo . '">'. $nomeCargo.'</option>';
                                    }
                                } else {
                                    echo '<option value="" disabled>Nenhum cargo encontrado</option>';
                                }
                                ?>
                            </select>
                        </div>


                        <div class="unidade col s6">
                            <label for="unidade_escolhida" class="left">Unidade:</label>
                            <select name="unidade_escolhida" id="unidade_escolhida">
                            <option value="" disabled selected>Selecione...</option>
                                <?php
                                // Verifique se há registros e gere as opções do select
                                if ($result2->num_rows > 0) {
                                    while ($row = $result2->fetch_assoc()) {
                                        $idUnidade = $row['idUnidade'];
                                        $nomeUnidade = $row['nomeUnidade'];
                                        echo '<option value="' . $idUnidade . '">'. $nomeUnidade.'</option>';
                                    }
                                } else {
                                    echo '<option value="" disabled>Nenhuma Unidade encontrada</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <br><br><br><br><br>
                        
                        <div class="admissao col s12">
                        <label for="dataAdmissao" class="left">Data da Admissão:</label>
                        <input type="tel" name="dataAdmissao" id="dataAdmissao" class="validate" oninput="formatarData(this)" placeholder="DD/MM/AAAA" required>
                        </div>

                        <br><br><br><br><br><br>

                        <h5 class="col s12 left-align">Uniforme</h5>

                        <br><br><br>

                        <div class="uniforme_tronco">
                            <label for="tam_tronco" class="left">Tamanho tronco:</label>
                            <select name="tam_tronco" id="tam_tronco" required>
                            <option value="" disabled selected>Selecione...</option>
                                <option value="P">Pequeno(P)</option>
                                <option value="M">Médio(M)</option>
                                <option value="G">Grande(G)</option>
                                <option value="GG">Extra grande(GG)</option>
                            </select>
                        </div>

                        <div class="uniforme_perna">
                            <label for="tam_perna" class="left">Tamanho pernas:</label>
                            <select name="tam_perna" id="tam_perna" required>
                                <option value="" disabled selected>Selecione...</option>
                                <option value="P">Pequeno(P)</option>
                                <option value="M">Médio(M)</option>
                                <option value="G">Grande(G)</option>
                                <option value="GG">Extra grande(GG)</option>
                            </select>
                        </div>

                        <div class="uniforme_calcado">
                            <label for="uniforme_calcado" class="left">Tamanho calçado:</label>
                            <select name="tam_calcado" id="uniforme_calcado" required>
                                <option value="" disabled selected>Selecione...</option>
                                <option value="35">Tam 35</option>
                                <option value="36">Tam 36</option>
                                <option value="37">Tam 37</option>
                                <option value="38">Tam 38</option>
                                <option value="39">Tam 39</option>
                                <option value="40">Tam 40</option>
                                <option value="41">Tam 41</option>
                                <option value="42">Tam 42</option>
                                <option value="43">Tam 43</option>
                                <option value="44">Tam 44</option>
                            <select>
                        </div>

                        <br><br><br><br><br>
                        
                        <div class="row col s12">
                        <button class="butao btn left col s6" id="anterior" onclick="anteriorEtapa()"><i class="material-icons prefix">keyboard_arrow_left</i>Anterior</button> 
                        <button type="submit" name="entrar" value="Finalizar cadastro" class="butao btn right col s6"><i class="material-icons prefix">how_to_reg</i>Concluir</button>
                        </div>
                        <br><br><br><br><br><br>

                    </div>
                    
                </form>


            </div>

        </main>

        <script> //JS para inicializar das listas suspensas SELECT (uniformes, cargos e unidade)
                                document.addEventListener('DOMContentLoaded', function() {
                                    var elems = document.querySelectorAll('select');
                                    var instances = M.FormSelect.init(elems);
                                });

                            //js para passar a etapas do form
                            let etapaAtual = 1;

                            function proximaEtapa() {
                                if (etapaAtual < 3) {
                                    etapaAtual++;
                                    atualizarEtapa();
                                }
                            }

                            function anteriorEtapa() {
                                if (etapaAtual > 1) {
                                    etapaAtual--;
                                    atualizarEtapa();
                                }
                            }

                            function atualizarEtapa() {
                                document.getElementById('etapa1').style.display = etapaAtual === 1 ? 'block' : 'none';
                                document.getElementById('etapa2').style.display = etapaAtual === 2 ? 'block' : 'none';
                                document.getElementById('etapa3').style.display = etapaAtual === 3 ? 'block' : 'none';
                            }


                            //corrigi o bug do label sobrepor o input quando já tem dado
                            $(document).ready(function() {
                                Materialize.updateTextFields();
                            });

                    </script>
            
            <?php include("footerContent.php");?> <!--adiciona o conteúdo do rodapé de modo modular usando o INCLUDE em PHP-->

    </body>
</HTML>