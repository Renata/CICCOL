<?php

header("Content-Type: text/html; charset=UTF-8",true);

include "dbconfig.php";

//recebendo variável da ação
$request_action = trim(strtolower($_REQUEST['action']));
$request_reference = trim(strtolower($_REQUEST['reference']));

switch ($request_reference) {
    case "projeto":
        switch ($request_action) {

            case "inserir_projeto":

                //Recebe as variáveis do datastring
                $request_descricao = trim(($_REQUEST['descricao']));
                $request_id = trim($_REQUEST['cad_id']);

                //Pega o id do currículo do docente corrente
                $SQL_Cur = ("SELECT id_curriculo FROM Curriculo WHERE doc_matricula='12310488'");
                $result_Cur = pg_query( $SQL_Cur ) or die("Não foi possível encontrar o número de matrícula do docente corrente.".pg_last_error());
                $rowCur = pg_fetch_array($result_Cur);
                $idCurriculo = $rowCur[id_curriculo];

                if ($idCurriculo=="")
                {
                    //Insere no banco
                    $SQL = ("INSERT INTO ProjetosPesquisa (descricao, id_curriculo, doc_matricula) VALUES ('$request_descricao', NULL, '12310488')");

                    //Verifica se foi inserido com sucesso
                    $result = pg_query( $SQL ) or die("O cadastro do projeto não foi realizado com sucesso!".pg_last_error());
                }
                else
                {
                    //Insere no banco
                    $SQL = ("INSERT INTO ProjetosPesquisa (descricao, id_curriculo, doc_matricula) VALUES ('$request_descricao', '$idCurriculo', '12310488')");

                    //Verifica se foi inserido com sucesso
                    $result = pg_query( $SQL ) or die("O cadastro do projeto não foi realizado com sucesso!".pg_last_error());

                }
                echo "Cadastro realizado com sucesso";
             
            break;


            case "update_projeto":

                //Recebe as variáveis do datastring
                $request_descricao = trim(($_REQUEST['descricao']));
                $request_id = trim($_REQUEST['cad_id']);

                //Pega o id do currículo do docente corrente
                $SQL_Cur = ("SELECT id_curriculo FROM Curriculo WHERE doc_matricula='12310488'");
                $result_Cur = pg_query( $SQL_Cur ) or die("Não foi possível encontrar o número de matrícula do docente corrente.".pg_last_error());
                $rowCur = pg_fetch_array($result_Cur);
                $idCurriculo = $rowCur[id_curriculo];

                if ($idCurriculo=="")
                {
                    //Atualiza no banco
                    $SQL = ("UPDATE ProjetosPesquisa SET descricao = '$request_descricao', id_curriculo = NULL WHERE (id_projeto = '$request_id')");

                    //Verifica se foi inserido com sucesso
                    $result = pg_query( $SQL ) or die("A ataualização do projeto não foi realizada com sucesso!".pg_last_error());
                }
                else
                {
                    //Atualiza no banco
                    $SQL = ("UPDATE ProjetosPesquisa SET descricao = '$request_descricao', id_curriculo = '$idCurriculo' WHERE (id_projeto = '$request_id')");

                    //Verifica se foi inserido com sucesso
                    $result = pg_query( $SQL ) or die("A ataualização do projeto não foi realizada com sucesso!".pg_last_error());

                }
                echo "Atualização realizada com sucesso";
                 
            break;


            case "apagar_projeto":

                //Recebe as variáveis do datastring
                $request_id = trim($_REQUEST['cad_id']);

                //Apaga do banco
                $SQL = ("DELETE FROM ProjetosPesquisa WHERE (id_projeto = $request_id)");

                //Verifica se foi removido com sucesso
                $result = pg_query( $SQL ) or die("Projeto removido com sucesso.".pg_last_error());
                
            break;


            case "grid_buscar_projeto":

                $page = $_REQUEST['page'];
                $limit = $_REQUEST['rows'];
                $sidx = $_REQUEST['sidx'];
                $sord = $_REQUEST['sord'];
               
                if(!$sidx) $sidx =1;

                $result = pg_query("SELECT COUNT(*) AS count FROM ProjetosPesquisa WHERE (doc_matricula='12310488')");
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


                $SQL = "SELECT id_projeto, descricao FROM ProjetosPesquisa WHERE (doc_matricula='12310488') ORDER BY id_projeto ";
                $result = pg_query( $SQL ) or die("A consulta aos projetos de pesquisa do docente não pode ser realizada.".pq_last_error());
                        
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
                        echo "<row id='". $row[id_projeto]."'>";
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
