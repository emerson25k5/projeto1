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
        <a href="#" class="brand-logo center">EBDS</a>
        <ul id="sidenav">
            <li href="#" data-target="slide-out" class="sidenav-trigger"><i class="material-icons">menu</i></li>
        </ul>
        </div>
    </nav>

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

    <ul id="slide-out" class="sidenav">
        <li class="center">EBDS Corporation</li>
        <div class="divider"></div>
        <li><a href="listausuarios.php">Funcionários</a></li>
        <li><a href="cadastrocargo.php">Cadastro de cargos</a></li>
        <li><a href="cadastrounidade.php">Cadastro de unidades</a></li>
        <li><a href="index.php">Cadastro de funcionários</a></li>
    </ul>
</header>