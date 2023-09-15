<?php

include("conecta.php");

$_statusCad = "";

if ($_POST) {

    $mysqli->begin_transaction();

//dados gerais
$nome = $_POST["nome"];
$email = $_POST["email"];
$cpf = $_POST["cpf"];
$senha = $cpf;

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

//uniforme
$tam_tronco_selecionado = $_POST["tam_tronco"];
$tam_perna_selecionado = $_POST["tam_perna"];
$tam_calcado_selecionado = $_POST["tam_calcado"];

    // Inserir o usuário
    $cadastrarUser = $mysqli->prepare("INSERT INTO usuarios (nome, email, cpf, senha) VALUES (?, ?, ?, ?)");
    $cadastrarUser->bind_param("ssss", $nome, $email, $cpf, $cpf);

    if ($cadastrarUser->execute()) {
        $ultimo_id = $mysqli->insert_id;
        $ultimo_id_inserido = $ultimo_id;

        // Inserir o endereço
        $cadastrarEndereco = $mysqli->prepare("INSERT INTO usedenderecos (cep, rua, numero, cidade, municipio, bairro, complemento, usuarioID) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $cadastrarEndereco->bind_param("ssissssi", $cep, $nomeRua, $numero, $cidade, $municipio, $bairro, $complemento, $ultimo_id_inserido);

        //inserir cargo
        if($cadastrarEndereco->execute()) {
            $ultimo_id_inserido;

            $cadastrarCargo = $mysqli->prepare("INSERT INTO usedcargos (nomeCargo, usuarioID) VALUES (?, ?)");
            $cadastrarCargo->bind_param("si", $cargo, $ultimo_id_inserido);
        }else{
            $_statusCad = "Falha no cadastro do endereço" . " Erro: " . $mysqli->error;
            $mysqli->rollback();
            $mysqli->close();
        }
        //inserir unidade
        if($cadastrarCargo->execute()) {
            $ultimo_id_inserido;

            $cadastrarUnidade = $mysqli->prepare("INSERT INTO usedunidades (nomeUnidade, usuarioID) VALUES (?, ?)");
            $cadastrarUnidade->bind_param("si", $unidade, $ultimo_id_inserido);
        }else{
            $_statusCad = "Falha no cadastro do cargo" . " Erro: " . $mysqli->error;
            $mysqli->rollback();
            $mysqli->close();
        }
        //inserir uniformes
        if($cadastrarUnidade->execute()) {
            $ultimo_id_inserido;

            $cadastrarUniforme = $mysqli->prepare("INSERT INTO useduniformes (tamTronco, tamPerna, tamCalcado, usuarioID) VALUES (?, ?, ?, ?)");
            $cadastrarUniforme->bind_param("ssii", $tam_tronco_selecionado, $tam_perna_selecionado, $tam_calcado_selecionado, $ultimo_id_inserido);
        }else{
            $_statusCad = "Falha no cadastro do uniforme" . " Erro: " . $mysqli->error;
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
    $_statusCad = "Falha no cadastro do usuário!" . " Erro: " . $mysqli->error;
    $mysqli->rollback();
    $mysqli->close();
}
}

include("conecta.php");

$sql = "SELECT idCargo, nomeCargo FROM cargos WHERE status = 1 ORDER BY idCargo";
$result = $mysqli->query($sql);

$sql2 = "SELECT idUnidade, nomeUnidade FROM unidades WHERE status = 1 ORDER BY idUnidade";
$result2 = $mysqli->query($sql2);

?>

<!DOCTYPE html>
<HTML lang="pt-BR">
    <HEAD>
        <TITLE>EBDS | Cadastro </TITLE>

<?php include("headContent.php"); ?>

    </HEAD>
    <body>
        <main class="box container">


            <div class="container center">

                <h4>CADASTRO DE FUNCIONÁRIOS</h4>

                    <script> //JS para inicializar a lista suspensa do cargo
                                document.addEventListener('DOMContentLoaded', function() {
                                    var elems = document.querySelectorAll('select');
                                    var instances = M.FormSelect.init(elems);
                                });
                    </script>

                <BR><BR>

                <form method="post" action="" class="form">

                    <div class="input-field">
                    <i class="material-icons prefix">account_circle</i>
                    <input type="text" name="nome" id="nome" maxlength="50" class="validate" required autofocus>
                    <label for="nome">Nome completo</label>
                    </div>

                    <div class="input-field">
                    <i class="material-icons prefix">email</i>
                    <input type="email" name="email" id="email" maxlength="50" class="validate" required>
                    <label for="email">E-mail</label>
                    </div>

                    <div class="input-field">
                    <i class="material-icons prefix">pin</i>
                    <input type="text" name="cpf" id="cpf" maxlength="11" class="validate" required>
                    <label for="cpf">CPF</label>
                    </div>

                    <br><h5 class="left">Endereço:</h5><br><br><br>

                    <div class="left input-field col s12 left" style="width: 35%;">
                    <i class="material-icons prefix" style="font-size:125%">place</i>
                    <input type="number" name="cep" id="cep" maxlength="20" class="validate" onclick="" required>
                    <label for="cep">CEP</label>
                    </div>
                    

                    <div class="input-field col s12 right" style="width: 60%;">
                    <input type="text" name="nomeRua" id="nomeRua" maxlength="70" class="validate" required>
                    <label for="nomeRua">Logradouro</label>
                    </div>

                    <br> <br> <br><br>

                <div class="userDados">

                    <div class="input-field col s12">
                    <i class="material-icons prefix">123</i>
                    <input type="number" name="numero" id="numero" maxlength="10" class="validate" required>
                    <label for="numero">Número</label>
                    </div>

                    <div class="input-field col s12">
                    <i class="material-icons prefix" style="font-size:135%">map</i>
                    <input type="text" name="cidade" id="cidade" maxlength="60" class="validate" required>
                    <label for="cidade">Cidade</label>
                    </div>

                    <div class="input-field col s12">
                    <i class="material-icons prefix" style="font-size:135%">map</i>
                    <input type="text" name="municipio" id="municipio" maxlength="60" class="validate" required>
                    <label for="municipio">Município</label>
                    </div>

                    <div class="input-field col s12">
                    <i class="material-icons prefix" style="font-size:135%">map</i>
                    <input type="text" name="bairro" id="bairro" maxlength="60" class="validate" required>
                    <label for="bairro">Bairro</label>
                    </div>


                    <div class="input-field col s12">
                    <i class="material-icons prefix" style="font-size:135%">edit_note</i>
                    <input type="text" name="complemento" id="complemento" aria-expanded="100" class="validate">
                    <label for="complemento">Complemento</label>
                    </div>


                </div>

                <br><h5 class="left">Atribuições do funcionário:</h5><br><br><br>
                <div class="unidcarniv">                   

                    <div class="cargo col s6" style="text-align:left">
                        <label for="cargo_escolhido">Cargo:</label>
                        <select name="cargo_escolhido" id="cargo_escolhido">
                        <option value="" disabled selected>Selecione...</option>
                            <?php
                            // Verifique se há registros e gere as opções do select
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $idCargo = $row['idCargo'];
                                    $nomeCargo = $row['nomeCargo'];
                                    echo '<option value="' . $nomeCargo . '">'. $nomeCargo.'</option>';
                                }
                            } else {
                                echo '<option value="" disabled>Nenhum cargo encontrado</option>';
                            }
                            ?>
                        </select>
                    </div>


                    <div class="unidade col s6" style="text-align:left">
                        <label>Unidade:</label>
                        <select name="unidade_escolhida" id="unidade_escolhida">
                        <option value="" disabled selected>Selecione...</option>
                            <?php
                            // Verifique se há registros e gere as opções do select
                            if ($result2->num_rows > 0) {
                                while ($row = $result2->fetch_assoc()) {
                                    $idUnidade = $row['idUnidade'];
                                    $nomeUnidade = $row['nomeUnidade'];
                                    echo '<option value="' . $nomeUnidade . '">'. $nomeUnidade.'</option>';
                                }
                            } else {
                                echo '<option value="" disabled>Nenhuma Unidade encontrada</option>';
                            }
                            ?>
                        </select>
                    </div>

                </div>

                <br>

                    <h5 class="left">Uniforme</h5><br><br><br>

                <div class="unifcarg">

                    <div class="uniforme_tronco" style="text-align:left">
                        <label>Tronco:</label>
                        <select name="tam_tronco" id="tam_tronco">
                        <option value="" disabled selected>Selecione...</option>
                            <option value="P">Pequeno(P)</option>
                            <option value="M">Médio(M)</option>
                            <option value="G">Grande(G)</option>
                            <option value="GG">Extra grande(GG)</option>
                        </select>
                    </div>

                    <div class="uniforme_perna" style="text-align:left">
                        <label>Pernas:</label>
                        <select name="tam_perna" id="tam_perna">
                            <option value="" disabled selected>Selecione...</option>
                            <option value="P">Pequeno(P)</option>
                            <option value="M">Médio(M)</option>
                            <option value="G">Grande(G)</option>
                            <option value="GG">Extra grande(GG)</option>
                        </select>
                    </div>

                    <div class="uniforme_calcado" style="text-align:left">
                        <label >Calçado:</label>
                        <select name="tam_calcado" id="uniforme_calcado">
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
                        
                        <br><br><br><br><br><br><br>

                        <input type="submit" name="entrar" value="Salvar" class="btn center-align">
                </div>

                    
                </form>

                <div class="statusCad center" id="statusCad"> <!--div para exibir se o cadastro foi realizado ou não e então exibir o erro -->
                    <?php echo $_statusCad;?>
                </div>

            </div>

            <BR>

        </main>
            
            <?php include("footerContent.php");?> <!--adiciona o conteúdo do rodapé de modo modular usando o INCLUDE em PHP-->

    </body>
</HTML>