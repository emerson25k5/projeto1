<?php
session_start();

if (isset($_SESSION['login'])) {
  $login = $_SESSION['login'];

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $senhaAntiga = $_POST['senha_antiga'];
    $senhaNova = $_POST['senha_nova'];
    $confirmarSenha = $_POST['confirmar_senha'];

    include("conecta.php");

    if ($mysqli) {
      $query = "SELECT senha FROM usuarios WHERE login = '$login'";
      $resultado = mysqli_query($mysqli, $query);

      if ($resultado) {
        if (mysqli_num_rows($resultado) === 1) {
          $row = mysqli_fetch_assoc($resultado);
          $senhaAtual = $row['senha'];

          if (password_verify($senhaAntiga, $senhaAtual)) {
            if ($senhaNova === $confirmarSenha) {
              $senhaHash = password_hash($senhaNova, PASSWORD_DEFAULT);

              $query = "UPDATE usuarios SET senha = '$senhaHash' WHERE login = '$login'";
              $resultado = mysqli_query($mysqli, $query);

              if ($resultado) {
                echo "<script>alert('Senha alterada com sucesso!');</script>";
                echo "<script>setTimeout(function(){ window.location.href = 'listaFuncionarios.php'; }, 100);</script>";
              } else {
                echo "<script>alert('Erro ao atualizar a senha.');</script>";
              }
            } else {
              echo "<script>alert('As senhas não coincidem.');</script>";
            }
          } else {
            echo "<script>alert('A senha antiga fornecida é inválida.');</script>";
          }
        } else {
          echo "<script>alert('Usuário não encontrado.');</script>";
        }
      } else {
        echo "<script>alert('Erro ao consultar o banco de dados.');</script>";
      }

 
      mysqli_close($mysqli);
    } else {
      echo "<script>alert('Erro ao conectar ao banco de dados.');</script>";
    }
  }
}
?>
<!DOCTYPE html>
<HTML lang="pt-BR">
  <HEAD>
    <TITLE>PATROL | Atualização de senha</TITLE>

    <?php
    include("headContent.php");
    include("mascaraContent.php");
    ?>
  
  
    </HEAD>
 <BODY>


 <br><br>

<div style="text-align: center">
  <h3>Crie uma nova senha</h3>
</div>

<br><br><br><br><br><br>

<div class="box container center">
    <form class="col s12" action="" method="POST">

      <div class="input-field col s12">
        <input id="senha_antiga" type="password" name="senha_antiga"  maxlength="15" class="validate" required>
        <label for="senha_antiga" maxlength="15" style="color:black;">Senha atual</label>
      </div>

      <div class="input-field col s12">
        <input id="senha_nova" type="password" name="senha_nova" class="validate" maxlength="15" required>
        <label for="senha_nova" maxlength="15" style="color:black;">Crie uma senha</label>
      </div>

      <div class="input-field col s12">
        <input id="confirmar_senha" type="password" name="confirmar_senha" class="validate" maxlength="15" required>
        <label for="confirmar_senha" maxlength="15" style="color:black;">Repita a senha</label>
      </div>

      <button type="submit" value="Confirmar" id="submit" class="search">Confirmar alteração</button>

      <script>

function ValidaCPF() {
  var cpf = document.getElementById("cpf").value;
  var cpfValido = /^(([0-9]{3}.[0-9]{3}.[0-9]{3}-[0-9]{2}))$/;
  if (cpfValido.test(cpf) == false) {
    cpf = cpf.replace(/\D/g, ""); 
    if (cpf.length == 11) {
      cpf = cpf.replace(/(\d{3})(\d)/, "$1.$2"); 
      cpf = cpf.replace(/(\d{3})(\d)/, "$1.$2"); 
      cpf = cpf.replace(/(\d{3})(\d{1,2})$/, "$1-$2"); 
      document.getElementById("cpf").value = cpf;
    } else {
      console.log("CPF inválido");
    }
  }
}
      </script>

    </form>

</div>
                  <br><br><br><br><br>


    <?php
    include("footerContent.php");

    ?>


 </BODY>
 </HTML>
