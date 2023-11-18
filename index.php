<?php
use Detection\MobileDetect;
if (isset($_SESSION["authenticated"]) && $_SESSION["authenticated"] == true) {
    header("Location: listaFuncionarios.php");
    exit;
}

if (isset($_POST['entrar'])) {

    require("conecta.php");

    date_default_timezone_set('America/Sao_Paulo');
    $dataHoraAtual = new DateTime();

    $login = $_POST['login'];
    $senha = $_POST['senha'];

    $login = $mysqli->real_escape_string($login); //trata a variável evitando ataques sql injection
    $senha = $mysqli->real_escape_string($senha); //trata a variável evitando ataques sql injection


    $sql = "SELECT usuarios.login, usuarios.senha, usuarios.idUsuario, usuarios.funcionarioID, usedperfilacesso.nivelPerfilID, funcionarios.nome
            FROM funcionarios
            LEFT JOIN usuarios ON funcionarios.idFuncionario = usuarios.funcionarioID
            LEFT JOIN usedperfilacesso ON usuarios.idUsuario = usedperfilacesso.usuarioID
            WHERE email = '$login' AND funcionarios.status = 1";

    $result = $mysqli->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $senhaArmazenada = $row['senha'];
        $nome = $row['nome'];
        $idSessao = $row['idUsuario'];
        $idFunc = $row['funcionarioID'];
        $nivelAcesso = $row['nivelPerfilID'];

        $sep = explode(" ", $nome);
        $pnome = $sep[0];

        if (password_verify($senha, $senhaArmazenada)) {
            if (session_status() == PHP_SESSION_NONE) {
            session_start();
            }
            $_SESSION["authenticated"] = true;
            $_SESSION['login'] = $login;
            $_SESSION['nomeUsuario'] = $pnome;
            $_SESSION['nomeCompleto'] = $nome;
            $_SESSION['nivelAcesso'] = $nivelAcesso;
            $_SESSION['idFuncionarioLogado'] = $idFunc;
            $_SESSION['idUsuarioLogado'] = $idSessao;

            if($nivelAcesso == 2){

                $tipoAcesso = "Acesso administrador";

                require_once 'Mobile-Detect/Mobile_Detect.php';
                $detect = new Mobile_Detect;

                if ($detect->isMobile()) {
                    $dispositivo = "Smartphone";
                } elseif ($detect->isTablet()) {
                    $dispositivo = "Tablet";
                } else {
                    $dispositivo = "Desktop";
                }

                $ip = $_SERVER['REMOTE_ADDR'];
                $browser = $_SERVER['HTTP_USER_AGENT'];
                $dataTentativa = $dataHoraAtual->format('Y-m-d H:i:s');
                $stmt = $mysqli->prepare("INSERT INTO historicoacesso (funcionarioID, tipoAcesso, dataCadastro, enderecoIp, browser, dispositivo) VALUES (?, ?, ?, ?, ?, ?)"); //insert no histórico de acessos

                $stmt->bind_param("isssss", $idFunc, $tipoAcesso, $dataTentativa, $ip, $browser, $dispositivo);

                if ($stmt->execute()) {
                header("Location: listaFuncionarios.php");
                }else{
                    echo "<script>alert('Erro ao inserir acesso no banco de dados. Fale com o suporte.');</script>";
                    echo "<script>setTimeout(function(){ window.location.href = 'index.php'; }, 5);</script>";
                }
            }else{

                $tipoAcesso = "Acesso usuário comum";

                require_once 'Mobile-Detect/Mobile_Detect.php';
                $detect = new Mobile_Detect;

                if ($detect->isMobile()) {
                    $dispositivo = "Smartphone";
                } elseif ($detect->isTablet()) {
                    $dispositivo = "Tablet";
                } else {
                    $dispositivo = "Desktop";
                }

                $ip = $_SERVER['REMOTE_ADDR'];
                $browser = $_SERVER['HTTP_USER_AGENT'];
                $dataTentativa = $dataHoraAtual->format('Y-m-d H:i:s');
                $stmt = $mysqli->prepare("INSERT INTO historicoacesso (funcionarioID, tipoAcesso, dataCadastro, enderecoIp, browser, dispositivo) VALUES (?, ?, ?, ?, ?, ?)"); //insert no histórico de acessos

                $stmt->bind_param("isssss", $idFunc, $tipoAcesso, $dataTentativa, $ip, $browser, $dispositivo);

                if ($stmt->execute()) {
                    header("Location: userInfo.php?id=$idFunc");
                }else{
                    echo "<script>alert('Erro ao inserir acesso no banco de dados. Fale com o suporte.');</script>";
                    echo "<script>setTimeout(function(){ window.location.href = 'index.php'; }, 5);</script>";
                }
            }

        } else {                   
            $tipoAcesso = "Senha incorreta";

            require_once 'Mobile-Detect/Mobile_Detect.php';
            $detect = new Mobile_Detect;

            if ($detect->isMobile()) {
                $dispositivo = "Smartphone";
            } elseif ($detect->isTablet()) {
                $dispositivo = "Tablet";
            } else {
                    $dispositivo = "Desktop";
            }

            $ip = $_SERVER['REMOTE_ADDR'];
            $browser = $_SERVER['HTTP_USER_AGENT'];
            $dataTentativa = $dataHoraAtual->format('Y-m-d H:i:s');
            $stmt = $mysqli->prepare("INSERT INTO historicoacesso (funcionarioID, tipoAcesso, dataCadastro, enderecoIp, browser, dispositivo) VALUES (?, ?, ?, ?, ?, ?)"); //insert no histórico de acessos

            $stmt->bind_param("isssss", $idFunc, $tipoAcesso, $dataTentativa, $ip, $browser, $dispositivo);

            if ($stmt->execute()) {
                echo "<script>alert('Senha incorreta!');</script>";
                echo "<script>setTimeout(function(){ window.location.href = 'index.php'; }, 5);</script>";
            } else {
                echo "Erro ao inserir tipo acesso no banco de dados.";
            }
            

        }
    }else {
        echo "<script>alert('Usuário não existe ou está inativo, fale com um administrador!');</script>";
        echo "<script>setTimeout(function(){ window.location.href = 'index.php'; }, 5);</script>";
    }

    $stmt->close();

}

