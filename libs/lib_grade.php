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
            case "consulta_ementa":

                //Recebe as variáveis do datastring
                $request_id_disciplina = trim(($_REQUEST['id_ementa']));

                $SQL =pg_query("SELECT descricao FROM Ementa WHERE id_ementa IN (SELECT id_ementa FROM Disciplina WHERE id_disciplina='$request_id_disciplina')");
                $row = pg_fetch_array($SQL);
                $descricao = $row['descricao'];

                echo $descricao;

            break;


           case "grid_buscar_grade1":

                //Recebe as variáveis do datastring
                $request_id_grade = trim(($_REQUEST['id_grade']));

                $idGrade = $request_id_grade{strlen($request_id_grade)-1};

                //echo gettype($idGrade);
               
                /* Se nenhuma grade foi selecionada, mostra as disciplinas da grade mais atual  */
                if ($idGrade == "")
                {
                    $aux=pg_query("SELECT MAX(id_grade) AS id_grade FROM GradeCurricular");
                    $row_aux = pg_fetch_array($aux);
                    $idGrade = $row_aux['id_grade'];
                }

                               
                $result = pg_query("SELECT COUNT(*) AS count FROM Disciplina NATURAL JOIN DisciplinaGradeCurricular WHERE (semestre='1' AND optativa='1' AND id_grade = '$idGrade')");
                $row = pg_fetch_array($result);
                $count = $row['count'];

                

                $SQL = "SELECT D.id_disciplina AS id_disciplina, M.nome AS nome_materia, D.nome AS nome_disciplina, carga_horaria, num_cred FROM materia M JOIN Disciplina D ON M.id_materia=D.id_materia WHERE (semestre='1' AND optativa='1' AND D.nome IN (SELECT nome FROM DisciplinaGradeCurricular WHERE id_grade = '$idGrade')) ORDER BY M.nome";
                $result = pg_query( $SQL ) or die("A consulta não pode ser realizada.".pq_last_error());

                if ( stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml") ) {
                        header("Content-type: application/xhtml+xml;charset=utf-8"); }
                else { header("Content-type: text/xml;charset=utf-8");
                }

                        echo "<?xml version='1.0' encoding='utf-8'?>";
                        echo "<rows>";
                        echo "<records>".$count."</records>";

                while($row = pg_fetch_array($result))
                {
                        echo "<row id='". $row[id_disciplina]."'>";
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

                //Recebe as variáveis do datastring
                $request_id_grade = trim(($_REQUEST['id_grade']));

                $idGrade = $request_id_grade{strlen($request_id_grade)-1};

                //echo gettype($idGrade);

                /* Se nenhuma grade foi selecionada, mostra as disciplinas da grade mais atual  */
                if ($idGrade == "")
                {
                    $aux=pg_query("SELECT MAX(id_grade) AS id_grade FROM GradeCurricular");
                    $row_aux = pg_fetch_array($aux);
                    $idGrade = $row_aux['id_grade'];
                }

                $result = pg_query("SELECT COUNT(*) AS count FROM Disciplina NATURAL JOIN DisciplinaGradeCurricular WHERE (semestre='2' AND optativa='1' AND id_grade = '$idGrade')");
                $row = pg_fetch_array($result);
                $count = $row['count'];


                $SQL = "SELECT D.id_disciplina AS id_disciplina, M.nome AS nome_materia, D.nome AS nome_disciplina, carga_horaria, num_cred FROM materia M JOIN Disciplina D ON M.id_materia=D.id_materia WHERE (semestre='2' AND optativa='1' AND D.nome IN (SELECT nome FROM DisciplinaGradeCurricular WHERE id_grade = '$idGrade')) ORDER BY M.nome";
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
                        echo "<row id='". $row[id_disciplina]."'>";
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


                //Recebe as variáveis do datastring
                $request_id_grade = trim(($_REQUEST['id_grade']));

                $idGrade = $request_id_grade{strlen($request_id_grade)-1};

                //echo gettype($idGrade);

                /* Se nenhuma grade foi selecionada, mostra as disciplinas da grade mais atual  */
                if ($idGrade == "")
                {
                    $aux=pg_query("SELECT MAX(id_grade) AS id_grade FROM GradeCurricular");
                    $row_aux = pg_fetch_array($aux);
                    $idGrade = $row_aux['id_grade'];
                }

                $result = pg_query("SELECT COUNT(*) AS count FROM Disciplina NATURAL JOIN DisciplinaGradeCurricular WHERE (semestre='3' AND optativa='1' AND id_grade = '$idGrade')");
                $row = pg_fetch_array($result);
                $count = $row['count'];


                $SQL = "SELECT D.id_disciplina AS id_disciplina, M.nome AS nome_materia, D.nome AS nome_disciplina, carga_horaria, num_cred FROM materia M JOIN Disciplina D ON M.id_materia=D.id_materia WHERE (semestre='3' AND optativa='1' AND D.nome IN (SELECT nome FROM DisciplinaGradeCurricular WHERE id_grade = '$idGrade')) ORDER BY M.nome";
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
                        echo "<row id='". $row[id_disciplina]."'>";
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

                //Recebe as variáveis do datastring
                $request_id_grade = trim(($_REQUEST['id_grade']));

                $idGrade = $request_id_grade{strlen($request_id_grade)-1};

                //echo gettype($idGrade);

                /* Se nenhuma grade foi selecionada, mostra as disciplinas da grade mais atual  */
                if ($idGrade == "")
                {
                    $aux=pg_query("SELECT MAX(id_grade) AS id_grade FROM GradeCurricular");
                    $row_aux = pg_fetch_array($aux);
                    $idGrade = $row_aux['id_grade'];
                }

                $result = pg_query("SELECT COUNT(*) AS count FROM Disciplina NATURAL JOIN DisciplinaGradeCurricular WHERE (semestre='4' AND optativa='1' AND id_grade = '$idGrade')");
                $row = pg_fetch_array($result);
                $count = $row['count'];
                

                $SQL = "SELECT D.id_disciplina AS id_disciplina, M.nome AS nome_materia, D.nome AS nome_disciplina, carga_horaria, num_cred FROM materia M JOIN Disciplina D ON M.id_materia=D.id_materia WHERE (semestre='4' AND optativa='1' AND D.nome IN (SELECT nome FROM DisciplinaGradeCurricular WHERE id_grade = '$idGrade')) ORDER BY M.nome";
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
                        echo "<row id='". $row[id_disciplina]."'>";
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


                //Recebe as variáveis do datastring
                $request_id_grade = trim(($_REQUEST['id_grade']));

                $idGrade = $request_id_grade{strlen($request_id_grade)-1};

                //echo gettype($idGrade);

                /* Se nenhuma grade foi selecionada, mostra as disciplinas da grade mais atual  */
                if ($idGrade == "")
                {
                    $aux=pg_query("SELECT MAX(id_grade) AS id_grade FROM GradeCurricular");
                    $row_aux = pg_fetch_array($aux);
                    $idGrade = $row_aux['id_grade'];
                }

                $result = pg_query("SELECT COUNT(*) AS count FROM Disciplina NATURAL JOIN DisciplinaGradeCurricular WHERE (semestre='5' AND optativa='1' AND id_grade = '$idGrade')");
                $row = pg_fetch_array($result);
                $count = $row['count'];
                
                $SQL = "SELECT D.id_disciplina AS id_disciplina, M.nome AS nome_materia, D.nome AS nome_disciplina, carga_horaria, num_cred FROM materia M JOIN Disciplina D ON M.id_materia=D.id_materia WHERE (semestre='5' AND optativa='1' AND D.nome IN (SELECT nome FROM DisciplinaGradeCurricular WHERE id_grade = '$idGrade')) ORDER BY M.nome";
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
                        echo "<row id='". $row[id_disciplina]."'>";
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


                //Recebe as variáveis do datastring
                $request_id_grade = trim(($_REQUEST['id_grade']));

                $idGrade = $request_id_grade{strlen($request_id_grade)-1};

                //echo gettype($idGrade);

                /* Se nenhuma grade foi selecionada, mostra as disciplinas da grade mais atual  */
                if ($idGrade == "")
                {
                    $aux=pg_query("SELECT MAX(id_grade) AS id_grade FROM GradeCurricular");
                    $row_aux = pg_fetch_array($aux);
                    $idGrade = $row_aux['id_grade'];
                }

                $result = pg_query("SELECT COUNT(*) AS count FROM Disciplina NATURAL JOIN DisciplinaGradeCurricular WHERE (semestre='6' AND optativa='1' AND id_grade = '$idGrade')");
                $row = pg_fetch_array($result);
                $count = $row['count'];
                

                $SQL = "SELECT D.id_disciplina AS id_disciplina, M.nome AS nome_materia, D.nome AS nome_disciplina, carga_horaria, num_cred FROM materia M JOIN Disciplina D ON M.id_materia=D.id_materia WHERE (semestre='6' AND optativa='1' AND D.nome IN (SELECT nome FROM DisciplinaGradeCurricular WHERE id_grade = '$idGrade')) ORDER BY M.nome";
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
                        echo "<row id='". $row[id_disciplina]."'>";
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


                //Recebe as variáveis do datastring
                $request_id_grade = trim(($_REQUEST['id_grade']));

                $idGrade = $request_id_grade{strlen($request_id_grade)-1};

                //echo gettype($idGrade);

                /* Se nenhuma grade foi selecionada, mostra as disciplinas da grade mais atual  */
                if ($idGrade == "")
                {
                    $aux=pg_query("SELECT MAX(id_grade) AS id_grade FROM GradeCurricular");
                    $row_aux = pg_fetch_array($aux);
                    $idGrade = $row_aux['id_grade'];
                }

                $result = pg_query("SELECT COUNT(*) AS count FROM Disciplina NATURAL JOIN DisciplinaGradeCurricular WHERE (semestre='7' AND optativa='1' AND id_grade = '$idGrade')");
                $row = pg_fetch_array($result);
                $count = $row['count'];


                $SQL = "SELECT D.id_disciplina AS id_disciplina, M.nome AS nome_materia, D.nome AS nome_disciplina, carga_horaria, num_cred FROM materia M JOIN Disciplina D ON M.id_materia=D.id_materia WHERE (semestre='7' AND optativa='1' AND D.nome IN (SELECT nome FROM DisciplinaGradeCurricular WHERE id_grade = '$idGrade')) ORDER BY M.nome";
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
                        echo "<row id='". $row[id_disciplina]."'>";
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


                //Recebe as variáveis do datastring
                $request_id_grade = trim(($_REQUEST['id_grade']));

                $idGrade = $request_id_grade{strlen($request_id_grade)-1};

                //echo gettype($idGrade);

                /* Se nenhuma grade foi selecionada, mostra as disciplinas da grade mais atual  */
                if ($idGrade == "")
                {
                    $aux=pg_query("SELECT MAX(id_grade) AS id_grade FROM GradeCurricular");
                    $row_aux = pg_fetch_array($aux);
                    $idGrade = $row_aux['id_grade'];
                }

                $result = pg_query("SELECT COUNT(*) AS count FROM Disciplina NATURAL JOIN DisciplinaGradeCurricular WHERE (semestre='8' AND optativa='1' AND id_grade = '$idGrade')");
                $row = pg_fetch_array($result);
                $count = $row['count'];


                $SQL = "SELECT D.id_disciplina AS id_disciplina, M.nome AS nome_materia, D.nome AS nome_disciplina, carga_horaria, num_cred FROM materia M JOIN Disciplina D ON M.id_materia=D.id_materia WHERE (semestre='8' AND optativa='1' AND D.nome IN (SELECT nome FROM DisciplinaGradeCurricular WHERE id_grade = '$idGrade')) ORDER BY M.nome";
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
                        echo "<row id='". $row[id_disciplina]."'>";
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


                //Recebe as variáveis do datastring
                $request_id_grade = trim(($_REQUEST['id_grade']));

                $idGrade = $request_id_grade{strlen($request_id_grade)-1};

                //echo gettype($idGrade);

                /* Se nenhuma grade foi selecionada, mostra as disciplinas da grade mais atual  */
                if ($idGrade == "")
                {
                    $aux=pg_query("SELECT MAX(id_grade) AS id_grade FROM GradeCurricular");
                    $row_aux = pg_fetch_array($aux);
                    $idGrade = $row_aux['id_grade'];
                }

                $result = pg_query("SELECT COUNT(*) AS count FROM Disciplina NATURAL JOIN DisciplinaGradeCurricular WHERE (optativa='2' AND id_grade = '$idGrade')");
                $row = pg_fetch_array($result);
                $count = $row['count'];


                $SQL = "SELECT D.id_disciplina AS id_disciplina, M.nome AS nome_materia, D.nome AS nome_disciplina, carga_horaria, num_cred FROM materia M JOIN Disciplina D ON M.id_materia=D.id_materia WHERE (optativa='2' AND D.nome IN (SELECT nome FROM DisciplinaGradeCurricular WHERE id_grade = '$idGrade')) ORDER BY M.nome";
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
                        echo "<row id='". $row[id_disciplina]."'>";
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
