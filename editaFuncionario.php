<?php

require "autenticaContent.php";
require "conecta.php";
require "funcoes.php";
require "mascaraContent.php";

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
    // Recupere o ID do registro a ser exibido
    $id = $_GET["id"];
    
    if(($_SESSION['nivelAcesso'] != 2) && ($_SESSION['idFuncionarioLogado'] != $id)) {
        echo "Acesso negado!";
        exit;
    }

    date_default_timezone_set('America/Sao_Paulo'); //define o fuso no php para usar o date ou

$sql = "SELECT * FROM funcionarios WHERE idFuncionario = $id";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $idFuncionario = $row['idFuncionario'];
        $nome = $row['nome'];
        $cpf = $row['cpf'];
        $rg = $row['rg'];
        $genero = $row['genero'];
        $email = $row['email'];
        $telefone = $row['telefone'];
        $dataCadastro = $row['dataCadFuncionario'];
        $status = $row['status'];
        $funcObservacoes = $row['funcObservacoes'];
        $responsavelCadastro = $row['responsavelCadastro'];

    }
}


$sql = "SELECT * FROM usedenderecos WHERE funcionarioID = $id";
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

    }
}

//query do cargo
$sql = "SELECT cargoID FROM usedcargos WHERE funcionarioID = $id";
$result2 = $mysqli->query($sql);

if($result2->num_rows > 0){
    while ($row = $result2->fetch_assoc())
    $atualCargoID = $row['cargoID'];

    $sql10 = "SELECT nomeCargo FROM cargos WHERE idCargo = $atualCargoID";
    $result10 = $mysqli->query($sql10);
    if($result10->num_rows > 0){
        while ($row = $result10->fetch_assoc()) {
        $cargoAtual = $row['nomeCargo'];
        }
}
}else{
    $cargoAtual = "Não atribuído a nenhum cargo!";
}

//query da unidade
$sql = "SELECT unidadeID FROM usedunidades WHERE funcionarioID = $id";
$result3 = $mysqli->query($sql);

if($result3->num_rows > 0){
    while ($row = $result3->fetch_assoc())
    $atualUnidadeID = $row['unidadeID'];

    $sql11 = "SELECT nomeUnidade FROM unidades WHERE idUnidade = $atualUnidadeID";
    $result11 = $mysqli->query($sql11);
    if($result11->num_rows > 0){
        while ($row = $result11->fetch_assoc()) {
        $unidadeAtual = $row['nomeUnidade'];
        }
}
}else{
    $unidadeAtual = "Não atribuído a nenhuma unidade!";
}

//query dos uniformes
$sql = "SELECT tamTronco, tamPerna, tamCalcado FROM useduniformes WHERE funcionarioID = $id";
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

//query da data de admissao
$sql = "SELECT * FROM admissao WHERE funcionarioID = $id";
$result5 = $mysqli->query($sql);

if($result5->num_rows > 0){
    while ($row = $result5->fetch_assoc()) {
    $admissao = $row['dataAdmissao'];
}
}

$sql2 = "SELECT * FROM controleferias WHERE funcionarioID = $id";
$result6 = $mysqli->query($sql2);

include("conecta.php");

$sql = "SELECT idCargo, nomeCargo FROM cargos WHERE status = 1 ORDER BY nomeCargo";
$result23 = $mysqli->query($sql);

