<?php

header("Content-Type: text/html; charset=UTF-8",true);

include "dbconfig.php";

//recebendo variável da ação
$request_action = trim(strtolower($_REQUEST['action']));
$request_reference = trim(strtolower($_REQUEST['reference']));

switch ($request_reference) {
    case "grade":
        switch ($request_action) {

            case "inserir_grade":

                //Recebe as variáveis do datastring
                $request_dt_implantacao = trim($_REQUEST['dt_implantacao']);
                $request_id = trim($_REQUEST['cad_id']);


                 //Montar a data no formato DD-MM-AAAA
                 $day = substr($request_dt_implantacao, 0, 2);
                 $month = substr($request_dt_implantacao, 3, 2);
                 $year = substr($request_dt_implantacao, 6, 4);

                 $meses = array("01"=>"Jan", "02"=>"Feb", "03"=>"Mar", "04"=>"Apr", "05"=>"May", "06"=>"June", "07"=>"July", "08"=>"Aug", "09"=>"Sept", "10"=>"Oct", "11"=>"Nov", "12"=>"Dec");

                 $dt_implantacao_format = $day. "-" . $meses[$month]. "-" . $year;

                //Verifica se a Grade já está cadastrado
                $SQL1 = "SELECT dt_implantacao FROM GradeCurricular WHERE (dt_implantacao = '$dt_implantacao_format') ";
                $result = pg_query( $SQL1 ) or die("Couldn t execute query.".pq_last_error());
                $row1 = pg_fetch_array($result);

                echo $row1;

                if($row1 == "")
                {
                     //Insere no bano
                    $SQL = ("INSERT INTO GradeCurricular (dt_implantacao) VALUES ('$dt_implantacao_format')");

                    //Verifica se foi inserido com sucesso
                    $result = pg_query( $SQL ) or die("Couldn t execute query".pg_last_error());

                    echo "Cadastro realizado com sucesso";
                }
                else echo "Grade já cadastrada";
                          
             
            break;


            case "update_grade":

                //Recebe as variáveis do datastring
                $request_dt_implantacao = trim($_REQUEST['dt_implantacao']);
                $request_id = trim($_REQUEST['cad_id']);

               
                //Montar a data no formato DD-MM-AAAA
                 $day = substr($request_dt_implantacao, 0, 2);
                 $month = substr($request_dt_implantacao, 3, 2);
                 $year = substr($request_dt_implantacao, 6, 4);

                 $meses = array("01"=>"Jan", "02"=>"Feb", "03"=>"Mar", "04"=>"Apr", "05"=>"May", "06"=>"June", "07"=>"July", "08"=>"Aug", "09"=>"Sept", "10"=>"Oct", "11"=>"Nov", "12"=>"Dec");

                 $dt_implantacao_format = $day. "-" . $meses[$month]. "-" . $year;

                //Atualiza no banco
                $SQL = ("UPDATE GradeCurricular SET dt_implantacao = '$dt_implantacao_format' WHERE (id_grade = $request_id)");

                //Verifica se foi inserido com sucesso
                $result = pg_query( $SQL ) or die("Couldn t execute query".pg_last_error());

                echo "Atualização realizada com sucesso";
            break;


            case "apagar_grade":

                //Recebe as variáveis do datastring
                $request_id = trim($_REQUEST['cad_id']);

                //Apaga do banco
                $SQL = ("DELETE FROM GradeCurricular WHERE (id_grade = $request_id)");

                //Verifica se foi inserido com sucesso
                $result = pg_query( $SQL ) or die("Couldn t execute query".pg_last_error());
                
            break;


            case "grid_buscar_grade":

                $page = $_REQUEST['page'];
                $limit = $_REQUEST['rows'];
                $sidx = $_REQUEST['sidx'];
                $sord = $_REQUEST['sord'];
               
                if(!$sidx) $sidx =1;

                $result = pg_query("SELECT COUNT(*) AS count FROM GradeCurricular");
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

                /* Chama a FuncFormataData (implementada no banco) para formatar a data */
                $SQL = "SELECT id_grade, FuncFormataData(dt_implantacao) AS dt_implantacao FROM GradeCurricular ORDER BY dt_implantacao";
                $result = pg_query( $SQL ) or die("A consulta a grade não foi realizada com sucesso.".pq_last_error());
                        
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
                        echo "<row id='". $row[id_grade]."'>";
                        echo "<cell>". "Grade " .$row[id_grade]."</cell>";
                        echo "<cell>". $row[dt_implantacao]."</cell>";
                        echo "</row>";
                }
                echo "</rows>";

            break;
        }

        }

//Fecha conexão com o banco de dados
pg_close($bd);
?>
