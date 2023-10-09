<?php 

include("autenticaContent.php");
include("conecta.php");

//verifica se o nivel de acesso é de adm, se n for é exibida mensagem de erro e o resto da página não carrega
if($_SESSION['nivelAcesso'] != 2) {
    echo "Acesso negado!";
    exit;
}

$_statusCad = "";

    //select todos os funcionarios ativos para a lista suspensa
    $sql = "SELECT * FROM funcionarios WHERE status = 1 ORDER BY nome";
    $result = $mysqli->query($sql);

    if ($_SERVER["REQUEST_METHOD"] == "POST") { 

        date_default_timezone_set('America/Sao_Paulo');
        $dataHoraAtual = new DateTime();

    
        if (isset($_POST["form_id"])) {
            $form_id = $_POST["form_id"];
    
            if ($form_id == 1) {

                $mysqli->begin_transaction();

                $idFuncionario = $_POST['funcionario_selecionado'];
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
                    WHERE controleentregauniformes.status = 1 ORDER BY funcionarios.nome";
    $resultado = $mysqli->query($chamaAsEntregas);

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
                                <label for="funcionario_selecionado">Funcionario:</label>
                                <select name="funcionario_selecionado" id="funcionario_selecionado" required>
                                    <option value="" selected>Selecione:</option>
                                    <?php
                                        // Verifique se há registros e gere as opções do select
                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                $idFuncionario = $row['idFuncionario'];
                                                $nomeFuncionario = $row['nome'];
                                                echo '<option value="' . $idFuncionario . '">'. $nomeFuncionario.'</option>';
                                            }
                                        } else {
                                            echo '<option value="" disabled selected>Nenhum funcionário encontrado</option>';
                                        }
                                    ?>
                                </select>
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
                                            <th>Funcionário</th>
                                            <th>Data entrega</th>
                                            <th>Camisa/Quant</th>
                                            <th>Calça/Quant</th>
                                            <th>Calçado/Quant</th>
                                            <th>Jaqueta/Quant</th>
                                            <th>Obs:</th>
                                            
                                        </tr>
                                    </thead>
                                        <tbody>
                                        <?php
                                        if ($resultado->num_rows > 0) {
                                            while ($row = $resultado->fetch_assoc()) {
                                                $nomeFuncionario = $row['nome'];
                                                $quantidade_camisa = $row['quantidade_camisa'];
                                                $quantidade_calca = $row['quantidade_calca'];
                                                $quantidade_calcado = $row['quantidade_calcado'];
                                                $quantidade_jaqueta = $row['quantidade_jaqueta'];
                                                $dataEntregaUniforme = $row['dataEntregaUniforme'];
                                                $unifObs = $row['entregaUnifObs'];              

                                            echo '<tr>';
                                            echo '<td><p>' . $nomeFuncionario . '</p></td>';
                                            echo '<td><p>' . $dataEntregaUniforme . '</p></td>';
                                            echo '<td><p>' . $quantidade_camisa . '</p></td>';
                                            echo '<td><p>' . $quantidade_calca . '</p></td>';
                                            echo '<td><p>' . $quantidade_calcado . '</p></td>';
                                            echo '<td><p>' . $quantidade_jaqueta . '</p></td>';
                                            echo '<td><p>' . $unifObs . '</p></td>';
                                            echo '</tr>';
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