?>

<!DOCTYPE html>
<HTML lang="pt-BR">
    <HEAD>
        <TITLE>PATROL | LOGIN </TITLE>

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
        <a href="#" class="brand-logo center"><img class="logo" src="imagens/brasao_patrol.png" alt="patrol_logo"></a>
        <ul id="sidenav">
        </ul>
        </div>
    </nav>

</header>

    </HEAD>
    <body>

    <BR>

    
    
    <BR><BR>

    <h4 class="center container" style="font-weight:bold;">Acesso | Patrol</h5>

        <BR><BR>

        <main class="row col s12 container center">

        <form action="" method="post" class="form">

        <div class="todo-login container">
            

            <BR>

                <div class="login input-field col s12 center">
                    <i class="material-icons prefix">email</i>
                    <input type="email" name="login" id="login" maxlength="50" class="input-login validate" required>
                    <label for="login">E-mail</label>
                </div>

                <div class="login input-field col s12 center">
                    <i class="material-icons prefix">pin</i>
                    <input type="password" name="senha" id="senha" maxlength="50" class="input-login validate" required>
                    <label for="senha">Senha</label>
                    <a href="#" class="recupera-senha right">Esqueceu a senha?</a>
                </div>

                <div>
                    <input type="submit" name="entrar" value="Acessar" class="submitLogin">
                </div>


                <BR><BR>



        </div>

        </form>
        
        <BR><BR>
        </main>

        <script>
$(document).ready(function() {
  $('#email').on('focus', function() {
    $('#emailIcon').css('color', 'red'); // Defina a cor desejada aqui
  });

  $('#email').on('blur', function() {
    $('#emailIcon').css('color', ''); // Retorna à cor padrão (removendo a cor personalizada)
  });
});
</script>

        <?php include("footerContent.php");?> <!--adiciona o conteúdo do rodapé de modo modular usando o INCLUDE em PHP-->

    </body>
</HTML>