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
    $sep = explode("-", $admissao);

    $ano = $sep[0];
    $mes = $sep[1];
    $dia = $sep[2];

    $admissao = "" .$dia."/".$mes."/".$ano."";
}
}else{
    $admissao = "Nenhuma data de admissão cadastrada!";
}
}

?>

<!DOCTYPE html>
<HTML lang="pt-BR">
    <HEAD>
        <TITLE>EBDS | Informações </TITLE>

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

        </script>

    </HEAD>
    <body>
        <main class="box container">

            <div>
                
                        <!--exibe dados gerais-->
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
                        <label for="dataAdmissao">Data da admissao:</label><br>
                        <input type="text" name="dataAdmissao" id="dataAdmissao" value="<?php echo $admissao?>" readonly> <br>
                        <label for="id">Funcionário ID:</label><br>
                        <input type="text" name="id" id="id" value="<?php echo $idUsuario?>" readonly><br>
                        <label for="cpf">CPF:</label><br>
                        <input type="text" name="cpf" id="cpf" value="<?php echo $cpf ?>" readonly><br>
                        <label for="rg">RG:</label><br>
                        <input type="text" name="rg" id="rg" value="<?php echo $rg ?>" readonly><br>
                        <label for="genero">Genero:</label><br>
                        <input type="text" name="genero" id="genero" value="<?php echo $genero ?>" readonly><br>
                        <label for="email">E-mail:</label><br>
                        <input type="text" name="email" id="email" value="<?php echo $email ?>" readonly><br>
                        <label for="telefone">Telfone:</label><br>
                        <input type="text" name="telefone" id="telefone" value="<?php echo $telefone ?>" readonly><br>
                        <label for="dataCad">Data e hora do cadastro:</label><br>
                        <input type="text" name="dataCad" id="dataCad" value="<?php echo $dataCadastro ?>" readonly><br>
                        <label for="status">Status do funcionário:</label><br>
                        <input type="text" name="status" id="status" value="<?php echo $status?>"  readonly><br>
                        </div>


                        <!--exibe endereço-->                        
                        <div id="option2" class="col s12">
                        <label for="cep">CEP:</label><br>
                        <input type="text" name="cep" id="cep" value="<?php echo $cep?>" readonly><br>

                        <label for="rua">Logradouro:</label><br>
                        <input type="text" name="rua" id="rua" value="<?php echo $rua?>" readonly><br>

                        <label for="numero">Número:</label><br>
                        <input type="text" name="numero" id="numero" value="<?php echo $numero ?>" readonly><br>

                        <label for="bairro">Bairro:</label><br>
                        <input type="text" name="bairro" id="bairro" value="<?php echo $bairro ?>" readonly><br>

                        <label for="cidade">Cidade:</label><br>
                        <input type="text" name="cidade" id="cidade" value="<?php echo $cidade ?>" readonly><br>

                        <label for="municipio">Municipio:</label><br>
                        <input type="text" name="municipio" id="municipio" value="<?php echo $municipio ?>" readonly><br>

                        <label for="complemento">Complemento:</label><br>
                        <input type="text" name="complemento" id="complemento" value="<?php echo $complemento ?>" readonly><br>

                        <label for="complemento">Data e hora do cadastro:</label><br>
                        <input type="text" name="dataCad" id="dataCad" value="<?php echo $dataCadastroEnd ?>" readonly><br>

                        <label for="status">Status do endereço:</label><br>
                        <input type="text" name="status" id="status" value="<?php echo $status ?>" readonly><br>

                        </div>

                        <!--exibe as atribuições-->   
                        <div id="option3" class="col s12">
                        <label for="cargo">Cargo:</label><br>
                        <input type="text" name="cargo" id="cargo" value="<?php echo $cargo?>" readonly>

                        <label for="rua">Unidade:</label><br>
                        <input type="text" name="rua" id="rua" value="<?php echo $unidade?>"  readonly><br>

                        <label for="dataAdmissao">Data da admissao:</label><br>
                        <input type="text" name="dataAdmissao" id="dataAdmissao" value="<?php echo $admissao?>" readonly><br>

                        <label for="dataAdmissao">Data das ultimas férias:</label><br>
                        <input type="text" name="dataAdmissao" id="dataAdmissao" value="<?php?>" readonly><br>
                        </div>

                        <!--exibe as atribuições-->   
                        <div id="option4" class="col s12">
                        <label for="uniforme_tronco">Tamanho do uniforme TRONCO:</label><br>
                        <input type="text" name="tamTronco" id="uniforme_tronco" value="<?php echo $tamTronco ?>" readonly>

                        <label for="tamPerna">Tamanho do uniforme PERNA:</label><br>
                        <input type="text" name="tamPerna" id="tamPerna" value="<?php echo $tamPerna?>"  readonly><br>

                        <label for="tamCalcado">Tamanho do uniforme CALÇADO:</label><br>
                        <input type="text" name="tamCalcado" id="tamCalcado" value="<?php echo $tamCalcado?>" readonly><br>
                        </div>

                </div>
            </div>

        </main>

        <?php include("footerContent.php");?> <!--adiciona o conteúdo do rodapé de modo modular usando o INCLUDE em PHP-->

    </body>
</HTML>