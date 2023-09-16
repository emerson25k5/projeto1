<?php

include("conecta.php");

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
    // Recupere o ID do registro a ser excluído
    $id = $_GET["id"];


$sql = "SELECT * FROM usuarios WHERE idUsuario = $id";
$result = $mysqli->query($sql);

$sql = "SELECT * FROM usedEnderecos WHERE usuarioID = $id";
$result1 = $mysqli->query($sql);

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

    $admissao = "Funcionário admitido em: " .$dia."/".$mes."/".$ano."";
}
}else{
    $admissao = "Nenhuma data de admissão cadastrada!";
}
}

?>

<!DOCTYPE html>
<HTML lang="pt-BR">
    <HEAD>
        <TITLE>EBDS | Funcionário </TITLE>

        <?php include("headContent.php"); ?>

        <style>
            label {
                font-weight: bold;
            }
            h5 {
                color: grey;
            }
        </style>

    </HEAD>
    <body>
        <main class="box container">


            <div>
                <?php //exibição dos dados do usuario
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $idUsuario = $row['idUsuario'];
                        $nome = $row['nome'];
                        $cpf = $row['cpf'];
                        $genero = $row['genero'];
                        $email = $row['email'];
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
                        
                        echo '<h4>' . $nome . '</h4>'.'<p> '. $admissao .'</p>';
                        echo '<h5>' . $cargo . "  | ". $unidade .'</h5><br><br>';
                        echo '<div class="dadosUser">';
                        echo '<label for="id">Funcionário ID:</label><br>';
                        echo '<input type="text" name="id" id="id" value="'. $idUsuario.'" readonly><br>';
                        echo '<label for="cpf">CPF:</label><br>';
                        echo '<input type="text" name="cpf" id="cpf" value="'. $cpf.'" readonly><br>';
                        echo '<label for="genero">Genero:</label><br>';
                        echo '<input type="text" name="genero" id="genero" value="'. $genero.'" readonly><br>';
                        echo '<label for="email">E-mail:</label><br>';
                        echo '<input type="text" name="email" id="email" value="'. $email.'" readonly><br>';
                        echo '<label for="dataCad">Data e hora do cadastro:</label><br>';
                        echo '<input type="text" name="dataCad" id="dataCad" value="'. $dataCadastro.'" readonly><br>';
                        echo '<label for="status">Status do funcionário:</label><br>';
                        echo '<input type="text" name="status" id="status" value="'. $status.'" readonly><br>';
                        

                    }
                }else {
                    echo '<p colspan="2">Nenhum usuário encontrado</p>';
                }


                //exibição do enedereço do usuario
                
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
                        
                        echo '<h4>Endereço</h4><br>';
                        echo '<div class="dadosUser">';


                        echo '<label for="cep">CEP:</label><br>';
                        echo '<input type="text" name="cep" id="cep" value="'. $cep.'" readonly>';

                        echo '<label for="rua">Logradouro:</label><br>';
                        echo '<input type="text" name="rua" id="rua" value="'. $rua.'" readonly><br>';

                        echo '<label for="numero">Número:</label><br>';
                        echo '<input type="text" name="numero" id="numero" value="'. $numero.'" readonly><br>';

                        echo '<label for="bairro">Bairro:</label><br>';
                        echo '<input type="text" name="bairro" id="bairro" value="'. $bairro.'" readonly><br>';

                        echo '<label for="cidade">Cidade:</label><br>';
                        echo '<input type="text" name="cidade" id="cidade" value="'. $cidade.'" readonly><br>';

                        echo '<label for="municipio">Municipio:</label><br>';
                        echo '<input type="text" name="municipio" id="municipio" value="'. $municipio.'" readonly><br>';

                        echo '<label for="complemento">Complemento:</label><br>';
                        echo '<input type="text" name="complemento" id="complemento" value="'. $complemento.'" readonly><br>';

                        echo '<label for="complemento">Data e hora do cadastro:</label><br>';
                        echo '<input type="text" name="dataCad" id="dataCad" value="'. $dataCadastroEnd.'" readonly><br>';

                        echo '<label for="status">Status do endereço:</label><br>';
                        echo '<input type="text" name="status" id="status" value="'. $status.'" readonly><br>';


                    }
                }else {
                    echo '<p colspan="2">Nenhum endereço cadastrado</p>';
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