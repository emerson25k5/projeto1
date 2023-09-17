<?php

include("conecta.php");

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
    // Recupere o ID do registro a ser exibido
    $id = $_GET["id"];


$sql = "SELECT * FROM usuarios WHERE idUsuario = $id";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $idUsuario = $row['idUsuario'];
        $nome = $row['nome'];
        $cpf = $row['cpf'];
        $rg = $row['rg'];
        $genero = $row['genero'];
        $email = $row['email'];
        $telefone = $row['telefone'];
        $dataCadastro = $row['dataCadUsuario'];
        $status = $row['status'];

        if($genero == "m"){
            $genero = "Masculino";
        }elseif($genero == "f"){
            $genero = "Feminino";
        }elseif($genero == "o"){
            $genero = "Outro";
        }

        if($status == 1){
            $status = "Ativo";
        }else{
            $status = "Inativo";
        }
    }
}


$sql = "SELECT * FROM usedEnderecos WHERE usuarioID = $id";
$result1 = $mysqli->query($sql);

if ($result1->num_rows > 0) {
    while ($row = $result1->fetch_assoc()) {
        $cep = $row['cep'];
        $rua = $row['rua'];
        $numero = $row['numero'];
        $bairro = $row['bairro'];
        $cidade = $row['cidade'];
        $municipio = $row['municipio'];
        $complemento = $row['complemento'];
        $dataCadastroEnd = $row['dataCadastro'];
        $status = $row['status'];

        if($status == 1){
            $status = "Ativo";
        }else{
            $status = "Inativo";
        }
    }
}

//query do cargo
$sql = "SELECT nomeCargo FROM usedcargos WHERE usuarioID = $id";
$result2 = $mysqli->query($sql);

if($result2->num_rows > 0){
    while ($row = $result2->fetch_assoc()) {
    $cargo = $row['nomeCargo'];
}
}else{
    $cargo = "Não atribuído a nenhum cargo!";
}

//query da unidade
$sql = "SELECT nomeUnidade FROM usedUnidades WHERE usuarioID = $id";
$result3 = $mysqli->query($sql);

if($result3->num_rows > 0){
    while ($row = $result3->fetch_assoc()) {
    $unidade = $row['nomeUnidade'];
}
}else{
    $unidade = "Não atribuído a nenhuma unidade!";
}

//query dos uniformes
$sql = "SELECT tamTronco, tamPerna, tamCalcado FROM usedUniformes WHERE usuarioID = $id";
$result4 = $mysqli->query($sql);

if($result4->num_rows > 0){
    while ($row = $result4->fetch_assoc()) {
    $tamTronco = $row['tamTronco'];
    $tamPerna = $row['tamPerna'];
    $tamCalcado = $row['tamCalcado'];
}
}else{
    $tamTronco = "Não atribuído a nenhum uniforme!";
    $tamPerna = "Não atribuído a nenhum uniforme!";
    $tamCalcado = "Não atribuído a nenhum uniforme!";
}

$sql = "SELECT * FROM admissao WHERE usuarioID = $id";
$result5 = $mysqli->query($sql);

if($result5->num_rows > 0){
    while ($row = $result5->fetch_assoc()) {
    $admissao = $row['dataAdmissao'];
}
}

$mysqli->close();

}
?>

