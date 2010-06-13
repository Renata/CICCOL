<?php

header("Content-Type: text/html; charset=UTF-8",true);

include "dbconfig.php";
@session_start();

//recebendo variável da ação
$request_action = trim(strtolower($_REQUEST['action']));
$request_reference = trim(strtolower($_REQUEST['reference']));

switch ($request_reference) {
    case "professor":
        switch ($request_action) {

            case "update_dados_professor":

                //Recebe as variáveis do datastring
                
                $request_sobrenome = trim($_REQUEST['sobrenome']);
                $request_email = trim($_REQUEST['email']);
                $request_nascimento = trim($_REQUEST['nascimento']);
                $idUsuario = $_SESSION['idUsuario'];

                //Atualiza no banco
                $SQL = ("UPDATE usuario SET sobrenome = '$request_sobrenome', email= '$request_email', dt_nasc = '$request_nascimento' WHERE (id_user = $idUsuario)");
                
                //Verifica se foi inserido com sucesso
                $result = pg_query( $SQL ) or die("Couldn t execute query".pg_last_error());
      
                echo "Atualização realizada com sucesso";
            break;


            case "grid_buscar_dados_professor":

                $idUsuario = $_SESSION['idUsuario'];
               
                $SQL = "SELECT D.matricula, U.nome, U.cpf, U.email, U.sobrenome, FuncFormataData(U.dt_nasc) as data FROM usuario U JOIN docente D ON U.id_user= '$idUsuario' AND D.id_user = U.id_user";
                $result = pg_query( $SQL ) or die("Couldn t execute query.".pq_last_error());

                if ( stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml") ) {
                        header("Content-type: application/xhtml+xml;charset=utf-8"); }
                else { header("Content-type: text/xml;charset=utf-8");
                }

                        echo "<?xml version='1.0' encoding='utf-8'?>";
                        echo "<rows>";

                while($row = pg_fetch_array($result)) {

                       
                        echo "<row id='". $idUsuario."'>";
                        echo "<cell>". $row[matricula]."</cell>";
                        echo "<cell>". $row[nome]."</cell>";
                        echo "<cell>". $row[sobrenome]."</cell>";
                        echo "<cell>". $row[email]."</cell>";
                        echo "<cell>". $row[cpf]."</cell>";
                        echo "<cell>". $row[data]."</cell>";
                        
                        echo "</row>";
                }
                echo "</rows>";

            break;

            
        }

        }

//Fecha conexão com o banco de dados
pg_close($bd);
?>
