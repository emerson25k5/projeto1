<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    include("conecta.php");

    if (isset($_POST["form_id"])) {
        $form_id = $_POST["form_id"];

        if ($form_id == 1) {
            if (isset($_POST["submit_form1"])) {
                // Processar os dados do formulário
                $id = $_POST['id'];
                $nome = $_POST['nome'];
                $cpf = $_POST['cpf'];
                $rg = $_POST['rg'];
                $email = $_POST['email'];
                $telefone = $_POST['telefone'];

                // Executar a atualização no banco de dados
                $sql = "UPDATE usuarios SET nome=?, cpf=?, rg=?, email=?, telefone=? WHERE idUsuario = $id";

                $stmt = $mysqli->prepare($sql);

                if ($stmt === false) {
                    die('Erro na preparação da consulta: ' . $mysqli->error);
                }

                $stmt->bind_param("sssss", $nome, $cpf, $rg, $email, $telefone);

                if ($stmt->execute()) {
                    echo "Alterações gravadas com sucesso!";
                } else {
                    echo "Erro ao atualizar: " . $stmt->error;
                }
            } else {
                echo "Formulário não foi enviado corretamente";
            }
        } else {
            echo "Formulário com ID desconhecido";
        }
    } else {
        echo "ID de formulário não foi especificado";
    }

    $mysqli->close();
}
?>
