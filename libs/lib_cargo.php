<?php

header("Content-Type: text/html; charset=UTF-8",true);

include "dbconfig.php";

//recebendo variável da ação
$request_action = trim(strtolower($_REQUEST['action']));
$request_reference = trim(strtolower($_REQUEST['reference']));

switch ($request_reference) {
    case "cargo":
        switch ($request_action) {

            case "inserir_cargo":


                //Recebe as variáveis do datastring
                $request_descricao_ver = trim(strtoupper($_REQUEST['descricao']));
                $request_descricao = trim(($_REQUEST['descricao']));
                $request_id = trim($_REQUEST['cad_id']);

                //Verifica se o cargo já está cadastrado
                $SQL1 = "SELECT descricao FROM cargo WHERE upper(descricao) = '$request_descricao_ver'";
                $result = pg_query( $SQL1 ) or die("Couldn t execute query.".pq_last_error());
                $row1 = pg_fetch_array($result);
                
               if($row1 == ""){
                    //Insere no banco
                    $SQL = ("INSERT INTO cargo (descricao) VALUES ('$request_descricao')");

                    //Verifica se foi inserido com sucesso
                    $result = pg_query( $SQL ) or die("Couldn t execute query".pg_last_error());

                    echo "Cadastro realizado com sucesso";
                }else
                    echo "Cargo já cadastrado";
             
            break;


            case "update_cargo":

                //Recebe as variáveis do datastring
                $request_descricao = trim($_REQUEST['descricao']);
                $request_id = trim($_REQUEST['cad_id']);

                //Atualiza no banco
                $SQL = ("UPDATE cargo SET descricao = '$request_descricao' WHERE (id_cargo = $request_id)");

                //Verifica se foi inserido com sucesso
                $result = pg_query( $SQL ) or die("Couldn t execute query".pg_last_error());
                 echo "Atualização realizada com sucesso";
                 
            break;


            case "apagar_cargo":

                //Recebe as variáveis do datastring
                $request_id = trim($_REQUEST['cad_id']);

                //Apaga do banco
                $SQL = ("DELETE FROM cargo WHERE (id_cargo = $request_id)");

                //Verifica se foi inserido com sucesso
                $result = pg_query( $SQL ) or die("Couldn t execute query".pg_last_error());
                
            break;


            case "grid_buscar_cargo":

                $page = $_REQUEST['page'];
                $limit = $_REQUEST['rows'];
                $sidx = $_REQUEST['sidx'];
                $sord = $_REQUEST['sord'];
               
                if(!$sidx) $sidx =1;

                $result = pg_query("SELECT COUNT(*) AS count FROM cargo");
                $row = pg_fetch_array($result);
                $count = $row['count'];
                //echo $count;
                if( $count >0 ) {
                        $total_pages = ceil($count/$limit);
                } else {
                        $total_pages = 0;
                }
                
                if ($page > $total_pages) $page=$total_pages;

                $start = $limit*$page - $limit;
                if($start <0) $start = 0;


                $SQL = "SELECT id_cargo, descricao FROM cargo ORDER BY descricao ";
                $result = pg_query( $SQL ) or die("Couldn t execute query.".pq_last_error());
                        
                if ( stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml") ) {
                        header("Content-type: application/xhtml+xml;charset=utf-8"); }
                else { header("Content-type: text/xml;charset=utf-8");
                }

                        echo "<?xml version='1.0' encoding='utf-8'?>";
                        echo "<rows>";
                        echo "<page>".$page."</page>";
                        echo "<total>".$total_pages."</total>";
                        echo "<records>".$count."</records>";

                while($row = pg_fetch_array($result)) {
                        echo "<row id='". $row[id_cargo]."'>";
                        echo "<cell>". $row[descricao]."</cell>";
                        echo "</row>";
                }
                echo "</rows>";

            break;
        }

        }

//Fecha conexão com o banco de dados
pg_close($bd);
?>
