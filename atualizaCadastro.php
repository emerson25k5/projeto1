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
                    echo '<script>alert("Alterações gravadas com sucesso!");</script>';
                    header("Refresh:0.1; url=listausuarios.php");
                } else {
                    echo '<script>alert("Erro ao atualizar dados:");</script>' . $stmt->error;
                }
            } else {
                echo "Formulário não foi enviado corretamente";
            }
        }

    $mysqli->close();
}
}
?>
