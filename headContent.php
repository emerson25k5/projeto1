        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="SistemaEBDS">
        <link rel="icon" href="imagens/brasao_patrol.png" type="image/png">
        <meta name="keywords" content="HTML, CSS, JavaScript">
        <link rel="stylesheet" type="text/css" href="estilo.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons"rel="stylesheet">


<header>
    
    <nav class="nav">
        <div class="nav-wrapper container">
        <a href="index.php" class="brand-logo center"><img class="logo" src="imagens/brasao_patrol.png" alt="patrol_logo"></a>
        <ul id="sidenav">
            <li href="#" data-target="slide-out" class="sidenav-trigger"><i class="material-icons">menu</i></li>
            <?php 
            if ($_SESSION["authenticated"] = true) {
                echo '<li class="right"><a class="dropdown-trigger" href="#" data-target="dropdown1">Olá, '.$_SESSION['nomeUsuario'].'</a></li>';
            }
                ?>
        </ul>
        </div>

        <ul id='dropdown1' class='dropdown-content'>
            <li><a href="atualizaSenha.php">Alterar senha</a></li>
            <li><a href="encerra_sessao.php">Sair</a></li>
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

        //JS para inicializar a sidenav
        document.addEventListener('DOMContentLoaded', function() {
                var elems = document.querySelectorAll('.sidenav');
                var instances = M.Sidenav.init(elems);
            });
    </script>

    <ul id="slide-out" class="right sidenav">
        <li><a href="index.php" class="brand-logo center"><img src="imagens/patrol_texto_bg.png" alt="patrol_logo"></a></li>
        <div class="divider"></div>
        <li><a href="cadastrocargo.php">Cadastro de cargos</a></li>
        <li><a href="cadastrounidade.php">Cadastro de unidades</a></li>
        <li><a href="associaPerfilAcesso.php">Associar perfis de acesso</a></li>
        <li><a href="cadastroFuncionario.php">Cadastro de funcionários</a></li>
        <li><a href="listaFuncionarios.php">Funcionários</a></li>
    </ul>
</header>