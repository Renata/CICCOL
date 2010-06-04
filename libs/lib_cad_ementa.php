<?php

header("Content-Type: text/html; charset=UTF-8",true);

include "dbconfig.php";

//recebendo variável da ação
$request_action = trim(strtolower($_REQUEST['action']));
$request_reference = trim(strtolower($_REQUEST['reference']));

switch ($request_reference) {
    case "ementa":
        switch ($request_action) {

            case "inserir_ementa":


                //Recebe as variáveis do datastring
                $request_disciplina = trim($_REQUEST['disciplina']);
                $request_ementa = trim($_REQUEST['ementa']);
              
               
                    //Insere na tabela ementa
                    $SQL1 = pg_query("INSERT INTO ementa (descricao) VALUES ('$request_ementa')")or die("Nao pode inserir em ementa".pg_last_error());
                    pg_query(COMMIT);

                    //Pega id da ementa
                    $SQL2 = pg_query("SELECT id_ementa FROM ementa WHERE (descricao = '$request_ementa')") or die("Nao foi possivel consultar ementa".pg_last_error());
               
                    //Armazena na variável idEmenta
                    $row = pg_fetch_array($SQL2);
                    $idEmenta = $row['id_ementa'];

                    echo $idEmenta;
                    
                    //Insere na tabela disciplina
                    $SQL3 = pg_query("UPDATE disciplina SET id_ementa = '$idEmenta' WHERE nome = '$request_disciplina'")or die("Nao pode inserir em disciplina".pg_last_error());
                    
                    echo "Ementa Cadastrada com Sucesso!";
            break;


            case "update_ementa":

                //Recebe as variáveis do datastring
                $requeste_ementa = trim($_REQUEST['ementa']);
                $request_id = trim($_REQUEST['cad_id']);

                //Atualiza no banco
                $SQL = ("UPDATE ementa SET descricao = '$requeste_ementa' WHERE (id_ementa = $request_id)");
                  //Verifica se foi inserido com sucesso
                $result = pg_query( $SQL ) or die("Couldn t execute query".pg_last_error());
               
                echo "Atualização realizada com sucesso";
            break;


            case "apagar_aluno":

                //Recebe as variáveis do datastring
                $request_id = trim($_REQUEST['cad_id']);

                //Apaga do banco
                $SQL = ("DELETE FROM usuario WHERE (id_user = $request_id)");
               
                //Verifica se foi inserido com sucesso
                $result = pg_query( $SQL ) or die("Couldn t execute query".pg_last_error());
                
            break;


            case "grid_buscar_ementa":

                $page = $_REQUEST['page'];
                $limit = $_REQUEST['rows'];
                $sidx = $_REQUEST['sidx'];
                $sord = $_REQUEST['sord'];

                if(!$sidx) $sidx =1;

                $result = pg_query("SELECT COUNT(*) AS count FROM ementa");
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


                $SQL = "select E.id_ementa, E.descricao, D.nome FROM disciplina D JOIN ementa E ON D.id_ementa = E.id_ementa";
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

                         
                        echo "<row id='". $row[id_ementa]."'>";
                        echo "<cell>". "" ."</cell>";
                        echo "<cell>". $row[nome]."</cell>";
                        echo "<cell>". $row[descricao]."</cell>";
                        echo "</row>";
                }
                echo "</rows>";

            break;

             case "buscar_aluno":


                //Recebe as variáveis do datastring
                $request_matricula = trim($_REQUEST['matricula']);
                $request_id = trim($_REQUEST['cad_id']);

                //Verifica se já existe algum cadastro na tebela docente e na tabela adminmoderador
                $SQL =  ("SELECT matricula FROM aluno WHERE (matricula = '$request_matricula')");
                $result = pg_query($SQL) or die("Couldn t execute query".pg_last_error());
                $row1 = pg_fetch_array($result);

                if(!$row1){
                    echo "cadastrar";
                }
                else{
                    echo '0';
                }


            break;


        }

        }

//Fecha conexão com o banco de dados
pg_close($bd);
?>
