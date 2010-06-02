<?php

header("Content-Type: text/html; charset=UTF-8",true);

include "dbconfig.php";

//recebendo variável da ação
$request_action = trim(strtolower($_REQUEST['action']));
$request_reference = trim(strtolower($_REQUEST['reference']));

switch ($request_reference) {
    case "disciplina":
        switch ($request_action) {

            case "inserir_disciplina":


                //Recebe as variáveis do datastring
                $request_codigo = trim(strtoupper($_REQUEST['codigo']));
                $request_nome_ver = trim(strtoupper($_REQUEST['nome']));
                $request_nome = trim($_REQUEST['nome']);
                $request_ch = trim($_REQUEST['ch']);
                $request_aluno = trim($_REQUEST['aluno']);
                $request_credito = trim($_REQUEST['credito']);
                $request_semestre = trim($_REQUEST['semestre']);
                $request_materia = trim($_REQUEST['materia']);
                $request_turma = trim($_REQUEST['turma']);
                $request_opt = trim($_REQUEST['opt']);
                $request_id = trim($_REQUEST['cad_id']);

               
                $SQL4 = "SELECT id_materia FROM materia WHERE nome = '$request_materia'";
                $result4= pg_query($SQL4) or die("Couldn t execute query.".pq_last_error());
                $row4 = pg_fetch_array($result4);
                $idMateria = $row4[id_materia];
                

                $SQL3 = "SELECT cod_disciplina, nome FROM disciplina WHERE (cod_disciplina = '$request_codigo' OR sem_acentos(upper(nome)) = sem_acentos('$request_nome_ver') )";
                $result3 = pg_query( $SQL3 ) or die("Couldn t execute query.".pq_last_error());
                $row = pg_fetch_array($result3);
                
                if($row == ""){
                    if($request_turma == "Única"){
                        //Insere no banco
                        $SQL = ("INSERT INTO disciplina (cod_disciplina, nome, carga_horaria, qtd_max_aluno, num_cred, semestre, id_materia, optativa ,turma ) VALUES ('$request_codigo', '$request_nome', '$request_ch', '$request_aluno', '$request_credito', '$request_semestre', $idMateria, '$request_opt', '3')");

                        //Verifica se foi inserido com sucesso
                        $result = pg_query( $SQL ) or die("Não foi possivel inserir em disciplina".pg_last_error());
                        echo "Cadastro realizado com sucesso";
                    }
                    elseif($request_turma=="Duas"){
                        //Insere no banco
                        $SQL1 = ("INSERT INTO disciplina (cod_disciplina, nome, carga_horaria, qtd_max_aluno, num_cred, semestre, id_materia, optativa, turma ) VALUES ('$request_codigo', '$request_nome', '$request_ch', '$request_aluno', '$request_credito', '$request_semestre', $idMateria, '$request_opt', '1')");
                        $SQL2 = ("INSERT INTO disciplina (cod_disciplina, nome, carga_horaria, qtd_max_aluno, num_cred, semestre, id_materia, optativa, turma ) VALUES ('$request_codigo', '$request_nome', '$request_ch', '$request_aluno', '$request_credito', '$request_semestre', $idMateria, '$request_opt', '2')");

                        //Verifica se foi inserido com sucesso
                        $result1 = pg_query( $SQL1 ) or die("Não foi possivel inserir em disciplina".pg_last_error());
                        $result2 = pg_query( $SQL2 ) or die("Não foi possivel inserir em disciplina".pg_last_error());

                        echo "Cadastro realizado com sucesso";
                    }

                }else echo "Disciplina ja cadastrada";
          
                
            break;


            case "update_disciplina":

                //Recebe as variáveis do datastring
                $request_codigo = trim(strtoupper($_REQUEST['codigo']));
                $request_nome = trim($_REQUEST['nome']);
                $request_ch = trim($_REQUEST['ch']);
                $request_aluno = trim($_REQUEST['aluno']);
                $request_credito = trim($_REQUEST['credito']);
                $request_semestre = trim($_REQUEST['semestre']);
                $request_materia = trim($_REQUEST['materia']);
                $request_id = trim($_REQUEST['cad_id']);


                $SQL4 = "SELECT id_materia FROM materia WHERE nome = '$request_materia'";
                $result4= pg_query($SQL4) or die("Couldn t execute query.".pq_last_error());
                $row4 = pg_fetch_array($result4);
                $idMateria = $row4[id_materia];

              
                //Atualiza no banco
                $SQL = ("UPDATE disciplina SET cod_disciplina = '$request_codigo', nome = '$request_nome', carga_horaria = '$request_ch', qtd_max_aluno = '$request_aluno', num_cred = '$request_credito', semestre = '$request_semestre', id_materia = '$idMateria' WHERE (id_disciplina = $request_id)");

                //Verifica se foi inserido com sucesso
                $result = pg_query( $SQL ) or die("Couldn t execute query".pg_last_error());
                 echo "Atualização realizada com sucesso";
 
 
            break;


            case "apagar_disciplina":

                //Recebe as variáveis do datastring
                $request_id = trim($_REQUEST['cad_id']);

                //Insere no bano
                $SQL = ("DELETE FROM disciplina WHERE (id_disciplina = $request_id)");

                //Verifica se foi inserido com sucesso
                $result = pg_query( $SQL ) or die("Couldn t execute query".pg_last_error());
                
            break;


            case "grid_buscar_disciplina":

                $page = $_REQUEST['page'];
                $limit = $_REQUEST['rows'];
                $sidx = $_REQUEST['sidx'];
                $sord = $_REQUEST['sord'];
               
                if(!$sidx) $sidx =1;

                $result = pg_query("SELECT COUNT(*) AS count FROM disciplina");
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


                $SQL = "SELECT D.id_disciplina, D.cod_disciplina, D.nome, M.nome AS nome_materia, D.carga_horaria, D.qtd_max_aluno, D.num_cred, D.semestre, D.turma, D.optativa FROM disciplina D JOIN materia M ON D.id_materia = M.id_materia ORDER BY semestre";
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

                        echo $qt_turma;
                        echo "<row id='". $row[id_disciplina]."'>";
                        echo "<cell>". $row [cod_disciplina]."</cell>";
                        echo "<cell>". $row[nome]."</cell>";
                        echo "<cell>". $row[nome_materia]."</cell>";
                        echo "<cell>". $row[carga_horaria]."</cell>";
                        echo "<cell>". $row[num_cred]."</cell>";
                        echo "<cell>". $row[semestre]."</cell>";
                        echo "<cell>". $row[qtd_max_aluno]."</cell>";                        
                        echo "<cell>". $qt_turma."</cell>";
                        echo "<cell>". $row[optativa]."</cell>";
                        echo "</row>";
                }
                echo "</rows>";

            break;
        }

        }

//Fecha conexão com o banco de dados
pg_close($bd);
?>
