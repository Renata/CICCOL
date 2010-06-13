<?php

header("Content-Type: text/html; charset=UTF-8",true);

include "dbconfig.php";
@session_start();

$senha_atual = trim($_REQUEST['senha_atual']);
$senha_nova = trim($_REQUEST['senha_nova']);
$idUsuario = $_SESSION['idUsuario'];

// QUERY //

if($idUsuario == 1)
    {
    echo "Voce nao pode alterar a sneha do root";
    }
else
{

    $consulta = pg_query("SELECT senha FROM autenticacao WHERE senha = '$senha_atual' AND id_user = '$idUsuario'") or die("Erro tabela usuario".pg_last_error());
    $saida = pg_fetch_array($consulta);

    if($saida)
        {
        $consulta = pg_query("UPDATE autenticacao SET senha = '$senha_nova' WHERE id_user = '$idUsuario' AND NOT (id_user = '1') ") or die("Erro tabela usuario".pg_last_error());
        $saida = pg_fetch_array($consulta);

        echo "Mudança ";
        }
        else
            {
            echo "senha atual invalida";
            }

}

//Fecha conexão com o banco de dados
pg_close($bd);
?>
