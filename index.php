<?php
session_start();
if (isset($_SESSION["authenticated"]) && $_SESSION["authenticated"] == true) {
    header("Location: listaFuncionarios.php");
    exit;
}

if (isset($_POST['entrar'])) {

    require("conecta.php");

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
            session_start();
            $_SESSION["authenticated"] = true;
            $_SESSION['login'] = $login;
            $_SESSION['nomeUsuario'] = $pnome;
            $_SESSION['nomeCompleto'] = $nome;
            $_SESSION['nivelAcesso'] = $nivelAcesso;
            $_SESSION['idFuncionarioLogado'] = $idFunc;
            $_SESSION['idUsuarioLogado'] = $idSessao;

            if($nivelAcesso == 2){
                header("Location: listaFuncionarios.php");
            }else{
                header("Location: userInfo.php?id=$idFunc");
            }

        } else {
            echo '<script>alert("Senha incorreta!")<script>';
            echo "<script>setTimeout(function(){ window.location.href = 'index.php'; }, 5);</script>";
        }
    }else {
        echo "<script>alert('Usuário não encontrado ou inativo, fale com um administrador!');</script>";
        echo "<script>setTimeout(function(){ window.location.href = 'index.php'; }, 5);</script>";
    }

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
        <a href="#" class="brand-logo center"><img class="logo" src="imagens/patrol_texto_bg.png" alt="patrol_logo"></a>
        <ul id="sidenav">
        </ul>
        </div>
    </nav>

</header>

    </HEAD>
    <body>

    <h4 class="center">Login</h4>

        <BR><BR><BR><BR>

        <main class="row col s12 container center">

        <form action="" method="post" class="form">

            <div class="login input-field col s12 center">
                <i class="material-icons prefix">email</i>
                <input type="email" name="login" id="login" maxlength="50" class="validate" required>
                <label for="login">E-mail</label>
            </div>

            <div class="login input-field col s12 center">
                <i class="material-icons prefix">password</i>
                <input type="password" name="senha" id="senha" maxlength="50" class="validate" required>
                <label for="senha">Senha</label>
            </div>

            <BR><BR>

            <input type="submit" name="entrar" value="Entrar" class="search">

        </form>
        

        </main>

        <?php include("footerContent.php");?> <!--adiciona o conteúdo do rodapé de modo modular usando o INCLUDE em PHP-->

    </body>
</HTML>