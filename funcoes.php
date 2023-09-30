<?php


//function que gera um switch/case para trazer "ativo" ou "inativo" das colunas de status no banco de dados

function traduz_status($status){

    $statusAtual = "";

switch($status){
    
    case 1:
        $statusAtual = 'Ativo';

        break;
    
    case 0:
        $statusAtual = 'Inativo';

        break;
}

    return $statusAtual;

}



//lista suspensa e select para ativar e inativar itens ou funcionarios
function lista_suspensa_inativa($status){

                        echo '<select name="status" id="status">';
                            echo '<option value="'. traduz_status($status) .'" selected>'. traduz_status($status).'</option>';
                                switch($status){
                                    case 1:
                                        echo '<option value="0">Inativo</option>';
                                        break;

                                    case 0:
                                        echo '<option value="1">Ativo</option>';
                                        break;
                                }
                        echo '</select>';


                    }






//function que gera um switch/case para trazer "ativo" ou "inativo" do endereco do funcionario das colunas de status no banco de dados
function traduz_statusEnd($statusEnd){

    $statusAtualEnd = "";

switch($statusEnd){
    
    case 1:
        $statusAtualEnd = 'Ativo';

        break;
    
    case 0:
        $statusAtualEnd = 'Inativo';

        break;
}

    return $statusAtualEnd;

}



//function que gera um switch/case para trazer genero por extenso com base em "m, f, e o" da coluna de genero no banco de dados
function traduz_genero($genero){

    $generoAtual = "";

    switch($genero){

        case 'm':
            $generoAtual = "Masculino";

            break;

        case "f":
            $generoAtual = "Feminino";

            break;

    }

    return $generoAtual;

}



?>