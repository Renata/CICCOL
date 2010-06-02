<?php

header("Content-Type: text/html; charset=UTF-8",true);

include "dbconfig.php";

//recebendo variável da ação
$request_action = trim(strtolower($_REQUEST['action']));
$request_reference = trim(strtolower($_REQUEST['reference']));

switch ($request_reference) {
    case "horario":
        switch ($request_action) {

            case "inserir_horario":

                //Recebe as variáveis do datastring
                $request_disciplina = trim($_REQUEST['disciplina']);
                $request_turma = trim($_REQUEST['turma']);
                $request_dia = trim($_REQUEST['dia']);
                $request_inicio = trim($_REQUEST['inicio']);
                $request_fim = trim($_REQUEST['fim']);
                $request_id = trim($_REQUEST['cad_id']);

                             
                //Verifica se o horário já está cadastrado
               // $SQL1 = "SELECT hora FROM horario WHERE hora = '$request_horario'";
               // $result = pg_query( $SQL1 ) or die("Couldn t execute query.".pq_last_error());
                //$row1 = pg_fetch_array($result);

                $SQL2 = "SELECT cod_disciplina FROM disciplina WHERE nome= '$request_disciplina' ";
                $result2 = pg_query ($SQL2);
                $row2 = pg_fetch_array($result2);
                $codDisc = $row2[cod_disciplina];
                //echo  $codDisc;
              
              // if($row1 == ""){
                    //Insere no banco

                $SQL = ("INSERT INTO horario (dia_semana, hora_inicio, hora_fim) VALUES ('$request_dia', '$request_inicio', '$request_fim'  )");

                $SQL1 = ("INSERT INTO disciplinahorario (dia_semana, cod_disciplina, nome, turma, hora_inicio, hora_fim) VALUES ('$request_dia', '$codDisc', '$request_disciplina', '$request_turma', '$request_inicio', '$request_fim'  )");

                    //Verifica se foi inserido com sucesso
                $result = pg_query( $SQL ) or die("Nao foi possivel inserir na tabela horario".pg_last_error());
    
                $result1 = pg_query( $SQL1 ) or die("Couldn t execute query".pg_last_error());

                //    echo "Cadastro realizado com sucesso";
                //}else
                 //   echo "Horário já cadastrado";
             
            break;


            case "update_shorario":

                //Recebe as variáveis do datastring
                $request_disciplina = trim($_REQUEST['disciplina']);
                $request_turma = trim($_REQUEST['turma']);
                $request_dia = trim($_REQUEST['dia']);
                $request_inicio = trim($_REQUEST['inicio']);
                $request_fim = trim($_REQUEST['fim']);
                $request_id = trim($_REQUEST['cad_id']);

                //Atualiza no banco
                $SQL = ("UPDATE disciplinahorario SET hora_inicio = '$request_inicio', hora_fim = '$request_fim'  WHERE (id_disciplinahorario = $request_id)");

                //Verifica se foi inserido com sucesso
                $result = pg_query( $SQL ) or die("Couldn t execute query".pg_last_error());
                 echo "Atualização realizada com sucesso";
                 
            break;


            case "apagar_horario":

                //Recebe as variáveis do datastring
                $request_id = trim($_REQUEST['cad_id']);

                //Apaga do banco
                $SQL = ("DELETE FROM disciplinahorario WHERE (id_disciplinahorario = $request_id)");

                //Verifica se foi inserido com sucesso
                $result = pg_query( $SQL ) or die("Couldn t execute query".pg_last_error());
                
            break;


            case "grid_buscar_horario":

                $page = $_REQUEST['page'];
                $limit = $_REQUEST['rows'];
                $sidx = $_REQUEST['sidx'];
                $sord = $_REQUEST['sord'];
               
                if(!$sidx) $sidx =1;

                $result = pg_query("SELECT COUNT(*) AS count FROM disciplinahorario");
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


                $SQL = "SELECT id_disciplinahorario, dia_semana, nome, turma, hora_inicio, hora_fim FROM disciplinahorario ORDER BY nome";
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

                         //Renomear quantidade de turma
                        $qt_turma = $row[turma];

                        if($qt_turma == "3"){
                            $qt_turma = "Única";
                        }elseif($qt_turma == "1"){
                            $qt_turma = "Turma A";
                        }elseif($qt_turma == "2"){
                            $qt_turma = "Turma B";
                        }

                        echo "<row id='". $row[id_disciplinahorario]."'>";
                        echo "<cell>". $row[nome]."</cell>";
                        echo "<cell>". $qt_turma."</cell>";
                        echo "<cell>". $row[dia_semana]."</cell>";
                        echo "<cell>". $row[hora_inicio]."</cell>";
                        echo "<cell>". $row[hora_fim]."</cell>";
                        echo "</row>";
                }
                echo "</rows>";

            break;


             case "buscar_turma":


                //Recebe as variáveis do datastring
                $request_disciplina = trim($_REQUEST['disciplina']);
                $request_turma = trim($_REQUEST['turma']);
                $request_id = trim($_REQUEST['cad_id']);

                $SQL3 = pg_query("SELECT turma FROM disciplina WHERE nome = '$request_disciplina' ") or die ("Não foi possível consultar a tebela disciplina".pg_last_error());
                //$row1 = pg_fetch_array($SQL3);

                while ($row_1 = pg_fetch_array($SQL3)){
                    $qt_turma= $row_1['turma'];
                     //echo $qt_turma;
                     if($qt_turma==3){
                         $option = '3';
                         echo $option;
                    }
                }
            break;
        
        }
        }

//Fecha conexão com o banco de dados
pg_close($bd);
?>
