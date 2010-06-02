<?php

header("Content-Type: text/html; charset=UTF-8",true);

include "dbconfig.php";

//recebendo variável da ação
$request_action = trim(strtolower($_REQUEST['action']));
$request_reference = trim(strtolower($_REQUEST['reference']));

switch ($request_reference) {
    case "sala":
        switch ($request_action) {

            case "inserir_sala":


                //Recebe as variáveis do datastring
                $request_numero = trim($_REQUEST['numero']);
                $request_localizacao= trim($_REQUEST['localizacao']);
                $request_id = trim($_REQUEST['cad_id']);

                // Verifica se a matricula ja está cadastrada
               // $SQL =  ("SELECT matricula FROM aluno WHERE (matricula = '$request_matricula')");
               // $result = pg_query($SQL) or die("Couldn t execute query".pg_last_error());
               // $row1 = pg_fetch_array($result);
               
             //  if($row1 == ""){
                    //Insere no bano
                    $SQL1 = pg_query("INSERT INTO sala (descricao, localizacao, classificacao) VALUES (' $request_numero', '$request_localizacao', '1' )") or die("Couldn t execute query".pg_last_error());
                   // pg_query(COMMIT);

                    //Pega id do usuario
                    //$SQL2 = pg_query("SELECT id_user FROM usuario WHERE (nome = '$request_nome' AND sobrenome= '$request_sobrenome' AND email= '$request_email' )") or die("Couldn t execute query".pg_last_error());
               
                    //Armazena na variável idUser
                    //$row = pg_fetch_array($SQL2);
                   // $idUser = $row['id_user'];
                    
                    //Insere no banco
                   // $SQL3 = pg_query("INSERT INTO aluno (matricula, semestre, ano_entrada, situacao, id_user) VALUES ('$request_matricula', '$request_semestre', '$request_entrada', '$request_situacao', '$idUser')")or die("Nao pode inserir em aluno".pg_last_error());
                    
             //    echo "Cadastro realizado com sucesso";

             //  }else{
             //   echo "Matricula já cadastrada";
            //   }


            break;


            case "update_sala":

                //Recebe as variáveis do datastring
                $request_numero = trim($_REQUEST['numero']);
                $request_localizacao= trim($_REQUEST['localizacao']);
                $request_id = trim($_REQUEST['cad_id']);


                //Atualiza no banco
                $SQL = ("UPDATE sala SET descricao = '$request_numero', localizacao = '$request_localizacao' WHERE (id_sala = $request_id)");
                
                //Verifica se foi atualizado com sucesso
                $result = pg_query( $SQL ) or die("Couldn t execute query".pg_last_error());
                
                
            break;


            case "apagar_sala":

                //Recebe as variáveis do datastring
                $request_id = trim($_REQUEST['cad_id']);

                //Apaga do banco
                $SQL = ("DELETE FROM sala WHERE (id_sala = $request_id)");
               
                //Verifica se foi inserido com sucesso
                $result = pg_query( $SQL ) or die("Couldn t execute query".pg_last_error());
                
            break;


            case "grid_buscar_sala":

                $page = $_REQUEST['page'];
                $limit = $_REQUEST['rows'];
                $sidx = $_REQUEST['sidx'];
                $sord = $_REQUEST['sord'];

                if(!$sidx) $sidx =1;

                $result = pg_query("SELECT COUNT(*) AS count FROM sala WHERE classificacao = '1'");
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


                $SQL = "SELECT id_sala, descricao, localizacao FROM sala WHERE classificacao = '1' ORDER BY descricao";
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

                        
                        echo "<row id='". $row[id_user]."'>";
                        echo "<cell>". $row[descricao]."</cell>";
                        echo "<cell>". $row[localizacao]."</cell>";
                        echo "</row>";
                }
                echo "</rows>";

            break;
        }

        }

//Fecha conexão com o banco de dados
pg_close($bd);
?>