$sql2 = "SELECT idUnidade, nomeUnidade FROM unidades WHERE status = 1 ORDER BY nomeUnidade";
$result24 = $mysqli->query($sql2);


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
                        <h5><?php echo $cargoAtual?> | <?php echo $unidadeAtual?></h5><br>
                        <div class="row">
                        <div class="col s12">
                        <ul class="tabs">
                        <li class="tab col s3"><a href="#option1">Informações Gerais</a></li>
                        <li class="tab col s3"><a href="#option2">Endereço</a></li>
                        <li class="tab col s3"><a href="#option3">Atribuições</a></li>
                        <li class="tab col s3"><a href="#option4">Uniforme</a></li><br>
                        </ul>
                        <br>
                        </div>
                        <div id="option1" class="col s12">
                        <button type="submit" name="submit_form1" class="search btn" id="submit_form1" value="Salvar alterações"><i class="material-icons left">check</i>Salvar</button>
                        <a href="listaFuncionarios.php" class="search btn"><i class="material-icons left">reply</i>Voltar</a><br><br>
                        <label for="nome">Nome completo:</label><br>
                        <input type="text" name="nome" id="nome" oninput="converterParaCaixaAlta(this)" value="<?php echo $nome?>"><br>
                        <label for="dataAdmissao">Data da admissao:</label><br>
                        <input type="date" name="dataAdmissao" id="dataAdmissao" value="<?php echo $admissao?>" readonly><br>
                        <label for="id">Funcionário ID:</label><br>
                        <input type="text" name="id" id="id" value="<?php echo $idFuncionario?>" readonly><br>
                        <label for="cpfInput">CPF:</label><br>
                        <input type="text" name="cpf" id="cpfInput" maxlength="14" value="<?php echo $cpf ?>" ><br>
                        <label for="rg">RG:</label><br>
                        <input type="text" name="rg" id="rg" maxlength="14" oninput="formatarRG(this)" value="<?php echo $rg ?>" ><br>
                        <label for="genero">Genero:</label><br>
                        <?php echo lista_suspensa_genero($genero);?>
                        <label for="email">E-mail:</label><br>
                        <input type="text" name="email" id="email" value="<?php echo $email ?>" ><br>
                        <label for="telefone">Telefone:</label><br>
                        <input type="text" name="telefone" id="telefone" oninput="formatarTelefone(this)" value="<?php echo $telefone ?>" ><br>
                        <label for="dataCad">Data e hora do cadastro:</label><br>
                        <input type="text" name="dataCad" id="dataCad" readonly value="<?php echo $dataCadastro ?>"><br>
                        <label for="status">Status do funcionário:</label><br>
                        <?php echo lista_suspensa_inativa($status)?> <!-- chama a espetacular criação de EMERSON function da lista suspensa de ativo e inativo -->
                        <br>
                        <label for="responsavelCadastro">Resposável pelo cadastro:</label><br>
                        <input type="text" name="responsavelCadastro" id="responsavelCadastro" value="<?php echo $responsavelCadastro;?>"  readonly><br>
                        <legend>Observações:</legend>
                            <textarea name="funcObservacoes" data-length="500" class="textarea"><?php echo $funcObservacoes;?></textarea>
                            <br><br>
                        </div>

                    </form>

                    <!-- form para a 2° tabela a de endereços-->
                <form id="form2" name="form2" action="atualizaCadastro.php" method="post">

                        <input type="hidden" name="form_id" value="2">

                        <!--exibe e edita endereço-->                        
                        <div id="option2" class="col s12">

                        <button type="submit" name="submit_form2" class="search btn" id="submit_form2" value="Salvar alterações"><i class="material-icons left">check</i>Salvar</button>
                        <a href="listaFuncionarios.php" class="search btn"><i class="material-icons left">reply</i>Voltar</a><br><br>
                        
                        <input type="hidden" name="id" id="id" value="<?php echo $idFuncionario?>">

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

                        </div>

                </form>

                    <!-- form para a 3° tabela a de atribuições-->
                <form id="form3" name="form3" action="atualizaCadastro.php" method="post">

                        <input type="hidden" name="form_id" value="3">

                            <!--exibe e edita as atribuições-->
                        <div id="option3" class="row col s12">
                            <button type="submit" name="submit_form3" class="search btn" id="submit_form3" value="Salvar alterações"><i class="material-icons left">check</i>Salvar</button>
                            <a href="listaFuncionarios.php" class="search btn"><i class="material-icons left">reply</i>Voltar</a><br><br>
                            <input type="hidden" name="id" id="id" value="<?php echo $idFuncionario?>">
                            <div class="cargo col s6" >
                                <label for="cargo_escolhido" class="labSelect">Cargo:</label>
                                <select name="cargo_escolhido" id="cargo_escolhido">
                                <option value="<?php echo $atualCargoID;?>"selected><?php echo $cargoAtual;?></option>
                                    <?php
                                    // Verifique se há registros e gere as opções do select
                                    if ($result23->num_rows > 0) {
                                        while ($row = $result23->fetch_assoc()) {
                                            $idCargo = $row['idCargo'];
                                            $nomeCargo = $row['nomeCargo'];
                                            if($idCargo != $atualCargoID){
                                            echo '<option value="' . $idCargo . '">'. $nomeCargo.'</option>';
                                            }
                                        }
                                    } else {
                                        echo '<option value="" disabled>Nenhum cargo encontrado</option>';
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="unidade col s6">
                                <label for="unidade_escolhida" class="labSelect">Unidade:</label>
                                <select name="unidade_escolhida" id="unidade_escolhida">
                                <option value="<?php echo $atualUnidadeID; ?>"selected><?php echo $unidadeAtual; ?></option>
                                    <?php
                                    // Verifique se há registros e gere as opções do select
                                    if ($result24->num_rows > 0) {
                                        while ($row = $result24->fetch_assoc()) {
                                            $idUnidade = $row['idUnidade'];
                                            $nomeUnidade = $row['nomeUnidade'];

                                            If ($idUnidade != $atualUnidadeID){
                                            echo '<option value="' . $idUnidade . '">'. $nomeUnidade.'</option>';
                                        }
                                        }
                                    } else {
                                        echo '<option value="" disabled>Nenhuma Unidade encontrada</option>';
                                    }
                                    ?>
                                </select>
                            </div>

                            <label for="dataAdmissao">Data da admissao:</label><br>
                            <input type="date" name="dataAdmissao" id="dataAdmissao" value="<?php echo $admissao;?>" readonly><br><br>

                            <h5>Histórico de férias</h5>
                            <br>
                            <a class="search" href="controleFerias?id=<?php echo $idFuncionario;?>">Adicionar novo registro</a>

                                <table>
                                    <thead>
                                        <tr>
                                            <th>Data Início</th>
                                            <th>Data Fim</th>
                                            <th>Rspnsavel pelo Cadastro</th>
                                            <th>Observações</th>
                                        </tr>
                                    </thead>
                                        <tbody>
                                        <?php
                                        if ($result6->num_rows > 0) {
                                            while ($row = $result6->fetch_assoc()) {
                                                $dataInicioUltFerias = $row['dataInicioUltFerias'];
                                                $dataFimUltFerias = $row['dataFimUltFerias'];
                                                $feriasObservacoes = $row['feriasObservacoes'];
                                                $responsavelCadastro = $row['nomeResponsavelCadastro'];                                           

                                            echo '<tr>';
                                            echo '<td><p>' . date('d/m/Y', strtotime($dataInicioUltFerias)) . '</p></td>';
                                            echo '<td><p>' . date('d/m/Y', strtotime($dataFimUltFerias)) . '</p></td>';
                                            echo '<td><p>' . $responsavelCadastro . '</p></td>';
                                            echo '<td><p>' . $feriasObservacoes . '</p></td>';
                                            echo '</tr>';
                                            }

                                            
                                        }else{
                                            echo '<tr><td colspan="2">Nenhum registro encontrato</td></tr>';
                                        }
                                        ?>
                                        </tbody>
                                </table>

                            </div>
                        </div>

                </form>



                <form id="form4" name="form4" action="atualizaCadastro.php" method="post">

                    <input type="hidden" name="form_id" value="4">

                        <!--exibe e edita o tamanho do uniforme-->   
                        <div id="option4" class="col s12">
                        <button type="submit" name="submit_form4" class="search btn" id="submit_form4" value="Salvar alterações"><i class="material-icons left">check</i>Salvar</button>
                        <a href="listaFuncionarios.php" class="search btn"><i class="material-icons left">reply</i>Voltar</a><br><br>
                        <input type="hidden" name="id" id="id" value="<?php echo $idFuncionario?>">
                        <div class="uniforme_tronco">
                            <label for="tam_tronco" class="labSelect">Tronco:</label>
                            <select name="tam_tronco" id="tam_tronco" required>
                            <option value="<?php echo $tamTronco;?>" selected><?php echo $tamTronco;?></option>
                                <option value="P">Pequeno(P)</option>
                                <option value="M">Médio(M)</option>
                                <option value="G">Grande(G)</option>
                                <option value="GG">Extra grande(GG)</option>
                            </select>
                        </div>

                        <div class="uniforme_perna">
                            <label for="tam_perna" class="labSelect">Pernas:</label>
                            <select name="tam_perna" id="tam_perna" required>
                                <option value="<?php echo $tamPerna;?>"selected><?php echo $tamPerna;?></option>
                                <option value="P">Pequeno(P)</option>
                                <option value="M">Médio(M)</option>
                                <option value="G">Grande(G)</option>
                                <option value="GG">Extra grande(GG)</option>
                            </select>
                        </div>

                        <div class="uniforme_calcado">
                            <label for="uniforme_calcado" class="labSelect">Calçado:</label>
                            <select name="tam_calcado" id="uniforme_calcado" required>
                                <option value="<?php echo $tamCalcado;?>"selected><?php echo $tamCalcado;?></option>
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

                <form>



        </main>

        <?php include("footerContent.php");?> <!--adiciona o conteúdo do rodapé de modo modular usando o INCLUDE em PHP-->

    </body>
</HTML>