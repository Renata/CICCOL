<?php

header("Content-Type: text/html; charset=UTF-8",true);

include "dbconfig.php";

//recebendo variável da ação
$request_action = trim(strtolower($_REQUEST['action']));
$request_reference = trim(strtolower($_REQUEST['reference']));

switch ($request_reference) {
    case "professor":
        switch ($request_action) {

            case "inserir_professor":

                //Recebe as variáveis do datastring
                $request_matricula = trim($_REQUEST['matricula']);
                $request_cpf = trim($_REQUEST['cpf']);
                $request_nome = trim($_REQUEST['nome']);
                $request_sobrenome = trim($_REQUEST['sobrenome']);
                $request_email = trim($_REQUEST['email']);
                $request_nascimento = trim($_REQUEST['nascimento']);
                $request_id = trim($_REQUEST['cad_id']);


                //Verifica se está cadastrado na tabela docente
                $SQL = pg_query("SELECT id_user FROM adminmoderador WHERE matricula = '$request_matricula'") or die("Não foi possível fazer a consulta na tabela docente".pg_last_error());
                $row = pg_fetch_array($SQL);
                $id = $row[id_user];

                if($row == ""){

                    //Insere na tabela usuario
                    $SQL1 = pg_query("INSERT INTO usuario (nome, sobrenome, email, dt_nasc, cpf) VALUES ('$request_nome', '$request_sobrenome', '$request_email', '$request_nascimento', '$request_cpf')") or die("Não foi possível inserir na tabela adminmoderador".pg_last_error());
                    pg_query(COMMIT);

                    //Pega id do usuario
                    $SQL2 = pg_query("SELECT id_user FROM usuario WHERE (nome = '$request_nome' AND sobrenome= '$request_sobrenome')") or die("Não foi possível fazer a consulta na tabela usuario".pg_last_error());

                    //Armazena na variável idUser
                    $row2 = pg_fetch_array($SQL2);
                    $idUser = $row2['id_user'];

                    //Insere no banco
                    $SQL3 = pg_query("INSERT INTO docente (matricula, id_user) VALUES ('$request_matricula', '$idUser')") or die("Não foi possível inserir na tabela adminmoderador".pg_last_error());
                    $SQL4 = pg_query("INSERT INTO autenticacao (identificador, id_user, id_tpuser) VALUES ('$request_matricula', '$idUser', '4')") or die("Não foi possível inserir na tabela adminmoderador".pg_last_error());


                }
                else{
                    $SQL5 = pg_query("INSERT INTO docente (matricula, id_user) VALUES ('$request_matricula', '$id')") or die("Não foi possível inserir na tabela adminmoderador".pg_last_error());
                    $SQL6 = pg_query("INSERT INTO autenticacao (identificador, id_user, id_tpuser) VALUES ('$request_matricula', '$id', '4')") or die("Não foi possível inserir na tabela adminmoderador".pg_last_error());


               }

            break;


            case "update_professor":

                //Recebe as variáveis do datastring
                $request_matricula = trim($_REQUEST['matricula']);
                $request_cpf = trim($_REQUEST['cpf']);
                $request_nome = trim($_REQUEST['nome']);
                $request_sobrenome = trim($_REQUEST['sobrenome']);
                $request_email = trim($_REQUEST['email']);
                $request_nascimento = trim($_REQUEST['nascimento']);
                $request_id = trim($_REQUEST['cad_id']);

                //Verifica se o usuario está cadastrado na tabela docente
                $SQL = "SELECT id_user FROM adminmoderador WHERE id_user = '$request_id'";
                $result = pg_query( $SQL ) or die("Nao pode selecionar na tabela docente".pg_last_error());


              if($result == ""){

                   //Atualiza no banco
                   $SQL = pg_query("UPDATE usuario SET nome = '$request_nome', sobrenome = '$request_sobrenome', email= '$request_email', dt_nasc = '$request_nascimento', cpf= '$request_cpf' WHERE (id_user = $request_id)") or die("Nao pode atualizar usuario".pg_last_error());
                   $SQL1 = pg_query("UPDATE docente SET matricula = '$request_matricula' WHERE (id_user = $request_id)") or die("Nao pode atualizar adminmoderador".pg_last_error());
                   $SQL2 = pg_query("UPDATE autenticacao SET identificador = '$request_matricula' WHERE (id_user = $request_id)") or die("Nao pode atualizar autenticacao".pg_last_error());

               }
               else{

                   $SQL = pg_query("UPDATE usuario SET nome = '$request_nome', sobrenome = '$request_sobrenome', email= '$request_email', dt_nasc = '$request_nascimento', cpf= '$request_cpf' WHERE (id_user = $request_id)") or die("Nao pode atualizar usuario".pg_last_error());
                   $SQL1 = pg_query("UPDATE adminmoderador SET matricula = '$request_matricula' WHERE (id_user = $request_id)") or die("Nao pode atualizar adminmoderador".pg_last_error());
                   $SQL2 = pg_query("UPDATE docente SET matricula = '$request_matricula' WHERE (id_user = $request_id)") or die("Nao pode atualizar docente".pg_last_error());
                   $SQL3 = pg_query("UPDATE autenticacao SET identificador = '$request_matricula' WHERE (id_user = $request_id AND id_tpuser = '4')") or die("Nao pode atualizar autenticacao".pg_last_error());

               }


            break;


            case "apagar_professor":

                //Recebe as variáveis do datastring
                $request_id = trim($_REQUEST['cad_id']);
                $request_cpf = trim($_REQUEST['cpf']);

                //Verifica se está cadastrado na tabela docente
                $SQL = pg_query("SELECT id_user FROM adminmoderador WHERE id_user = '$request_id'") or die("Não foi possível fazer a consulta na tabela docente".pg_last_error());
                $row = pg_fetch_array($SQL);

                if($row == ""){
                    //Apaga da tabela usuario e adminmoderador
                    $SQL1 = pg_query("DELETE FROM docente WHERE (id_user = $request_id)");
                    $SQL2 = pg_query("DELETE FROM usuario WHERE (id_user = $request_id)");
                    $SQL3 = pg_query("DELETE FROM autenticacao WHERE (id_user = $request_id)");


                }
                else{
                    //Apaga da tabela adminmoderador
                    $SQL3 = pg_query("DELETE FROM docente WHERE (id_user = $request_id)");
                    $SQL3 = pg_query("DELETE FROM autenticacao WHERE (id_user = $request_id AND id_tpuser = '4' )");

                }

            break;


            case "grid_buscar_professor":


                $page = $_REQUEST['page'];
                $limit = $_REQUEST['rows'];
                $sidx = $_REQUEST['sidx'];
                $sord = $_REQUEST['sord'];

                if(!$sidx) $sidx =1;

                $result = pg_query("SELECT COUNT(*) AS count FROM autenticacao WHERE id_tpuser = 4");
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


               $SQL = "SELECT U.id_user, U.cpf, A.matricula, U.nome, U.email, U.sobrenome, DATE(U.dt_nasc) as dtnasc  FROM usuario U, docente A, autenticacao L WHERE U.id_user = A.id_user AND U.id_user = L.id_user AND id_tpuser = 4 ORDER BY nome";
               $result = pg_query( $SQL ) or die("Couldn t execute query.");


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
                        echo "<cell>". $row[matricula]."</cell>";
                        echo "<cell>". $row[nome]."</cell>";
                        echo "<cell>". $row[sobrenome]."</cell>";
                        echo "<cell>". $row[email]."</cell>";
                        echo "<cell>". $row[cpf]."</cell>";
                        echo "<cell>". $row[dtnasc]."</cell>";
                        echo "</row>";
                }
                echo "</rows>";

            break;

            case "buscar_professor":


                //Recebe as variáveis do datastring
                $request_matricula = trim($_REQUEST['matricula']);
                $request_id = trim($_REQUEST['cad_id']);

                //Verifica se já existe algum cadastro na tebela docente e na tabela adminmoderador
                $SQL3 = pg_query("SELECT matricula FROM docente WHERE matricula = '$request_matricula' ") or die ("Não foi possível consultar a tebela disciplina".pg_last_error());
                $row1 = pg_fetch_array($SQL3);

                    if(!$row1[matricula]){

                        $SQL4 = pg_query("SELECT matricula FROM adminmoderador WHERE matricula = '$request_matricula' ") or die ("Não foi possível consultar a tebela disciplina".pg_last_error());
                        $row4 = pg_fetch_array($SQL4);

                        if(!$row4[matricula]){
                            $retorno = '1';
                        }
                        else{
                           $retorno = '2';
                        }
                    }
                    else{
                        $retorno = '0';
                    }

                if($retorno == '0'){
                    echo "0";
                }
                elseif($retorno == '1'){
                     echo "cadastrar";
                }
                elseif($retorno == '2'){
                    $SQL = pg_query("select U.nome, U.cpf, U.sobrenome, U.email, U.dt_nasc FROM usuario U JOIN adminmoderador A on A.matricula = '$row4[matricula]' AND U.id_user = A.id_user") or die ("Não foi possível consultar a tebela usuario".pg_last_error());
                    $row = pg_fetch_array($SQL);

                    $dados = array(
                        0 => $row[nome],
                        1 => $row[sobrenome],
                        2 => $row[email],
                        3 => $row[dt_nasc],
                        4 => $row[cpf]
                   );
                    $string_array  = implode("|", $dados);
                    echo $string_array;
                }


            break;
        }

        }

//Fecha conexão com o banco de dados
pg_close($bd);
?>
