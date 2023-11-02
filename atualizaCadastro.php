<?php

require "autenticaContent.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require("conecta.php");

    date_default_timezone_set('America/Sao_Paulo'); //obtem a data e hora
    $dataHoraAtual = new DateTime();

    if (isset($_POST["form_id"])) {
        $form_id = $_POST["form_id"];

        if ($form_id == 1) {

            try{

                if (isset($_POST["submit_form1"])) {


                    $idAlt = $_POST['id']; //declara o id para usar no SELECT que busca os dados sem alteração
                    
                    $mysqli->begin_transaction();

                    //busca o nome do usuário para gravar no log a versão antes da alteração
                    $sql = "SELECT * FROM funcionarios WHERE idFuncionario = $idAlt";
                    $result = $mysqli->query($sql);
                    if($result->num_rows > 0){
                        $row = $result->fetch_assoc();

                        //declara os dados que serão salvos no log
                        $idLogUsuarioResponsavel = $_SESSION['idUsuarioLogado'];
                        $nomeUsuarioResponsavel = $_SESSION['nomeCompleto'];
                        $tipoLog = "Alteração dados gerais funcionario";
                        $dataLogAlteracao = $dataHoraAtual->format('Y-m-d H:i:s');
                        $idAlt = $_POST['id'];
                        $cpfAlt = $row['cpf'];
                        $dataCadFuncionarioAlt = $row['dataCadFuncionario'];
                        $emailAlt = $row['email'];
                        $funcObservacoesAlt = $row['funcObservacoes'];
                        $generoAlt = $row['genero'];
                        $idFuncionarioAlt = $row['idFuncionario'];
                        $nascimentoAlt = $row['nascimento'];
                        $nomeAlt = $row['nome'];
                        $rgAlt = $row['rg'];
                        $statusAlt = $row['status'];
                        $telefoneAlt = $row['telefone'];
                    }

                    //inserção dos dados no log
                    $insertLog = $mysqli->prepare("INSERT INTO logalteracoes (idLogUsuarioResponsavel, tipoLog, dataLogAlteracao, nomeUsuarioResponsavel, cpfAlt, dataCadFuncionarioAlt, emailAlt, funcObservacoesAlt, generoAlt,
                    idFuncionarioAlt, nascimentoAlt, nomeAlt, rgAlt, statusAlt, telefoneAlt) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $insertLog->bind_param("isssssssissssss", $idLogUsuarioResponsavel, $tipoLog, $dataLogAlteracao, $nomeUsuarioResponsavel, $cpfAlt, $dataCadFuncionarioAlt, $emailAlt, $funcObservacoesAlt, $generoAlt, $idFuncionarioAlt,
                    $nascimentoAlt, $nomeAlt, $rgAlt, $statusAlt, $telefoneAlt);
        
                    if ($insertLog->execute()) {
                        $mysqli->commit();
                    }else{
                        echo "Falha no registro de LOG" . " Erro: " . $mysqli->error;
                        $mysqli->rollback();
                        $mysqli->close();
                        die('Erro na inserção do registro de alterações: ' . $mysqli->error);
                    }

                    if ($insertLog) {

                        $mysqli->begin_transaction();
                        // Processar os dados do formulário
                        $id = $_POST['id'];
                        $nome = $_POST['nome'];
                        $cpf = $_POST['cpf'];
                        $rg = $_POST['rg'];
                        $genero = $_POST['genero'];
                        $email = $_POST['email'];
                        $telefone = $_POST['telefone'];
                        $status = $_POST['status'];
                        $funcObservacoes = $_POST['funcObservacoes'];             

                        // Executar a atualização no banco de dados
                        $sql = "UPDATE funcionarios SET nome=?, cpf=?, rg=?, genero=?, email=?, telefone=?, status=?, funcObservacoes=? WHERE idFuncionario = $id";

                        $stmt = $mysqli->prepare($sql);
        
                        if ($stmt === false) {
                            die('Erro na preparação da consulta: ' . $mysqli->error);
                        }

                        $stmt->bind_param("ssssssis", $nome, $cpf, $rg, $genero, $email, $telefone, $status, $funcObservacoes);

                        if ($stmt->execute()) {
                            $mysqli->commit();
                            header("Location: listaFuncionarios.php");
                        } else {
                            $mysqli->rollback();
                            echo '<script>alert("Erro ao atualizar dados:");</script>' . $stmt->error;
                        }
                    } else {
                        echo "Registro log não realizado corretamente";
                    }
                }

            }catch (mysqli_sql_exception $e) {
                if ($e->getCode() == 1062) {//se o CPF retorna duplicidade
                    echo '<script>alert("O funcionário associado a este CPF já tem um cadastro. Verifique e tente novamente!");</script>';
                    echo "<script>setTimeout(function(){ window.location.href = 'editaFuncionario.php?id=$id'; }, 100);</script>";
                } else {//se não se o E-MAIL retorna duplicidade
                    echo '<script>alert("O funcionário associado a este e-mail já tem um cadastro. Verifique e tente novamente!");</script>';
                    echo "<script>setTimeout(function(){ window.location.href = 'editaFuncionario.php?id=$id'; }, 100);</script>";
                }
                $mysqli->rollback();
                $mysqli->close();
            }
        }

        if ($form_id == 2) {

            if (isset($_POST["submit_form2"])){

                $idAlt = $_POST['id']; //declara o id para usar no SELECT que busca os dados do endereço antes da alteração
                
                $mysqli->begin_transaction();

                //busca o nome do usuário para gravar no log a versão antes da alteração
                $sql = "SELECT * FROM funcionarios WHERE idFuncionario = $idAlt";
                $result = $mysqli->query($sql);
                if($result->num_rows > 0){
                    $row = $result->fetch_assoc();

                    //declara os dados que serão salvos no log
                    $idLogUsuarioResponsavel = $_SESSION['idUsuarioLogado'];
                    $nomeUsuarioResponsavel = $_SESSION['nomeCompleto'];
                    $tipoLog = "Alteração endereço funcionario";
                    $dataLogAlteracao = $dataHoraAtual->format('Y-m-d H:i:s');
                    $nomeAlt = $row['nome'];
                    $idAlt = $_POST['id'];//declara o id do fucionario alterado para inserir no LOG
                }

                //inserção dos dados na tabela de log
                $insertLog = $mysqli->prepare("INSERT INTO logalteracoes (idLogUsuarioResponsavel, nomeUsuarioResponsavel, tipoLog, dataLogAlteracao, idFuncionarioAlt, nomeAlt) VALUES (?, ?, ?, ?, ?, ?)");
                $insertLog->bind_param("isssis", $idLogUsuarioResponsavel, $nomeUsuarioResponsavel, $tipoLog, $dataLogAlteracao, $idAlt, $nomeAlt);
    
                if ($insertLog->execute()) {
                    $mysqli->commit();
                }else{
                    echo "Falha no registro de LOG" . " Erro: " . $mysqli->error;
                    $mysqli->rollback();
                    $mysqli->close();
                    die('Erro na inserção do registro de alterações: ' . $mysqli->error);
                }

                if ($insertLog) {

                    $mysqli->begin_transaction();

                    // Processar os dados do formulário
                    $id = $_POST['id'];
                    $cep = $_POST['cep'];
                    $rua = $_POST['rua'];
                    $numero = $_POST['numero'];
                    $bairro = $_POST['bairro'];
                    $cidade = $_POST['cidade'];
                    $municipio = $_POST['municipio'];
                    $complemento = $_POST['complemento'];
                    
                    // Executar a atualização no banco de dados
                    $sql = "UPDATE usedenderecos SET cep=?, rua=?, numero=?, bairro=?, cidade=?, municipio=?, complemento=? WHERE funcionarioID = $id";

                    $stmt = $mysqli->prepare($sql);
    
                    if ($stmt === false) {
                        die('Erro na preparação da consulta: ' . $mysqli->error);
                    }

                    $stmt->bind_param("sssssss", $cep, $rua, $numero, $bairro, $cidade, $municipio, $complemento);

                    if ($stmt->execute()) {
                        header("Refresh:0.1; url=listaFuncionarios.php");
                        $mysqli->commit();
                    } else {
                        $mysqli->rollback();
                        echo '<script>alert("Erro ao atualizar endereço:");</script>' . $stmt->error;
                    }
                } else {
                    echo "Registro log não realizado corretamente";
                }
            }
        }

        if ($form_id == 3) {

            if (isset($_POST["submit_form3"])) {

                $idAlt = $_POST['id']; //declara o id para usar no SELECT que busca os dados do endereço antes da alteração
                
                $mysqli->begin_transaction();

                //busca o nome do usuário para gravar no log a versão antes da alteração
                $sql = "SELECT * FROM funcionarios WHERE idFuncionario = $idAlt";
                $result = $mysqli->query($sql);
                if($result->num_rows > 0){
                    $row = $result->fetch_assoc();

                    //declara os dados que serão salvos no log
                    $idLogUsuarioResponsavel = $_SESSION['idUsuarioLogado'];
                    $nomeUsuarioResponsavel = $_SESSION['nomeCompleto'];
                    $tipoLog = "Alteração atribuições funcionario";
                    $dataLogAlteracao = $dataHoraAtual->format('Y-m-d H:i:s');
                    $nomeAlt = $row['nome'];
                    $idAlt = $_POST['id'];//declara o id do fucionario alterado para inserir no LOG
                }

                //inserção dos dados na tabela de log
                $insertLog = $mysqli->prepare("INSERT INTO logalteracoes (idLogUsuarioResponsavel, nomeUsuarioResponsavel, tipoLog, dataLogAlteracao, idFuncionarioAlt, nomeAlt) VALUES (?, ?, ?, ?, ?, ?)");
                $insertLog->bind_param("isssis", $idLogUsuarioResponsavel, $nomeUsuarioResponsavel, $tipoLog, $dataLogAlteracao, $idAlt, $nomeAlt);
    
                if ($insertLog->execute()) {
                    $mysqli->commit();
                }else{
                    echo "Falha no registro de LOG" . " Erro: " . $mysqli->error;
                    $mysqli->rollback();
                    $mysqli->close();
                    die('Erro na inserção do registro de alterações: ' . $mysqli->error);
                }

                if ($insertLog) {

                    $mysqli->begin_transaction();

                    // Processar os dados do formulário
                    $id = $_POST['id'];
                    $novoCargo = $_POST['cargo_escolhido'];
                    $novaUnidade = $_POST['unidade_escolhida'];
                    
                    // Executar a atualização no cargo
                    $sql = "UPDATE usedcargos SET cargoID=? WHERE funcionarioID = $id";
                    $sql2 = "UPDATE usedunidades SET unidadeID=? WHERE funcionarioID = $id";

                    $stmt = $mysqli->prepare($sql);
                    $stmt2 = $mysqli->prepare($sql2);
    
                    if ($stmt && $stmt2 === false) {
                        die('Erro na preparação da consulta: ' . $mysqli->error);
                    }

                    $stmt->bind_param("i", $novoCargo);
                    $stmt2->bind_param("i", $novaUnidade); 


                    if ($stmt->execute()) {
                        if ($stmt2->execute()) {
                            $mysqli->commit();
                            header("Refresh:0.1; url=listaFuncionarios.php");
                        }
                    } else {
                        $mysqli->rollback();
                        echo '<script>alert("Erro ao atualizar atribuições:");</script>' . $stmt->error;
                    }
                } else {
                    echo "Registro log não realizado corretamente";
                }
            }
        }       

        if ($form_id == 4) {

            if (isset($_POST["submit_form4"])) {

                    $idAlt = $_POST['id']; //declara o id para usar no SELECT que busca os dados do endereço antes da alteração
                    
                    $mysqli->begin_transaction();

                    //busca o nome do usuário para gravar no log a versão antes da alteração
                    $sql = "SELECT * FROM funcionarios WHERE idFuncionario = $idAlt";
                    $result = $mysqli->query($sql);
                    if($result->num_rows > 0){
                        $row = $result->fetch_assoc();

                        //declara os dados que serão salvos no log
                        $idLogUsuarioResponsavel = $_SESSION['idUsuarioLogado'];
                        $nomeUsuarioResponsavel = $_SESSION['nomeCompleto'];
                        $tipoLog = "Alteração tamanho uniforme funcionario";
                        $dataLogAlteracao = $dataHoraAtual->format('Y-m-d H:i:s');
                        $nomeAlt = $row['nome'];
                        $idAlt = $_POST['id'];//declara o id do fucionario alterado para inserir no LOG
                    }

                    //inserção dos dados na tabela de log
                    $insertLog = $mysqli->prepare("INSERT INTO logalteracoes (idLogUsuarioResponsavel, nomeUsuarioResponsavel, tipoLog, dataLogAlteracao, idFuncionarioAlt, nomeAlt) VALUES (?, ?, ?, ?, ?, ?)");
                    $insertLog->bind_param("isssis", $idLogUsuarioResponsavel, $nomeUsuarioResponsavel, $tipoLog, $dataLogAlteracao, $idAlt, $nomeAlt);
        
                    if ($insertLog->execute()) {
                        $mysqli->commit();
                    }else{
                        echo "Falha no registro de LOG" . " Erro: " . $mysqli->error;
                        $mysqli->rollback();
                        $mysqli->close();
                        die('Erro na inserção do registro de alterações: ' . $mysqli->error);
                    }

                if ($insertLog) {

                        $mysqli->begin_transaction();

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
}
?>
