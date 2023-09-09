<?php

include("conecta.php");

$_statusCadCargo = "";

$_POST;
if($_POST){

$nomeCargo = $_POST["nomeCargo"];

$cadastrarCargo = $mysqli->query("INSERT INTO cargos (nomeCargo) VALUES ('$nomeCargo')");

$_statusCadCargo = ($cadastrarCargo) ? "Cadastro realizado com sucesso!" . $mysqli->error : "Falha ao realizar o cadastro!" ." Código do erro:  " . $mysqli->error;
}

$sql = "SELECT * FROM cargos ORDER BY nomeCargo";
$result = $mysqli->query($sql);

?>

<!DOCTYPE html>
<HTML lang="pt-BR">
    <HEAD>
        <TITLE>EBDS | Cadastro Cargo</TITLE>

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


            <div class="container center">

                <h4>CADASTRO DE CARGOS</h4>

                <BR>

                <form method="post" action="">

                    <div class="input-field col s12 left">
                    <i class="material-icons prefix">maps_home_work</i>
                    <input type="text" name="nomeCargo" id="nomeCargo" maxlength="25" class="validate" required autofocus>
                    <label for="nomeCargo">Nome do cargo</label>
                    </div>
                    
                    <br><br><br><br>

                    <input type="submit" name="entrar" value="Adicionar" class="btn">


                    <br><br>

                    <div class="statusCadCargo center" id="statusCadCargo"> <!-- div para exibir se o cadastro foi realizado ou não e então exibir o erro -->
                        <?php echo $_statusCadCargo;?>
                    </div>

                </form>
                
            </div>

            <BR>

            <div class="mostraCargo col s12">
                <table>
                    <thead>
                        <tr>
                            <th>Nome do Cargo</th>
                            <th>Data de cadastro</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                        <tbody>
                            <?php
                            // Verifique se há registros e gere as linhas da tabela
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $nome = $row['nomeCargo'];
                                    $dataCadastro = $row['dataCadastro'];
                                    $status = $row['status'];
                                    echo '<tr>';
                                    echo '<td>' . $nome . '</td>';
                                    echo '<td>' . $dataCadastro . '</td>';
                                    if($status == 1){
                                        echo '<td>Ativo</td>';
                                    }else {
                                        echo '<td>Inativo</td>';
                                    };
                                    echo '</tr>';
                                }
                            } else {
                                echo '<tr><td colspan="2">Nenhum cargo encontrado</td></tr>';
                            }
                            ?>
                        </tbody>
                </table>
            </div>

            <br><br><br>

        </main>

        <footer class="page-footer">
            <div>
                <p class="center">EDBS all righst reserved</p>
                <br>
            
        </footer>
    </body>
</HTML>