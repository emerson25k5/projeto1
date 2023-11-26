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
        <?php //limita o acesso ao menu por um usuário comum
            if($_SESSION['nivelAcesso'] == 2 ) { ?>
            <li href="#" data-target="slide-out" class="sidenav-trigger hide-on-med-and-down"><i class="material-icons">menu</i></li>
            <li><a href="home.php" class="menu hide-on-large-only"><i class="material-icons">grid_view</i></a></li>
            <?php } ?>
            <li class="botao-acc dropdown-trigger right" href="#" data-target="dropdown1">Olá, <?php echo $_SESSION['nomeUsuario'];?><i class="material-icons">arrow_drop_down</i></li>
        </ul>
        </div>
    </nav>
    <nav class="finin">
    </nav>
        <ul id='dropdown1' class='menu-perfil dropdown-content'>
            <li class="lista-menu-suspenso-opt"><a class="a-menu-suspenso-opt" href="userInfo.php?id=<?php echo $_SESSION['idFuncionarioLogado'];?>"><i class="material-icons prefix">badge</i>Meus dados</a></li>
            <li class="lista-menu-suspenso-opt"><a class="a-menu-suspenso-opt" href="atualizaSenha.php"><i class="material-icons prefix">key</i>Alterar senha</a></li>
            <li class="lista-menu-suspenso-opt"><a class="a-menu-suspenso-opt" href="encerra_sessao.php"><i class="material-icons prefix">logout</i>Sair</a></li>
        </ul>


        <?php
        require "conecta.php";

        $itensMenuFloat = "SELECT * FROM itensmenu WHERE status = 1 ORDER BY idItem ASC";
        $resultadoFloat = $mysqli->query($itensMenuFloat);

        //desativa o float no menu
        
        if(isset($inativaFloat)){
            $inativaFloat = "hide";
        }else{
            $inativaFloat = "hide-on-large-only";
        }
        ?>

        
<?php
if($_SESSION['nivelAcesso'] == 2 ) { ?>
        <div class="fixed-action-btn <?php echo $inativaFloat;?>">
            <a class="btn-floating btn-large black">
                <i class="large material-icons">menu</i>
            </a>
            <ul class="haha">
            <?php
            if ($resultadoFloat->num_rows > 0) {
                while ($row = $resultadoFloat->fetch_assoc()) {   
            ?>
                <li ><a href="<?php echo $row['linkItem'];?>" style="background-color:<?php echo $row['corItem'];?>" class="btn-floating z-depth-5"><i style="color: <?php echo $row['corIcone'];?>" class="material-icons"><?php echo $row['iconeItem'];?></i><?php echo $row['nomeItem']; ?></a></li>
            <?php } ?>
        <?php } ?>
        </ul>
        </div>
<?php } ?>



    <ul id="slide-out" class="side-mobile sidenav">
        <br>
        <div class="center row"><img src="imagens/patrol_texto_bg.png" class="patrolSideNavImage center"></div>
        <ul>
            <?php
            if($_SESSION['nivelAcesso'] == 2 ){
                echo '
                        <li><a href="home.php" class="menu"><i class="material-icons">grid_view</i>MENU</a></li><br>
                        <li><a class="subheader">Gestão de funcionários</a></li>
                        <li><a href="listaFuncionarios.php" class="menu"><i class="material-icons">badge</i>Funcionários</a></li>
                        <li><a href="cadastroFuncionario.php" class="menu"><i class="material-icons">person_add</i>Cadastro funcionários</a></li><br>
                        </ul>
                        <ul>
                        <li><a class="subheader">Controle interno</a></li>
                        <li><a href="listaFuncionariosUniformes.php" class="menu"><i class="material-icons">checkroom</i>Entrega uniformes</a></li>
                        <li><a href="listaFuncionariosFerias.php" class="menu"><i class="material-icons">date_range</i>Férias</a></li>
                        <li><a href="controleFolgaTrabalhada.php" class="menu"><i class="material-icons">event</i>Trabalho externo</a></li><br>
                        </ul>
                        <ul>
                        <li><a class="subheader">Cadastros</a></li>
                        <li><a href="cadastroFuncionario.php" class="menu"><i class="material-icons">person_add</i>Cadastro funcionários</a></li>
                        <li><a href="cadastroCargo.php" class="menu"><i class="material-icons">post_add</i>Cargos</a></li>
                        <li><a href="cadastroUnidade.php" class="menu"><i class="material-icons">add_location_alt</i>Unidades</a></li><br>
                        </ul>
                        <ul>
                        <li><a class="subheader">Segurança</a></li>
                        <li><a href="associaPerfilAcesso.php" class="menu"><i class="material-icons">admin_panel_settings</i>Permissões acesso</a></li>
                        <li><a href="registroAcessos.php" class="menu"><i class="material-icons">schedulety</i>Histórico de acessos</a></li>
                        <li><a href="atualizaSenha.php" class="menu"><i class="material-icons">key</i>Alterar minha senha</a></li>
                        <br><br><br><br><br>
                        <p class="center">SWUPE - EBDS</p>
                        </ul>';
            }?>

    </ul>

    <script>
        
    //js da lista suspensa do float action
    document.addEventListener('DOMContentLoaded', function() {
        var elems = document.querySelectorAll('.fixed-action-btn');
        var instances = M.FloatingActionButton.init(elems, {
            direction: 'top',
            hoverEnabled: false

        });
    });

    //JS do Menu suspenso das opções do usuário logado (trocar senha, sair)
    document.addEventListener('DOMContentLoaded', function() {
        var dropdowns = document.querySelectorAll('.dropdown-trigger');   
        var options = {
            coverTrigger: false,
            openOnClick: true,
            constrainWidth: true
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