<?php 

include("autenticaContent.php");
include("conecta.php");

//verifica se o nivel de acesso é de adm, se n for é exibida mensagem de erro e o resto da página não carrega
if($_SESSION['nivelAcesso'] != 2) {
    echo "Acesso negado!";
    exit;
}

if (isset($_GET["id"])) {
    // Recupere o ID do registro a ser exibido
    $id = $_GET["id"];
    $nome = $_GET["nome"];
}

    //select todos os funcionarios ativos para a lista suspensa
    $sql = "SELECT * FROM funcionarios WHERE status = 1 AND idFuncionario = $id ORDER BY nome";
    $result = $mysqli->query($sql);

    if ($_SERVER["REQUEST_METHOD"] == "POST") { 

        date_default_timezone_set('America/Sao_Paulo');
        $dataHoraAtual = new DateTime();

    
        if (isset($_POST["form_id"])) {
            $form_id = $_POST["form_id"];
    
            if ($form_id == 1) {

                $mysqli->begin_transaction();

                $idFuncionario = $id;
                $dataEntrega = $_POST['dataEntrega'];

                $camisa_quantidade = $_POST['camisa_quantidade'];
                $calca_quantidade = $_POST['calca_quantidade'];
                $calcado_quantidade = $_POST['calcado_quantidade'];
                $jaqueta_quantidade = $_POST['jaqueta_quantidade'];

                $entregaUnifObservacoes = $_POST['entregaUnifObservacoes'];
                $dataCadastro = $dataHoraAtual->format('Y-m-d H:i:s');
                $responsavelCadastro = $_SESSION['nomeCompleto'];

                $cadastroEntrega = $mysqli->prepare("INSERT INTO controleentregauniformes (funcionarioID, quantidade_camisa, quantidade_calca, quantidade_calcado, quantidade_jaqueta, dataEntregaUniforme, dataCadastro, responsavelEntrega, entregaUnifObs) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $cadastroEntrega->bind_param("iiiiissss", $idFuncionario, $camisa_quantidade, $calca_quantidade, $calcado_quantidade, $jaqueta_quantidade,
                $dataEntrega, $dataCadastro, $responsavelCadastro, $entregaUnifObservacoes);

                if ($cadastroEntrega->execute()) {
                    $mysqli->commit();
                    echo "<script>alert('Entrega cadastrada com sucesso!');</script>";
                    echo "<script>setTimeout(function(){ window.location.href = 'controleEntregaUniformes.php'; }, 5);</script>";
                } else {
                    $mysqli->rollback();
                    echo "<script>alert('Falha no cadastro da entrega de uniformes '.$mysqli->error.');</script>";
                    echo "<script>setTimeout(function(){ window.location.href = 'controleEntregaUniformes.php'; }, 5);</script>";
                }

            }

        }

    }

    $chamaAsEntregas = "SELECT funcionarios.nome, controleentregauniformes.* 
                    FROM funcionarios
                    LEFT JOIN controleentregauniformes ON funcionarios.idFuncionario = controleentregauniformes.funcionarioID
                    WHERE controleentregauniformes.status = 1 AND idFuncionario = $id ORDER BY dataEntregaUniforme ASC";
    $resultado = $mysqli->query($chamaAsEntregas);

    $mysqli->close();

?>


