<?php

header("Content-Type: text/html; charset=UTF-8",true);

include "dbconfig.php";

//recebendo variável da ação
$request_action = trim(strtolower($_REQUEST['action']));
$request_reference = trim(strtolower($_REQUEST['reference']));

switch ($request_reference) {
    case "materia":
        switch ($request_action) {

            case "inserir_materia":

                //Recebe as variáveis do datastring
                $request_nome_ver = trim(strtoupper($_REQUEST['nome']));
                $request_nome = trim($_REQUEST['nome']);
                $request_id = trim($_REQUEST['cad_id']);

                //Verifica se a materia já está cadastrada
                $SQL = "SELECT nome FROM materia WHERE sem_acentos(upper(nome)) = sem_acentos('$request_nome_ver')";
                $result = pg_query($SQL) or die("Nao pode consultar a tebela materia".pq_last_error());;
                $row = pg_fetch_array($result);
               
                if($row == ""){
                    //Insere no banco
                    $SQL1 = ("INSERT INTO materia (nome) VALUES ('$request_nome')");

                    //Verifica se foi inserido com sucesso
                    $result1 = pg_query( $SQL1 ) or die("Couldn t execute query".pg_last_error());
                    echo "Cadastro Efetuado com sucesso!!";
                }
                else
                    echo "Materia já cadastrada";
            break;


            case "update_materia":

                //Recebe as variáveis do datastring
                $request_nome = trim($_REQUEST['nome']);
                $request_id = trim($_REQUEST['cad_id']);

                //Atualiza no banco
                $SQL = ("UPDATE materia SET nome = '$request_nome' WHERE (id_materia = $request_id)");

                //Verifica se foi inserido com sucesso
                $result = pg_query( $SQL ) or die("Couldn t execute query".pg_last_error());
                echo "Atualização Efetuada com sucesso!!";
            break;


            case "apagar_materia":

                //Recebe as variáveis do datastring
                $request_id = trim($_REQUEST['cad_id']);

                //Insere no bano
                $SQL = ("DELETE FROM materia WHERE (id_materia = $request_id)");

                //Verifica se foi inserido com sucesso
                $result = pg_query( $SQL ) or die("Couldn t execute query".pg_last_error());

            break;


            case "grid_buscar_materia":

                $page = $_REQUEST['page'];
                $limit = $_REQUEST['rows'];
                $sidx = $_REQUEST['sidx'];
                $sord = $_REQUEST['sord'];
               
                if(!$sidx) $sidx =1;

                $result = pg_query("SELECT COUNT(*) AS count FROM materia");
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


                $SQL = "SELECT id_materia, nome FROM materia ORDER BY nome ";
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
                        echo "<row id='". $row[id_materia]."'>";
                        echo "<cell>". $row[nome]."</cell>";
                        echo "</row>";
                }
                echo "</rows>";

            break;
        }

        }

//Fecha conexão com o banco de dados
pg_close($bd);
?>
