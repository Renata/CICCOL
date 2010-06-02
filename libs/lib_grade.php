<?php

header("Content-Type: text/html; charset=UTF-8",true);

include "dbconfig.php";

//recebendo variável da ação
$request_action = trim(strtolower($_REQUEST['action']));
$request_reference = trim(strtolower($_REQUEST['reference']));

switch ($request_reference)
{
    case "grade":
        switch ($request_action) 
        {
            case "buscar_grade":

               //Recebe as variáveis do datastring
                $request_id_grade = trim(strtoupper($_REQUEST['id_grade']));

                $idGrade = $request_id_grade{strlen($request_id_grade)-1};

            break;

           case "grid_buscar_grade1":

                //Recebe as variáveis do datastring
                $request_id_grade = trim(strtoupper($_REQUEST['id_grade']));

                $idGrade = $request_id_grade{strlen($request_id_grade)-1};

                echo $idGrade;
               
                /* Se nenhuma grade foi selecionada, mostra as disciplinas da grade mais atual  */
                if ($idGrade == "")
                {
                    $aux=pg_query("SELECT MAX(id_grade) AS id_grade FROM GradeCurricular");
                    $row_aux = pg_fetch_array($aux);
                    $idGrade = $row['id_grade'];
                }

                               
                $result = pg_query("SELECT COUNT(*) AS count FROM Disciplina WHERE (semestre='1' AND optativa='1' AND id_grade = '1')");
                $row = pg_fetch_array($result);
                $count = $row['count'];

                

                $SQL = "SELECT M.id_materia AS id_materia, M.nome AS nome_materia, D.nome AS nome_disciplina, carga_horaria, num_cred FROM materia M JOIN Disciplina D ON M.id_materia=D.id_materia WHERE (semestre='1' AND optativa='1' AND D.nome IN (SELECT nome FROM DisciplinaGradeCurricular WHERE id_grade = '1')) ORDER BY M.nome";
                $result = pg_query( $SQL ) or die("Couldn t execute query.".pq_last_error());

                if ( stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml") ) {
                        header("Content-type: application/xhtml+xml;charset=utf-8"); }
                else { header("Content-type: text/xml;charset=utf-8");
                }

                        echo "<?xml version='1.0' encoding='utf-8'?>";
                        echo "<rows>";
                        echo "<records>".$count."</records>";

                while($row = pg_fetch_array($result))
                {
                        echo "<row id='". $row[id_materia]."'>";
                        echo "<cell>".""."</cell>";
                        echo "<cell>". $row[nome_materia]."</cell>";
                        echo "<cell>". $row[nome_disciplina]."</cell>";
                        echo "<cell>". $row[carga_horaria]."</cell>";
                        echo "<cell>". $row[num_cred]."</cell>";
                        /* Seleciona as matérias que são pré-requisto das matérias correntes */
                        $result_1 = pg_query("SELECT requisito_nome FROM DisciplinaRequisitaDisciplina WHERE nome = '$row[nome_disciplina]'");
                        $aux='';
                        /* Colaca as matérias que são pré-requisito em um array. */
                        while($row_1 = pg_fetch_array($result_1))
                        {
                            /* Transforma as matérias que são pré-requisito em uma única string */
                            $aux = $aux . ', ' . $row_1[requisito_nome];

                        }
                        /* Se não houver nenhum pré-requisito, mostra os --- na tela.  */
                        if($aux == '')
                            $aux = '---';
                        else
                        {
                            /* Remove a vírgula inicial da string*/
                            $aux = substr($aux, 1);
                            /* Retira os espaços do começo e do fim*/
                            $aux = trim($aux);
                        }
                        echo "<cell>". $aux."</cell>";
                        echo "</row>";
                }
                echo "</rows>";

            break;

            case "grid_buscar_grade2":

               $page = $_REQUEST['page'];
               $limit = $_REQUEST['rows'];
               $sidx = $_REQUEST['sidx'];
               $sord = $_REQUEST['sord'];

               if(!$sidx) $sidx =1;

                $result = pg_query("SELECT COUNT(*) AS count FROM Disciplina WHERE (semestre='2' AND optativa='1')");
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


                $SQL = "SELECT M.id_materia AS id_materia, M.nome AS nome_materia, D.nome AS nome_disciplina, carga_horaria, num_cred FROM materia M JOIN Disciplina D ON M.id_materia=D.id_materia WHERE (semestre='2' AND optativa='1') ORDER BY M.nome ";
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

                while($row = pg_fetch_array($result))
                {
                        echo "<row id='". $row[id_materia]."'>";
                        echo "<cell>". $row[nome_materia]."</cell>";
                        echo "<cell>". $row[nome_disciplina]."</cell>";
                        echo "<cell>". $row[carga_horaria]."</cell>";
                        echo "<cell>". $row[num_cred]."</cell>";
                        /* Seleciona as matérias que são pré-requisto das matérias correntes */
                        $result_1 = pg_query("SELECT requisito_nome FROM DisciplinaRequisitaDisciplina WHERE nome = '$row[nome_disciplina]'");
                        $aux='';
                        /* Colaca as matérias que são pré-requisito em um array. */
                        while($row_1 = pg_fetch_array($result_1))
                        {
                            /* Transforma as matérias que são pré-requisito em uma única string */
                            $aux = $aux . ', ' . $row_1[requisito_nome];

                        }
                        /* Se não houver nenhum pré-requisito, mostra os --- na tela.  */
                        if($aux == '')
                            $aux = '---';
                        else
                        {
                            /* Remove a vírgula inicial da string*/
                            $aux = substr($aux, 1);
                            /* Retira os espaços do começo e do fim*/
                            $aux = trim($aux);
                        }
                        echo "<cell>". $aux."</cell>";
                        echo "</row>";
                }
                echo "</rows>";

            break;

            case "grid_buscar_grade3":

               $page = $_REQUEST['page'];
               $limit = $_REQUEST['rows'];
               $sidx = $_REQUEST['sidx'];
               $sord = $_REQUEST['sord'];

               if(!$sidx) $sidx =1;

                $result = pg_query("SELECT COUNT(*) AS count FROM Disciplina WHERE (semestre='3' AND optativa='1')");
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


                $SQL = "SELECT M.id_materia AS id_materia, M.nome AS nome_materia, D.nome AS nome_disciplina, carga_horaria, num_cred FROM materia M JOIN Disciplina D ON M.id_materia=D.id_materia WHERE (semestre='3' AND optativa='1') ORDER BY M.nome ";
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

                while($row = pg_fetch_array($result))
                {
                        echo "<row id='". $row[id_materia]."'>";
                        echo "<cell>". $row[nome_materia]."</cell>";
                        echo "<cell>". $row[nome_disciplina]."</cell>";
                        echo "<cell>". $row[carga_horaria]."</cell>";
                        echo "<cell>". $row[num_cred]."</cell>";
                        /* Seleciona as matérias que são pré-requisto das matérias correntes */
                        $result_1 = pg_query("SELECT requisito_nome FROM DisciplinaRequisitaDisciplina WHERE nome = '$row[nome_disciplina]'");
                        $aux='';
                        /* Colaca as matérias que são pré-requisito em um array. */
                        while($row_1 = pg_fetch_array($result_1))
                        {
                            /* Transforma as matérias que são pré-requisito em uma única string */
                            $aux = $aux . ', ' . $row_1[requisito_nome];

                        }
                        /* Se não houver nenhum pré-requisito, mostra os --- na tela.  */
                        if($aux == '')
                            $aux = '---';
                        else
                        {
                            /* Remove a vírgula inicial da string*/
                            $aux = substr($aux, 1);
                            /* Retira os espaços do começo e do fim*/
                            $aux = trim($aux);
                        }
                        echo "<cell>". $aux."</cell>";
                        echo "</row>";
                }
                echo "</rows>";

            break;


            case "grid_buscar_grade4":

                $result = pg_query("SELECT COUNT(*) AS count FROM Disciplina WHERE (semestre='4' AND optativa='1')");
                $row = pg_fetch_array($result);
                $count = $row['count'];
                

                $SQL = "SELECT M.id_materia AS id_materia, M.nome AS nome_materia, D.nome AS nome_disciplina, carga_horaria, num_cred FROM materia M JOIN Disciplina D ON M.id_materia=D.id_materia WHERE (semestre='4' AND optativa='1') ORDER BY M.nome ";
                $result = pg_query( $SQL ) or die("Couldn t execute query.".pq_last_error());

                if ( stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml") ) {
                        header("Content-type: application/xhtml+xml;charset=utf-8"); }
                else { header("Content-type: text/xml;charset=utf-8");
                }

                        echo "<?xml version='1.0' encoding='utf-8'?>";
                        echo "<rows>";
                        echo "<records>".$count."</records>";

                while($row = pg_fetch_array($result))
                {
                        echo "<row id='". $row[id_materia]."'>";
                        echo "<cell>". $row[nome_materia]."</cell>";
                        echo "<cell>". $row[nome_disciplina]."</cell>";
                        echo "<cell>". $row[carga_horaria]."</cell>";
                        echo "<cell>". $row[num_cred]."</cell>";
                        /* Seleciona as matérias que são pré-requisto das matérias correntes */
                        $result_1 = pg_query("SELECT requisito_nome FROM DisciplinaRequisitaDisciplina WHERE nome = '$row[nome_disciplina]'");
                        $aux='';
                        /* Colaca as matérias que são pré-requisito em um array. */
                        while($row_1 = pg_fetch_array($result_1))
                        {
                            /* Transforma as matérias que são pré-requisito em uma única string */
                            $aux = $aux . ', ' . $row_1[requisito_nome];

                        }
                        /* Se não houver nenhum pré-requisito, mostra os --- na tela.  */
                        if($aux == '')
                            $aux = '---';
                        else
                        {
                            /* Remove a vírgula inicial da string*/
                            $aux = substr($aux, 1);
                            /* Retira os espaços do começo e do fim*/
                            $aux = trim($aux);
                        }
                        echo "<cell>". $aux."</cell>";
                        echo "</row>";
                }
                echo "</rows>";

            break;


            case "grid_buscar_grade5":

                $result = pg_query("SELECT COUNT(*) AS count FROM Disciplina WHERE (semestre='5' AND optativa='1')");
                $row = pg_fetch_array($result);
                $count = $row['count'];
                
                $SQL = "SELECT M.id_materia AS id_materia, M.nome AS nome_materia, D.nome AS nome_disciplina, carga_horaria, num_cred FROM materia M JOIN Disciplina D ON M.id_materia=D.id_materia WHERE (semestre='5' AND optativa='1') ORDER BY M.nome ";
                $result = pg_query( $SQL ) or die("Couldn t execute query.".pq_last_error());

                if ( stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml") ) {
                        header("Content-type: application/xhtml+xml;charset=utf-8"); }
                else { header("Content-type: text/xml;charset=utf-8");
                }

                        echo "<?xml version='1.0' encoding='utf-8'?>";
                        echo "<rows>";
                        echo "<records>".$count."</records>";

                while($row = pg_fetch_array($result))
                {
                        echo "<row id='". $row[id_materia]."'>";
                        echo "<cell>". $row[nome_materia]."</cell>";
                        echo "<cell>". $row[nome_disciplina]."</cell>";
                        echo "<cell>". $row[carga_horaria]."</cell>";
                        echo "<cell>". $row[num_cred]."</cell>";
                        /* Seleciona as matérias que são pré-requisto das matérias correntes */
                        $result_1 = pg_query("SELECT requisito_nome FROM DisciplinaRequisitaDisciplina WHERE nome = '$row[nome_disciplina]'");
                        $aux='';
                        /* Colaca as matérias que são pré-requisito em um array. */
                        while($row_1 = pg_fetch_array($result_1))
                        {
                            /* Transforma as matérias que são pré-requisito em uma única string */
                            $aux = $aux . ', ' . $row_1[requisito_nome];

                        }
                        /* Se não houver nenhum pré-requisito, mostra os --- na tela.  */
                        if($aux == '')
                            $aux = '---';
                        else
                        {
                            /* Remove a vírgula inicial da string*/
                            $aux = substr($aux, 1);
                            /* Retira os espaços do começo e do fim*/
                            $aux = trim($aux);
                        }
                        echo "<cell>". $aux."</cell>";
                        echo "</row>";
                }
                echo "</rows>";

            break;

            case "grid_buscar_grade6":

               $page = $_REQUEST['page'];
               $limit = $_REQUEST['rows'];
               $sidx = $_REQUEST['sidx'];
               $sord = $_REQUEST['sord'];

               if(!$sidx) $sidx =1;

                $result = pg_query("SELECT COUNT(*) AS count FROM Disciplina WHERE (semestre='6' AND optativa='1')");
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


                $SQL = "SELECT M.id_materia AS id_materia, M.nome AS nome_materia, D.nome AS nome_disciplina, carga_horaria, num_cred FROM materia M JOIN Disciplina D ON M.id_materia=D.id_materia WHERE (semestre='6' AND optativa='1') ORDER BY M.nome ";
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

                while($row = pg_fetch_array($result))
                {
                        echo "<row id='". $row[id_materia]."'>";
                        echo "<cell>". $row[nome_materia]."</cell>";
                        echo "<cell>". $row[nome_disciplina]."</cell>";
                        echo "<cell>". $row[carga_horaria]."</cell>";
                        echo "<cell>". $row[num_cred]."</cell>";
                        /* Seleciona as matérias que são pré-requisto das matérias correntes */
                        $result_1 = pg_query("SELECT requisito_nome FROM DisciplinaRequisitaDisciplina WHERE nome = '$row[nome_disciplina]'");
                        $aux='';
                        /* Colaca as matérias que são pré-requisito em um array. */
                        while($row_1 = pg_fetch_array($result_1))
                        {
                            /* Transforma as matérias que são pré-requisito em uma única string */
                            $aux = $aux . ', ' . $row_1[requisito_nome];

                        }
                        /* Se não houver nenhum pré-requisito, mostra os --- na tela.  */
                        if($aux == '')
                            $aux = '---';
                        else
                        {
                            /* Remove a vírgula inicial da string*/
                            $aux = substr($aux, 1);
                            /* Retira os espaços do começo e do fim*/
                            $aux = trim($aux);
                        }
                        echo "<cell>". $aux."</cell>";
                        echo "</row>";
                }
                echo "</rows>";

            break;

            case "grid_buscar_grade7":

               $page = $_REQUEST['page'];
               $limit = $_REQUEST['rows'];
               $sidx = $_REQUEST['sidx'];
               $sord = $_REQUEST['sord'];

               if(!$sidx) $sidx =1;

                $result = pg_query("SELECT COUNT(*) AS count FROM Disciplina WHERE (semestre='7' AND optativa='1')");
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


                $SQL = "SELECT M.id_materia AS id_materia, M.nome AS nome_materia, D.nome AS nome_disciplina, carga_horaria, num_cred FROM materia M JOIN Disciplina D ON M.id_materia=D.id_materia WHERE (semestre='7' AND optativa='1') ORDER BY M.nome ";
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

                while($row = pg_fetch_array($result))
                {
                        echo "<row id='". $row[id_materia]."'>";
                        echo "<cell>". $row[nome_materia]."</cell>";
                        echo "<cell>". $row[nome_disciplina]."</cell>";
                        echo "<cell>". $row[carga_horaria]."</cell>";
                        echo "<cell>". $row[num_cred]."</cell>";
                        /* Seleciona as matérias que são pré-requisto das matérias correntes */
                        $result_1 = pg_query("SELECT requisito_nome FROM DisciplinaRequisitaDisciplina WHERE nome = '$row[nome_disciplina]'");
                        $aux='';
                        /* Colaca as matérias que são pré-requisito em um array. */
                        while($row_1 = pg_fetch_array($result_1))
                        {
                            /* Transforma as matérias que são pré-requisito em uma única string */
                            $aux = $aux . ', ' . $row_1[requisito_nome];

                        }
                        /* Se não houver nenhum pré-requisito, mostra os --- na tela.  */
                        if($aux == '')
                            $aux = '---';
                        else
                        {
                            /* Remove a vírgula inicial da string*/
                            $aux = substr($aux, 1);
                            /* Retira os espaços do começo e do fim*/
                            $aux = trim($aux);
                        }
                        echo "<cell>". $aux."</cell>";
                        echo "</row>";
                }
                echo "</rows>";

            break;

            case "grid_buscar_grade8":

               $page = $_REQUEST['page'];
               $limit = $_REQUEST['rows'];
               $sidx = $_REQUEST['sidx'];
               $sord = $_REQUEST['sord'];

               if(!$sidx) $sidx =1;

                $result = pg_query("SELECT COUNT(*) AS count FROM Disciplina WHERE (semestre='8' AND optativa='1')");
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


                $SQL = "SELECT M.id_materia AS id_materia, M.nome AS nome_materia, D.nome AS nome_disciplina, carga_horaria, num_cred FROM materia M JOIN Disciplina D ON M.id_materia=D.id_materia WHERE (semestre='8' AND optativa='1') ORDER BY M.nome ";
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

                while($row = pg_fetch_array($result))
                {
                        echo "<row id='". $row[id_materia]."'>";
                        echo "<cell>". $row[nome_materia]."</cell>";
                        echo "<cell>". $row[nome_disciplina]."</cell>";
                        echo "<cell>". $row[carga_horaria]."</cell>";
                        echo "<cell>". $row[num_cred]."</cell>";
                        /* Seleciona as matérias que são pré-requisto das matérias correntes */
                        $result_1 = pg_query("SELECT requisito_nome FROM DisciplinaRequisitaDisciplina WHERE nome = '$row[nome_disciplina]'");
                        $aux='';
                        /* Colaca as matérias que são pré-requisito em um array. */
                        while($row_1 = pg_fetch_array($result_1))
                        {
                            /* Transforma as matérias que são pré-requisito em uma única string */
                            $aux = $aux . ', ' . $row_1[requisito_nome];

                        }
                        /* Se não houver nenhum pré-requisito, mostra os --- na tela.  */
                        if($aux == '')
                            $aux = '---';
                        else
                        {
                            /* Remove a vírgula inicial da string*/
                            $aux = substr($aux, 1);
                            /* Retira os espaços do começo e do fim*/
                            $aux = trim($aux);
                        }
                        echo "<cell>". $aux."</cell>";
                        echo "</row>";
                }
                echo "</rows>";

            break;

            case "grid_buscar_grade_optativa":

               $page = $_REQUEST['page'];
               $limit = $_REQUEST['rows'];
               $sidx = $_REQUEST['sidx'];
               $sord = $_REQUEST['sord'];

               if(!$sidx) $sidx =1;

                $result = pg_query("SELECT COUNT(*) AS count FROM Disciplina WHERE optativa='2'");
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


                $SQL = "SELECT M.id_materia AS id_materia, M.nome AS nome_materia, D.nome AS nome_disciplina, semestre, carga_horaria, num_cred FROM materia M JOIN Disciplina D ON M.id_materia=D.id_materia WHERE optativa='2' ORDER BY M.nome ";
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

                while($row = pg_fetch_array($result))
                {
                        echo "<row id='". $row[id_materia]."'>";
                        echo "<cell>". $row[nome_materia]."</cell>";
                        echo "<cell>". $row[nome_disciplina]."</cell>";
                        echo "<cell>". $row[semestre]."</cell>";
                        echo "<cell>". $row[carga_horaria]."</cell>";
                        echo "<cell>". $row[num_cred]."</cell>";
                        /* Seleciona as matérias que são pré-requisto das matérias correntes */
                        $result_1 = pg_query("SELECT requisito_nome FROM DisciplinaRequisitaDisciplina WHERE nome = '$row[nome_disciplina]'");
                        $aux='';
                        /* Colaca as matérias que são pré-requisito em um array. */
                        while($row_1 = pg_fetch_array($result_1))
                        {
                            /* Transforma as matérias que são pré-requisito em uma única string */
                            $aux = $aux . ', ' . $row_1[requisito_nome];

                        }
                        /* Se não houver nenhum pré-requisito, mostra os --- na tela.  */
                        if($aux == '')
                            $aux = '---';
                        else
                        {
                            /* Remove a vírgula inicial da string*/
                            $aux = substr($aux, 1);
                            /* Retira os espaços do começo e do fim*/
                            $aux = trim($aux);
                        }
                        echo "<cell>". $aux."</cell>";
                        echo "</row>";
                }
                echo "</rows>";

            break;

        }
}

//Fecha conexão com o banco de dados
pg_close($bd);
?>