<!DOCTYPE html>
<HTML lang="pt-BR">
    <HEAD>
        <TITLE>PATROL | CONTROLE UNIFORMES </TITLE>

        <script>

		        //JS para inicializar das listas suspensas SELECT (uniformes, cargos e unidade)
		        document.addEventListener('DOMContentLoaded', function() {
						var elems = document.querySelectorAll('select');
						var instances = M.FormSelect.init(elems);
			    });
		</script>

    <?php 
    include("headContent.php");
    require "mascaraContent.php";
    ?>

    <style>

        fieldset {
            border-radius: 10px;
            align-items: center;
        }

        textarea {
            border-radius: 10px;
            height: 40px !important;
        }
    </style>

    <script>//inicializador do modal
        
        document.addEventListener('DOMContentLoaded', function() {
        var elems = document.querySelectorAll('.modal');
        var instances = M.Modal.init(elems);
    
      });
        
    </script>   
    </HEAD>

    <body>

    <h4 class="center">Entrega de uniformes:</h4>

    <br>


    <div class="col s12 container">


                <form method="post" action="">

                            <input type="hidden" name="form_id" value="1">

                    <fieldset>

                    <br>

                            <div class="funcionarios col s6">
                                <h5 class="center"><?php echo $nome;?></h5>
                                <br>

                            <label for="dataEntrega" class="col s6">Data de entrega:</label>
                            <input type="tel" name="dataEntrega" id="dataEntrega" oninput="formatarData(this)" placeholder="DD/MM/AAAA" required><br>

                                
                        <fieldset>

                        <br>

                            <p>
                                <label>
                                    <input type="checkbox" class="checkbox" value="1">
                                    <span>Camisa</span>
                                </label>
                                <input type="number" name="camisa_quantidade" class="quantity" style="display: none;" placeholder="quantidade (1 a 3)" min="1" max="3">
                            </p>
                            <p>
                                <label>
                                    <input type="checkbox" class="checkbox" value="1">
                                    <span>Calça</span>
                                </label>
                                <input type="number" name="calca_quantidade" class="quantity" style="display: none;" placeholder="quantidade (1 a 3)" min="1" max="3">
                            </p>
                            <p>
                                <label>
                                    <input type="checkbox" class="checkbox" value="1">
                                    <span>Calçado</span>
                                </label>
                                <input type="number" name="calcado_quantidade" class="quantity" style="display: none;" placeholder="quantidade (1 a 3)" min="1" max="3">
                            </p>
                            <p>
                                <label>
                                    <input type="checkbox" class="checkbox" value="1">
                                    <span>Jaqueta</span>
                                </label>
                                <input type="number" name="jaqueta_quantidade" class="quantity" style="display: none;" placeholder="quantidade (1 a 3)" min="1" max="3">
                            </p>

                            
                            <script>//script para aparecer o input apenas quando o checkbox é marcado
                                const checkboxes = document.querySelectorAll('.checkbox');
                                const quantities = document.querySelectorAll('.quantity');

                                checkboxes.forEach((checkbox, index) => {
                                    checkbox.addEventListener('change', () => {
                                        if (checkbox.checked) {
                                            quantities[index].style.display = 'block';
                                            // Tornar o campo de entrada "required"
                                            quantities[index].setAttribute('required', 'required');
                                        } else {
                                            quantities[index].style.display = 'none';
                                            // Remover o atributo "required" quando não estiver visível
                                            quantities[index].removeAttribute('required');
                                        }
                                    });
                                });
                            </script>

                            <label for="obs" class="col s6">Observações:</label>
                            <textarea name="entregaUnifObservacoes" data-length="500" id="obs"></textarea>


                        </fieldset>
                        
                        <br>

                        <div class="center">
                            <button type="submit" class="search">Adicionar entrega</button>
                        </div>

                        <br>

                    </fieldset>

                </form>

                <br>

                <h5>Histórico de retirada de uniformes</h5>

                <br>

                            <div class="col s12">

                            <fieldset style="border-radius:10px">
                            <table>
                                    <thead>
                                        <tr>
                                            <th>Data entrega</th>
                                            <th>Quantidade de peças</th>
                                            <th>Obs:</th>
                                            
                                        </tr>
                                    </thead>
                                        <tbody>
                                        <?php
                                        if ($resultado->num_rows > 0) {
                                            while ($row = $resultado->fetch_assoc()) {
                                                $idEntregaUniforme = $row['idEntregaUniforme'];
                                                $quantidade_camisa = $row['quantidade_camisa'];
                                                $quantidade_calca = $row['quantidade_calca'];
                                                $quantidade_calcado = $row['quantidade_calcado'];
                                                $quantidade_jaqueta = $row['quantidade_jaqueta'];
                                                $dataEntregaUniforme = $row['dataEntregaUniforme'];
                                                $responsavelCadastro = $row['responsavelEntrega'];
                                                $unifObs = $row['entregaUnifObs'];
                                                $totalUniformes = $quantidade_camisa + $quantidade_calca + $quantidade_calcado + $quantidade_jaqueta;  //soma todas as peças para exibir na tela principal
                                                $modalId = 'modal' . $idEntregaUniforme;

                                            echo '<tr>';
                                            echo '<td><p>' . $dataEntregaUniforme . '</p></td>';
                                            echo '<td><h5>' . $totalUniformes . '</h5></td>';
                                            echo '<td><p>' . $unifObs . '</p></td>';
                                            echo '<td><button class="search modal-trigger" href="#'. $modalId .'"><i class="material-icons">search</i></button></td>';
                                            echo '</tr>';

                                            echo '<div id="'. $modalId . '" class="modal" style="border-radius: 10px">';
                                            echo '<div class="modal-content">';
                                            echo '<h6>' . $nome . '</h6><br>';
                                            echo '<div class="divider"></div><br>';
                                            echo '<label for="data">Data entrega</label>';
                                            echo '<p>' . $dataEntregaUniforme . '</p>';
                                            echo '<label for="camisa">Camisa(s) entregues:</label>';
                                            echo '<p class="col s6">' . $quantidade_camisa . '</p>';
                                            echo '<label for="calca">Calça(s) entregues:</label>';
                                            echo '<p class="col s6">' . $quantidade_calca . '</p>';
                                            echo '<label for="calcado">Calçado(s) entregues:</label>';
                                            echo '<p>' . $quantidade_calcado . '</p>';
                                            echo '<label for="jaqueta">Jaqueta(s) entregues:</label>';
                                            echo '<p>' . $quantidade_jaqueta . '</p>';
                                            echo '<label for="jaqueta">Observações:</label>';
                                            echo '<p>' . $unifObs . '</p>';
                                            echo '<label for="responsavel">Responsavel cadastro:</label>';
                                            echo '<p id="responsavel">' . $responsavelCadastro . '</p>';
                                            echo '</div>';
                                            echo '<div class="modal-footer">';
                                            echo '<button href="#!" class="search modal-close">Fechar</button>';
                                            echo '</div>';
                                            echo '</div>';
                                            
                                            }

                                        }else{
                                            echo '<tr><td colspan="2">Nenhum registro de férias encontrato</td></tr>';
                                        }
                                        ?>
                                        </tbody>
                                </table>
                                </fieldset>
                            
                            </div>

    </div>


    </body>

    <?php require "footerContent.php"; ?>

</HTML>