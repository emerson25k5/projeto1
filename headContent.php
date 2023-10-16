        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Patrol-Sistem_byEBDS">
        <link rel="icon" href="imagens/brasao_patrol.png" type="image/png">
        <meta name="keywords" content="HTML, CSS, JavaScript">
        <link rel="stylesheet" type="text/css" href="estilo.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <link type="text/css" rel="stylesheet" href="materialize/css/materialize.min.css"  media="screen,projection"/>
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">


<header>
    
    <nav class="nav">
        <div class="nav-wrapper container">
        <a href="#" class="brand-logo center"><img class="logo" src="imagens/brasao_patrol.png" alt="patrol_logo"></a>
        <ul id="sidenav">
            <li href="#" data-target="slide-out" class="sidenav-trigger"><i class="material-icons">menu</i></li>
            <li class="dropdown-trigger right" href="#" data-target="dropdown1">Olá, <?php echo $_SESSION['nomeUsuario'];?></li>
        </ul>
        </div>
    </nav>
    <nav class="finin">
    </nav>
        <ul id='dropdown1' class='menu-perfil dropdown-content'>
        <li><a href="userInfo.php?id=<?php echo $_SESSION['idFuncionarioLogado'];?>"><i class="material-icons prefix">badge</i>Meus dados</a></li>
            <li><a href="atualizaSenha.php"><i class="material-icons prefix">key</i>Alterar senha</a></li>
            <li><a href="encerra_sessao.php"><i class="material-icons prefix">logout</i>Sair</a></li>
        </ul>



    <ul id="slide-out" class="right sidenav">

<li>
                <img src="imagens/patrol_texto_bg.png" class="patrolSideNavImage">

                <?php
    if($_SESSION['nivelAcesso'] == 2 ){
        echo '
                <li><a href="listaFuncionarios.php" class="menu"><i class="material-icons">badge</i>Funcionários</a></li>
                <li><a href="cadastroFuncionario.php" class="menu"><i class="material-icons">person_add</i>Cadastro de funcionários</a></li>
                <li><a href="listaFuncionariosUniformes.php" class="menu"><i class="material-icons">checkroom</i>Controle de uniformes</a></li>
                <li><a href="listaFuncionariosFerias.php" class="menu"><i class="material-icons">flight</i>Controle de férias</a></li>
                <li><a href="cadastrocargo.php" class="menu"><i class="material-icons">post_add</i>Cadastro de cargos</a></li>
                <li><a href="cadastrounidade.php" class="menu"><i class="material-icons">add_location_alt</i>Cadastro de unidades</a></li>
                <li><a href="associaPerfilAcesso.php" class="menu"><i class="material-icons">admin_panel_settings</i>Associar perfis de acesso</a></li>';               
    }?>

    </ul>

    <script>

    //JS do Menu suspenso das opções do usuário logado (trocar senha, sair)
    document.addEventListener('DOMContentLoaded', function() {
        var dropdowns = document.querySelectorAll('.dropdown-trigger');   
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

</header>