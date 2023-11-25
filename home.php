<?php 

require "autenticaContent.php";
require "conecta.php";
require "configuracoes.php";

if($_SESSION['nivelAcesso'] != 2) {
    echo "Acesso negado!";
    exit;
}

$itensMenu = "SELECT * FROM itensmenu WHERE status = 1 ORDER BY idItem ASC";
$result = $mysqli->query($itensMenu);

//conta quanto funcionarios tem
$funcionarios = "SELECT * FROM funcionarios";
$rstFuncionarios = $mysqli->query($funcionarios);

if($rstFuncionarios){

    $funcQuantidade = $rstFuncionarios->num_rows;
}


//conta quantos funcionarios ativos tem
$funcAtivos = "SELECT * FROM funcionarios WHERE status = 1";
$rstAtivos = $mysqli->query($funcAtivos);

if($rstAtivos){

    $funcAtivos = $rstAtivos->num_rows;
}

//conta quantas unidade ativos tem
$adms = "SELECT * FROM usedperfilacesso WHERE nivelPerfilID = 2";
$sqlAdm = $mysqli->query($adms);

if($sqlAdm){

    $quantidadeAdm = $sqlAdm->num_rows;
}

//conta quantos cargos tem
$cargos = "SELECT * FROM cargos";
$rstCargos = $mysqli->query($cargos);

if($rstCargos){

    $cargosQuantidade = $rstCargos->num_rows;
}

//conta quantos cargos ativos tem
$cargosAtv = "SELECT * FROM cargos WHERE status = 1";
$rstCargosAtv = $mysqli->query($cargosAtv);

if($rstCargosAtv){

    $cargosAtivos = $rstCargosAtv->num_rows;
}

//conta quantos unidades tem
$unidades = "SELECT * FROM unidades";
$rstUnidades = $mysqli->query($unidades);

if($rstUnidades){

    $unidadesQuantidade = $rstUnidades->num_rows;
}

//conta quantas unidade ativos tem
$unidadesAtv = "SELECT * FROM unidades WHERE status = 1";
$rstUnidadesAtv = $mysqli->query($unidadesAtv);

if($rstUnidadesAtv){

    $unidadesAtivas = $rstUnidadesAtv->num_rows;
}



//array com os valores obtidos nos selects
$valoresDash = [

    "Funcionários" => $funcQuantidade,
    "Funcionários Ativos" => $funcAtivos,
    "Administradores" => $quantidadeAdm,
    "Cargos" => $cargosQuantidade,
    "Cargos Ativos" => $cargosAtivos,
    "Unidades" => $unidadesQuantidade,
    "Unidades Ativas" => $unidadesAtivas

];

$inativaFloat = true;

$mysqli->close();

?>

<!DOCTYPE html>
<HTML lang="pt-BR">
    <HEAD>
        
        <TITLE><?php echo NOME_EMPRESA;?> | HOME </TITLE>

        <?php
        require "headContent.php"; 
        require "funcoes.php";
        ?>

        <BR>

    </HEAD>
    <body>

    <div class="menu-inicial-content  container">

        <!-- exibe os itens do menu -->
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {   
        ?>

        <div class="menu-home-item z-depth-1 hoverable" style="background-color:<?php echo $row['corItem'];?>" onclick="window.location.href='<?php echo $row['linkItem']; ?>';" style="cursor: pointer;">
        
            <p class="titulo-content" style="color: <?php echo $row['corIcone']; ?>">
                <?php echo $row['nomeItem']; ?>
            </p>
            <div class="icone-content" style="color: <?php echo $row['corIcone']; ?>">
                <i class='item-icone material-icons'><?php echo $row['iconeItem']; ?></i>
            </div>

        </div>
            <?php } ?>
        <?php } ?>

    </div>


    <!-- exibe os valores do dashboard -->
    <div class="container"><h4>Dashboard</h4></div>

    <div class="menu-inicial-content  container">

        
        <?php
        foreach ($valoresDash as $key => $value) {
        ?>

        <div class="menu-home-item">
            <p class="titulo-content">
            <?php echo $key; ?>
            </p>
            <div class="icone-content">
                <h6 class="item-icone"><?php echo $value; ?></h6>
            </div>
        </div>

        <?php } ?>
    </div>

    <?php include("footerContent.php");?> <!--adiciona o conteúdo do rodapé de modo modular usando o INCLUDE em PHP-->

    </body>
</HTML>