<!DOCTYPE html>
<HTML lang="pt-BR">
    <HEAD>
        <TITLE>EBDS | Editar funcionário </TITLE>

        <?php include("headContent.php"); ?>

        <style>
            label {
                font-weight: bold;
            }
            h5 {
                color: grey;
            }
        </style>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
            // Inicialize as tabs
            M.Tabs.init(document.querySelector('.tabs'));
        });

        //JS para inicializar das listas suspensas SELECT (uniformes, cargos e unidade)
        document.addEventListener('DOMContentLoaded', function() {
                                    var elems = document.querySelectorAll('select');
                                    var instances = M.FormSelect.init(elems);
                                });
        </script>

        <?php include("mascaraContent.php");?>

    </HEAD>
    <body>
        <main class="box container">

                    <form id="form1" name="form1" action="atualizaCadastro.php" method="post">

                        <input type="hidden" name="form_id" value="1">

                        <!--exibe e edita dados gerais-->
                        <h4><?php echo $nome ?></h4>
                        <h5><?php echo $cargo?> | <?php echo $unidade?></h5><br>
                        <div class="row">
                        <div class="col s12">
                        <ul class="tabs">
                        <li class="tab col s3"><a href="#option1">Informações Gerais</a></li>
                        <li class="tab col s3"><a href="#option2">Endereço</a></li>
                        <li class="tab col s3"><a href="#option3">Atribuições</a></li>
                        <li class="tab col s3"><a href="#option4">Uniforme</a></li>
                        </ul>
                        <br>
                        </div>
                        <div id="option1" class="col s12">
                        <label for="nome">Nome completo:</label><br>
                        <input type="text" name="nome" id="nome" oninput="converterParaCaixaAlta(this)" value="<?php echo $nome?>"><br>
                        <label for="dataAdmissao">Data da admissao:</label><br>
                        <input type="date" name="dataAdmissao" id="dataAdmissao" value="<?php echo $admissao?>"><br>
                        <label for="id">Funcionário ID:</label><br>
                        <input type="text" name="id" id="id" value="<?php echo $idUsuario?>" readonly><br>
                        <label for="cpfInput">CPF:</label><br>
                        <input type="text" name="cpf" id="cpfInput" maxlength="14" value="<?php echo $cpf ?>" ><br>
                        <label for="rg">RG:</label><br>
                        <input type="text" name="rg" id="rg" maxlength="14" oninput="formatarRG(this)" value="<?php echo $rg ?>" ><br>
                        <label for="genero">Genero:</label><br>
                        <input type="text" name="genero" id="genero" readonly value="<?php echo $genero ?>" ><br>
                        <label for="email">E-mail:</label><br>
                        <input type="text" name="email" id="email" oninput="converterParaCaixaAlta(this)" value="<?php echo $email ?>" ><br>
                        <label for="telefone">Telefone:</label><br>
                        <input type="text" name="telefone" id="telefone" oninput="formatarTelefone(this)" value="<?php echo $telefone ?>" ><br>
                        <label for="dataCad">Data e hora do cadastro:</label><br>
                        <input type="text" name="dataCad" id="dataCad" readonly value="<?php echo $dataCadastro ?>"><br>
                        <label for="status">Status do funcionário:</label><br>
                        <input type="text" name="status" id="status" readonly value="<?php echo $status?>"><br>
                        <input type="submit" name="submit_form1" class="btn" id="submit_form1" value="Salvar alterações">

                        </div>

                    </form>
                


                        <!--exibe e edita endereço-->                        
                        <div id="option2" class="col s12">
                        <label for="cep">CEP:</label><br>
                        <input type="text" name="cep" id="cep" oninput="formatarCEP(this)" value="<?php echo $cep?>" >

                        <label for="rua">Logradouro:</label><br>
                        <input type="text" name="rua" id="rua" oninput="converterParaCaixaAlta(this)" value="<?php echo $rua?>"  ><br>

                        <label for="numero">Número:</label><br>
                        <input type="text" name="numero" id="numero" value="<?php echo $numero ?>" ><br>

                        <label for="bairro">Bairro:</label><br>
                        <input type="text" name="bairro" id="bairro" oninput="converterParaCaixaAlta(this)" value="<?php echo $bairro ?>" ><br>

                        <label for="cidade">Cidade:</label><br>
                        <input type="text" name="cidade" id="cidade" oninput="converterParaCaixaAlta(this)" value="<?php echo $cidade ?>" ><br>

                        <label for="municipio">Municipio:</label><br>
                        <input type="text" name="municipio" id="municipio" oninput="converterParaCaixaAlta(this)" value="<?php echo $municipio ?>" ><br>

                        <label for="complemento">Complemento:</label><br>
                        <input type="text" name="complemento" id="complemento" oninput="converterParaCaixaAlta(this)" value="<?php echo $complemento ?>" ><br>

                        <label for="complemento">Data e hora do cadastro:</label><br>
                        <input type="text" name="dataCad" id="dataCad" value="<?php echo $dataCadastroEnd ?>" ><br>

                        <label for="status">Status do endereço:</label><br>
                        <input type="text" name="status" id="status" oninput="converterParaCaixaAlta(this)" value="<?php echo $status ?>" ><br>
                        </div>

                        <!--exibe e edita as atribuições-->   
                        <div id="option3" class="col s12">
                        <label for="cargo">Cargo:</label><br>
                        <input type="text" name="cargo" id="cargo" value="<?php echo $cargo?>" >

                        <label for="rua">Unidade:</label><br>
                        <input type="text" name="rua" id="rua" value="<?php echo $unidade?>"  ><br>

                        <label for="dataAdmissao">Data da admissao:</label><br>
                        <input type="text" name="dataAdmissao" id="dataAdmissao" value="<?php echo $admissao?>" ><br>

                        <label for="dataAdmissao">Data das ultimas férias:</label><br>
                        <input type="date" name="dataAdmissao" id="dataAdmissao" value="<?php?>" ><br>
                        </div>

                        <!--exibe e edita o tamanho do uniforme-->   
                        <div id="option4" class="col s12">
                        <div class="uniforme_tronco">
                            <label for="tam_tronco" class="labSelect">Tronco:</label>
                            <select name="tam_tronco" id="tam_tronco" required>
                            <option value="<?php echo $tamTronco;?>" disabled selected><?php echo $tamTronco;?></option>
                                <option value="P">Pequeno(P)</option>
                                <option value="M">Médio(M)</option>
                                <option value="G">Grande(G)</option>
                                <option value="GG">Extra grande(GG)</option>
                            </select>
                        </div>

                        <div class="uniforme_perna">
                            <label for="tam_perna" class="labSelect">Pernas:</label>
                            <select name="tam_perna" id="tam_perna" required>
                                <option value="<?php echo $tamPerna;?>" disabled selected><?php echo $tamPerna;?></option>
                                <option value="P">Pequeno(P)</option>
                                <option value="M">Médio(M)</option>
                                <option value="G">Grande(G)</option>
                                <option value="GG">Extra grande(GG)</option>
                            </select>
                        </div>

                        <div class="uniforme_calcado">
                            <label for="uniforme_calcado" class="labSelect">Calçado:</label>
                            <select name="tam_calcado" id="uniforme_calcado" required>
                                <option value="<?php echo $tamCalcado;?>" disabled selected><?php echo $tamCalcado;?></option>
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
                        </div>
                    </div>



        </main>

        <?php include("footerContent.php");?> <!--adiciona o conteúdo do rodapé de modo modular usando o INCLUDE em PHP-->

    </body>
</HTML>