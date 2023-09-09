<?php

include("conecta.php");

$sql = "SELECT * FROM usuarios ORDER BY nome";
$result = $mysqli->query($sql);

?>

<!DOCTYPE html>
<HTML lang="pt-BR">
    <HEAD>
        <TITLE>EBDS | Usuários </TITLE>

        <meta charset="UTF-8">
        <meta name="description" content="SistemaEBDS">
        <link rel="icon" type="image/png" href="gravata.png">
        <meta name="keywords" content="HTML, CSS, JavaScript">
        <link rel="stylesheet" type="text/css" href="estilo.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons"rel="stylesheet">


        <header>
        <nav class="nav">
                <div class="nav-wrapper container">
                <a href="#" class="brand-logo">EBDS</a>
                <ul id="sidenav" class="right">
                    <li><a class="dropdown-trigger" href="#!" data-target="dropdown1">Menu<i class="material-icons right">arrow_drop_down</i></a></li>
                </ul>
                </div>
            </nav>

            <ul id="dropdown1" class="dropdown-content">
                <li><a href="cadastrocargo.php">Cargos</a></li>
                <li><a href="cadastrounidade.php">Unidades</a></li>
                <li><a href="listausuarios.php">Funcionários</a></li>
                <li><a href="index.php">Cadastrar funcionários</a></li>
            </ul>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var dropdowns = document.querySelectorAll('.dropdown-trigger');   //JS do Menu suspenso das cetegorias
                    var options = {
                        coverTrigger: false,
                        openOnClick: true,
                        outDuration: 100
                    };
                    M.Dropdown.init(dropdowns, options);
                  });
            </script>

        </header>

    </HEAD>
    <body>
        <main class="box container">

            <div>
                <h4>Funcionários:</h4>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $nome = $row['nome'];
                        $email = $row['email'];
                        $cpf = $row['cpf'];
                        $cargo = $row['cargoId'];
                        $unidade = $row['unidadeId'];
                        $nivelUsuario = $row['nivelUsuarioId'];
                        $tamUniTronco = $row['tam_uniforme_tronco'];
                        $tamUniPerna = $row['tam_uniforme_perna'];
                        $tamUniCalcado = $row['tam_uniforme_calcado'];
                        echo '<fieldset style="border-radius:5px; width:35%;">';
                        echo '<p style="font-weight: bold;">Dados pessoais:</p>';
                        echo '<p>Nome: ' . $nome . '</p>';
                        echo '<p>E-mail: ' . $email . '</p>';
                        echo '<p>CPF: ' . $cpf . '</p>';
                        echo '<tr>';
                        echo '<p style="font-weight: bold;">Atribuições:</p>';
                        echo '<p>Cargo: ' . $cargo . '</p>';
                        echo '<p>Unidade: ' . $unidade . '</p>';
                        echo '<p>Nível de usuario atribuído ao cargo: ' . $nivelUsuario . '</p>';
                        echo '<p style="font-weight: bold;">Uniforme:</p>';
                        echo '<p>Tronco: ' . $tamUniTronco . '</p>';
                        echo '<p>Calça: ' . $tamUniPerna . '</p>';
                        echo '<p>Calçado: ' . $tamUniCalcado . '</p>';
                        echo '</fieldset><br>';
                    }
                }else {
                    echo '<p colspan="2">Nenhum usuário encontrado</p>';
                }
                ?>

        </main>

        <footer class="page-footer">
            <div>
                <p class="center">EDBS all righst reserved</p>
                <br>
            
        </footer>
    </body>
</HTML>