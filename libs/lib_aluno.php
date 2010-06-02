<?php

header("Content-Type: text/html; charset=UTF-8",true);

include "dbconfig.php";

//recebendo variável da ação
$request_action = trim(strtolower($_REQUEST['action']));
$request_reference = trim(strtolower($_REQUEST['reference']));

switch ($request_reference) {
    case "aluno":
        switch ($request_action) {

            case "inserir_aluno":


                //Recebe as variáveis do datastring
                $request_matricula = trim($_REQUEST['matricula']);
                $request_cpf = trim($_REQUEST['cpf']);
                $request_nome= trim($_REQUEST['nome']);
                $request_sobrenome = trim($_REQUEST['sobrenome']);
                $request_email = trim($_REQUEST['email']);
                $request_nascimento = trim($_REQUEST['nascimento']);
                $request_periodo = trim($_REQUEST['periodo']);
                $request_situacao = trim($_REQUEST['situacao']);
                $request_entrada = trim($_REQUEST['entrada']);
                $request_id = trim($_REQUEST['cad_id']);

                    //Insere no bano
                    $SQL1 = pg_query("INSERT INTO usuario (nome, sobrenome, email, dt_nasc, cpf) VALUES ('$request_nome', '$request_sobrenome', '$request_email', '$request_nascimento', '$request_cpf')")or die("Nao pode inserir em usuario".pg_last_error());
                    pg_query(COMMIT);

                    //Pega id do usuario
                    $SQL2 = pg_query("SELECT id_user FROM usuario WHERE (nome = '$request_nome' AND sobrenome= '$request_sobrenome')") or die("Couldn t execute query".pg_last_error());
               
                    //Armazena na variável idUser
                    $row = pg_fetch_array($SQL2);
                    $idUser = $row['id_user'];
                    
                    //Insere no banco
                    $SQL3 = pg_query("INSERT INTO aluno (matricula, ano_entrada, semestre_entrada,  situacao, id_user) VALUES ('$request_matricula', '$request_entrada', '$request_periodo', '$request_situacao', '$idUser')")or die("Nao pode inserir em aluno".pg_last_error());
                    $SQL3 = pg_query("INSERT INTO autenticacao (identificador, id_user, id_tpuser) VALUES ('$request_matricula', '$idUser', '5')")or die("Nao pode inserir em aluno".pg_last_error());

                    echo "Aluno cadastrado com sucesso!";
            break;


            case "update_aluno":

                //Recebe as variáveis do datastring
                $request_matricula = trim($_REQUEST['matricula']);
                $request_nome= trim($_REQUEST['nome']);
                $request_sobrenome = trim($_REQUEST['sobrenome']);
                $request_email = trim($_REQUEST['email']);
                $request_nascimento = trim($_REQUEST['nascimento']);
                $request_periodo = trim($_REQUEST['periodo']);
                $request_situacao = trim($_REQUEST['situacao']);
                $request_entrada = trim($_REQUEST['entrada']);
                $request_id = trim($_REQUEST['cad_id']);

                //Atualiza no banco
                $SQL = ("UPDATE usuario SET nome = '$request_nome', sobrenome = '$request_sobrenome', email= '$request_email', dt_nasc = '$request_nascimento' WHERE (id_user = $request_id)");
                $SQL1 = ("UPDATE aluno SET matricula = '$request_matricula', semestre_entrada = '$request_periodo', situacao = '$request_situacao' , ano_entrada = '$request_entrada' WHERE (id_user = $request_id)");

                //Verifica se foi inserido com sucesso
                $result = pg_query( $SQL ) or die("Couldn t execute query".pg_last_error());
                $result1 = pg_query( $SQL1 ) or die("Couldn t execute query".pg_last_error());

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


            case "grid_buscar_aluno":

                $page = $_REQUEST['page'];
                $limit = $_REQUEST['rows'];
                $sidx = $_REQUEST['sidx'];
                $sord = $_REQUEST['sord'];

                if(!$sidx) $sidx =1;

                $result = pg_query("SELECT COUNT(*) AS count FROM aluno");
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


                $SQL = "SELECT U.id_user, A.matricula, U.nome, U.cpf, U.email, U.sobrenome, DATE(U.dt_nasc) as data, A.situacao, A.semestre_entrada, A.semestre, A.ano_entrada FROM usuario U JOIN aluno A ON A.id_user = U.id_user ORDER BY nome";
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
                        $sit = $row[situacao];

                        if($sit == "1"){
                            $sit = "Cursando";
                        }elseif($sit == "2"){
                            $sit = "Matricula Trancada";
                        }

                        echo "<row id='". $row[id_user]."'>";
                        echo "<cell>". $row[matricula]."</cell>";
                        echo "<cell>". $row[nome]."</cell>";
                        echo "<cell>". $row[sobrenome]."</cell>";
                        echo "<cell>". $row[email]."</cell>";
                        echo "<cell>". $row[cpf]."</cell>";
                        echo "<cell>". $row[data]."</cell>";
                        echo "<cell>". $row[ano_entrada]."</cell>";
                        echo "<cell>". $row[semestre_entrada]."</cell>";
                        echo "<cell>". $row[semestre]."</cell>";
                        echo "<cell>". $sit."</cell>";
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
