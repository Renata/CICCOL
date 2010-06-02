<?php

header("Content-Type: text/html; charset=UTF-8",true);

include "dbconfig.php";

//recebendo variável da ação
$request_action = trim(strtolower($_REQUEST['action']));
$request_reference = trim(strtolower($_REQUEST['reference']));

switch ($request_reference) {
    case "requisito":
        switch ($request_action) {

            case "inserir_requisito":


                //Recebe as variáveis do datastring
                $request_disciplina = trim($_REQUEST['disciplina']);
                $request_requisito= trim($_REQUEST['requisito']);
                $request_id = trim($_REQUEST['cad_id']);
                
                $SQL = "SELECT cod_disciplina, turma FROM disciplina WHERE nome= '$request_disciplina'";
                $result = pg_query($SQL) or die ("Não foi possível consultar cod disciplina".pg_last_error());
                $row = pg_fetch_array($result);
                $cod_disc = $row[cod_disciplina];
                $turm = $row[turma];


                //DIVIDINDO A STRING
                $parte = explode("  ", $request_requisito);

                for($i=0; $i < sizeof($parte) ; $i++){

                   $SQL1 = "SELECT cod_disciplina, turma FROM disciplina WHERE nome= '$parte[$i]'";
                   $result1 = pg_query($SQL1) or die ("Não foi possível consultar cod disciplina".pg_last_error());
                   $row1 = pg_fetch_array($result1);
                   $cod = $row1[cod_disciplina];
                   $turma = $row1[turma];

                   $SQL2 = "INSERT INTO disciplinarequisitadisciplina (cod_disciplina, nome, turma, requisito_cod_disciplina, requisito_nome, requisito_turma) VALUES ('$cod_disc', '$request_disciplina', '$turm', '$cod', '$parte[$i]', '$turma') ";
                   $result2 = pg_query($SQL2) or die ("Não foi possível inserir".pg_last_error());
                  

                }
                 
                
                // Verifica se a matricula ja está cadastrada
               // $SQL =  ("SELECT matricula FROM docente WHERE (matricula = '$request_matricula')");
               // $result = pg_query($SQL) or die("Couldn t execute query".pg_last_error());
               // $row1 = pg_fetch_array($result);
                
             //  if($row1 == ""){
                    //Insere no bano
             //       $SQL1 = pg_query("INSERT INTO usuario (nome, sobrenome, email, dt_nasc, id_tpuser) VALUES ('$request_nome', '$request_sobrenome', '$request_email', '$request_nascimento', '3' )") ;
             //       pg_query(COMMIT);

                    //Pega id do usuario
            //        $SQL2 = pg_query("SELECT id_user FROM usuario WHERE (nome = '$request_nome' AND sobrenome= '$request_sobrenome')") or die("Couldn t execute query".pg_last_error());

                    //Armazena na variável idUser
             //       $row = pg_fetch_array($SQL2);
            //        $idUser = $row['id_user'];

                    //Insere no banco
          //          $SQL3 = pg_query("INSERT INTO docente (matricula, dt_entrada, id_user) VALUES ('$request_matricula', '$admissao', '$idUser')")or die("Couldn t execute query".pg_last_error());
                    
           //      echo "Cadastro realizado com sucesso";

           //    }else{
              //  echo "Matricula já cadastrada";
             //  }


            break;


            case "update_professor":

                //Recebe as variáveis do datastring
                $request_matricula = trim($_REQUEST['matricula']);
                $request_nome= trim($_REQUEST['nome']);
                $request_sobrenome = trim($_REQUEST['sobrenome']);
                $request_email = trim($_REQUEST['email']);
                $request_nascimento = trim($_REQUEST['nascimento']);
                $request_admissao = trim($_REQUEST['admissao']);
                $request_id = trim($_REQUEST['cad_id']);

                //Atualiza no banco
                $SQL = ("UPDATE usuario SET nome = '$request_nome', sobrenome = '$request_sobrenome', email= '$request_email', dt_nasc = '$request_nascimento' WHERE (id_user = $request_id)");
                $SQL1 = ("UPDATE docente SET matricula = '$request_matricula', dt_entrada = '$request_admissao' WHERE (id_user = $request_id)");

                //Verifica se foi inserido com sucesso
                $result = pg_query( $SQL ) or die("Couldn t execute query".pg_last_error());
                $result1 = pg_query( $SQL1 ) or die("Couldn t execute query".pg_last_error());

                echo "Atualização realizada com sucesso";
            break;


            case "apagar_requisito":

                //Recebe as variáveis do datastring
                $request_id = trim($_REQUEST['cad_id']);

                //Apaga do banco
                $SQL = ("DELETE FROM disciplinarequisitadisciplina WHERE (id_disciplinarequisitadisciplina = $request_id)");
               
                //Verifica se foi deletado com sucesso
                $result = pg_query( $SQL ) or die("Couldn t execute query".pg_last_error());
                
            break;


            case "grid_buscar_requisito":

                $page = $_REQUEST['page'];
                $limit = $_REQUEST['rows'];
                $sidx = $_REQUEST['sidx'];
                $sord = $_REQUEST['sord'];

                if(!$sidx) $sidx =1;

                $result = pg_query("SELECT COUNT(*) AS count FROM disciplinarequisitadisciplina");
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


                $SQL = "SELECT nome, requisito_nome FROM disciplinarequisitadisciplina";
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
                        echo "<cell>". $row[nome]."</cell>";
                        echo "<cell>". $row[requisito_nome]."</cell>";
                        echo "</row>";
                }
                echo "</rows>";

            break;
        }

        }

//Fecha conexão com o banco de dados
pg_close($bd);
?>
