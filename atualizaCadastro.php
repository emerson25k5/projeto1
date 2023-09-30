<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    include("conecta.php");

    if (isset($_POST["form_id"])) {
        $form_id = $_POST["form_id"];

        if ($form_id == 1) {

            $mysqli->begin_transaction();

            if (isset($_POST["submit_form1"])) {
                // Processar os dados do formulário
                $id = $_POST['id'];
                $nome = $_POST['nome'];
                $cpf = $_POST['cpf'];
                $rg = $_POST['rg'];
                $email = $_POST['email'];
                $telefone = $_POST['telefone'];
                $status = $_POST['status'];
                $funcObservacoes = $_POST['funcObservacoes'];             

                // Executar a atualização no banco de dados
                $sql = "UPDATE funcionarios SET nome=?, cpf=?, rg=?, email=?, telefone=?, status=?, funcObservacoes=? WHERE idFuncionario = $id";

                $stmt = $mysqli->prepare($sql);
 
                if ($stmt === false) {
                    die('Erro na preparação da consulta: ' . $mysqli->error);
                }

                $stmt->bind_param("sssssis", $nome, $cpf, $rg, $email, $telefone, $status, $funcObservacoes);

                if ($stmt->execute()) {
                    $mysqli->commit();
                    echo '<script>alert("Alterações gravadas com sucesso!");</script>';
                    header("Refresh:0.1; url=listaFuncionarios.php");
                } else {
                    $mysqli->rollback();
                    echo '<script>alert("Erro ao atualizar dados:");</script>' . $stmt->error;
                }
            } else {
                echo "Formulário não foi enviado corretamente";
            }
        }

        if ($form_id == 2) {

            $mysqli->begin_transaction();

            if (isset($_POST["submit_form2"])) {
                // Processar os dados do formulário
                $id = $_POST['id'];
                $cep = $_POST['cep'];
                $rua = $_POST['rua'];
                $numero = $_POST['numero'];
                $bairro = $_POST['bairro'];
                $cidade = $_POST['cidade'];
                $municipio = $_POST['municipio'];
                $complemento = $_POST['complemento'];
                $dataCadastroEnd = $_POST['dataCadastroEnd'];
                
                // Executar a atualização no banco de dados
                $sql = "UPDATE usedenderecos SET cep=?, rua=?, numero=?, bairro=?, cidade=?, municipio=?, complemento=?, dataCadastro=? WHERE funcionarioID = $id";

                $stmt = $mysqli->prepare($sql);
 
                if ($stmt === false) {
                    die('Erro na preparação da consulta: ' . $mysqli->error);
                }

                $stmt->bind_param("ssssssss", $cep, $rua, $numero, $bairro, $cidade, $municipio, $complemento, $dataCadastroEnd);

                if ($stmt->execute()) {
                    echo '<script>alert("Alterações gravadas com sucesso!");</script>';
                    header("Refresh:0.1; url=listaFuncionarios.php");
                    $mysqli->commit();
                } else {
                    $mysqli->rollback();
                    echo '<script>alert("Erro ao atualizar endereço:");</script>' . $stmt->error;
                }
            } else {
                echo "Formulário não foi enviado corretamente";
            }
        }

        if ($form_id == 3) {

            $mysqli->begin_transaction();

            if (isset($_POST["submit_form3"])) {


                // Processar os dados do formulário
                $id = $_POST['id'];
                $novoCargo = $_POST['cargo_escolhido'];
                $novaUnidade = $_POST['unidade_escolhida'];
                $novaDataAdmissao = $_POST['dataAdmissao'];
                $novaDataUltFerias = $_POST['dataUltFerias'];
                
                // Executar a atualização no cargo
                $sql = "UPDATE usedcargos SET cargoID=? WHERE funcionarioID = $id";
                $sql2 = "UPDATE usedunidades SET unidadeID=? WHERE funcionarioID = $id";
                $sql3 = "UPDATE admissao SET dataAdmissao=? WHERE funcionarioID = $id";
                $sql4 = "UPDATE controleferias SET dataUltFerias=? WHERE funcionarioID = $id";

                $stmt = $mysqli->prepare($sql);
                $stmt2 = $mysqli->prepare($sql2);
                $stmt3 = $mysqli->prepare($sql3);
                $stmt4 = $mysqli->prepare($sql4);
 
                if ($stmt === false) {
                    die('Erro na preparação da consulta: ' . $mysqli->error);
                }

                $stmt->bind_param("i", $novoCargo);
                $stmt2->bind_param("i", $novaUnidade);
                $stmt3->bind_param("s", $novaDataAdmissao);
                $stmt4->bind_param("s", $novaDataUltFerias);


                if ($stmt->execute()) {
                    if ($stmt2->execute()) {
                        if ($stmt3->execute()) {
                            if ($stmt4->execute()) {
                    $mysqli->commit();
                    echo '<script>alert("Alterações gravadas com sucesso!");</script>';
                    header("Refresh:0.1; url=listaFuncionarios.php");
                            }
                        }
                    }
                } else {
                    $mysqli->rollback();
                    echo '<script>alert("Erro ao atualizar atribuições:");</script>' . $stmt->error;
                }
            } else {
                echo "Formulário não foi enviado corretamente";
            }
        }

        if ($form_id == 4) {

            $mysqli->begin_transaction();

            if (isset($_POST["submit_form4"])) {

                // Processar os dados do formulário
                $id = $_POST['id'];
                $novoTamTronco = $_POST['tam_tronco'];
                $novoTamPerna = $_POST['tam_perna'];
                $novoTamCalcado = $_POST['tam_calcado'];
                
                // Executar a atualização no cargo
                $sql = "UPDATE useduniformes SET tamTronco=?, tamPerna=?, tamCalcado=? WHERE funcionarioID = $id";


                $stmt = $mysqli->prepare($sql);
 
                if ($stmt === false) {
                    die('Erro na preparação da consulta: ' . $mysqli->error);
                }

                $stmt->bind_param("ssi", $novoTamTronco, $novoTamPerna, $novoTamCalcado);



                if ($stmt->execute()) {
                    $mysqli->commit();
                    echo '<script>alert("Alterações gravadas com sucesso!");</script>';
                    header("Refresh:0.1; url=listaFuncionarios.php");

                } else {
                    $mysqli->rollback();
                    echo '<script>alert("Erro ao atualizar atribuições:");</script>' . $stmt->error;
                }
            } else {
                echo "Formulário não foi enviado corretamente";
            }
        }

    $mysqli->close();
}


}
?>
