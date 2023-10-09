<?php


//function que gera um switch/case para trazer "ativo" ou "inativo" das colunas de status no banco de dados

function traduz_status($status){

    $statusAtual = "";

switch($status){
    
    case 1:
        $statusAtual = '<div style="color: white; background-color:green; border-radius:3px; text-align:center;"><b>Ativo</b></div>';

        break;
    
    case 0:
        $statusAtual = '<div style="color: white; background-color:red; border-radius:3px; text-align:center;"><b>Inativo</b></div>';

        break;
}

    return $statusAtual;

}

//traduz para exibir sem levar tag <p> e style como acima
function traduz_status_para_exibir($status){

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
                            echo '<option value="'. $status .'" selected>'. traduz_status($status).'</option>';
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



//lista suspensa e select para mostrar as opções de genero
function lista_suspensa_genero($genero){

                        echo '<select name="genero" id="genero">';
                            echo '<option value="'. $genero .'" selected>'. traduz_genero($genero).'</option>';
                                switch($genero){
                                    case "f":
                                        echo '<option value="m">Masculino</option>';
                                        break;

                                    case "m":
                                        echo '<option value="f">Feminino</option>';
                                        break;
                                }
                        echo '</select>';


                    }



//function que gera um switch/case para trazer genero por extenso com base em "m, f, e o" da coluna de genero no banco de dados
function traduz_genero($genero){

    $generoAtual = "";

    switch($genero){

        case "m":
            $generoAtual = "Masculino";

            break;

        case "f":
            $generoAtual = "Feminino";

            break;

    }

    return $generoAtual;

}