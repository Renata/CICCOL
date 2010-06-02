<?php

header("Content-Type: text/html; charset=UTF-8",true);

include "dbconfig.php";

require_once 'Smarty.class.php';


$smarty = new Smarty();

$smarty->template_dir = 'templates/';
$smarty->compile_dir = 'templates_c/';
$smarty->left_delimiter = '<!--{';
$smarty->right_delimiter = '}-->';

$smarty->compile_check = true;
$smarty->debugging = false;

//recebendo variável da ação
$request_action = trim(strtolower($_REQUEST['action']));
$request_reference = trim(strtolower($_REQUEST['reference']));

switch ($request_reference) {
    case "alocar_professor":
        switch ($request_action) {

            case "inserir_alocar_professor":


                //Recebe as variáveis do datastring
                $request_disciplina = trim($_REQUEST['disciplina']);
                $request_professor= trim($_REQUEST['professor']);
                $request_turma = trim($_REQUEST['turma']);
                $request_id = trim($_REQUEST['cad_id']);

                //Pega a matricula do professor
                $SQL = ("SELECT D.matricula FROM usuario U JOIN docente D ON U.nome = '$request_professor'  AND U.id_user = D.id_user ");
                $result =pg_query ($SQL) or die ("Nao pode selecionar a matricula do professor".pg_last_error());
                pg_query(COMMIT);

                //Armazena a matricula do professor na variavel matriculaProfessor
                $row = pg_fetch_array($result);
                $matriculaProfessor = $row[matricula];

                //Verifica se já existe professor alocado na disciplina selecionada
                $SQL2 = "SELECT doc_matricula FROM disciplina WHERE nome = '$request_disciplina' AND turma = '$request_turma' ";
                $result2 = pg_query( $SQL2 ) or die("Couldn t execute query.".pq_last_error());
                $row2 = pg_fetch_array($result2);

                //Insere no banco
                if($row2[doc_matricula] == NULL){
                    
                    if($request_turma == "3"){
                        $SQL3 = pg_query("UPDATE disciplina SET doc_matricula = '$matriculaProfessor' WHERE (nome = '$request_disciplina' AND turma = '3')") or die ("Não foi possivel inserir na tabela disciplina".pg_last_error());
                    }
                    elseif($request_turma == "1"){
                        $SQL4 = pg_query("UPDATE disciplina SET doc_matricula = '$matriculaProfessor' WHERE (nome = '$request_disciplina' AND turma = '1')") or die ("Não foi possivel inserir na tabela disciplina".pg_last_error());
                    }
                    elseif($request_turma == "2"){
                        $SQL5 = pg_query("UPDATE disciplina SET doc_matricula = '$matriculaProfessor' WHERE (nome = '$request_disciplina' AND turma = '2')") or die ("Não foi possivel inserir na tabela disciplina".pg_last_error());
                    }
                    echo "Professor alocado com sucesso";
                }
                else echo "Já existe um professor alocado nesta disciplina e turma";

                
            break;


            case "update_professor":

                //Recebe as variáveis do datastring
                $request_disciplina = trim($_REQUEST['disciplina']);
                $request_professor= trim($_REQUEST['professor']);
                $request_turma = trim($_REQUEST['turma']);
                $request_id = trim($_REQUEST['cad_id']);

                //Atualiza no banco
                $SQL = ("UPDATE usuario SET nome = '$request_nome', sobrenome = '$request_sobrenome', email= '$request_email', dt_nasc = '$request_nascimento' WHERE (id_user = $request_id)");
                $SQL1 = ("UPDATE docente SET matricula = '$request_matricula', dt_entrada = '$request_admissao' WHERE (id_user = $request_id)");

                //Verifica se foi inserido com sucesso
                $result = pg_query( $SQL ) or die("Couldn t execute query".pg_last_error());
                $result1 = pg_query( $SQL1 ) or die("Couldn t execute query".pg_last_error());

                echo "Atualização realizada com sucesso";
            break;


            case "apagar_alocar_professor":

                //Recebe as variáveis do datastring
                $request_id = trim($_REQUEST['cad_id']);

                //Apaga do banco
                $SQL = ("UPDATE disciplina SET doc_matricula = NULL WHERE (id_disciplina = $request_id)");
               
                //Verifica se foi inserido com sucesso
                $result = pg_query( $SQL ) or die("Não foi possível atualizar".pg_last_error());
                
            break;


            case "grid_buscar_alocar_professor":

                $page = $_REQUEST['page'];
                $limit = $_REQUEST['rows'];
                $sidx = $_REQUEST['sidx'];
                $sord = $_REQUEST['sord'];

                if(!$sidx) $sidx =1;
                 
                $result = pg_query("SELECT COUNT(doc_matricula) AS count FROM disciplina");
                $row = pg_fetch_array($result);
                $count = $row['count'];
               
                if( $count >0 ) {
                        $total_pages = ceil($count/$limit);
                } else {
                        $total_pages = 0;
                }

                if ($page > $total_pages) $page=$total_pages;

                $start = $limit*$page - $limit;
                if($start <0) $start = 0;


                $SQL = "SELECT DiD.nome AS nome_dis, DiD.turma, U.nome, DiD.id_disciplina from (disciplina join docente on doc_matricula=matricula) As DiD join usuario u ON DiD.id_user=U.id_user";
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
                    
                        echo "<row id='". $row[id_disciplina]."'>";
                        echo "<cell>". $row[nome_dis]."</cell>";
                        echo "<cell>". $row[nome]."</cell>";
                        echo "<cell>". $qt_turma."</cell>";
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
