<?php

header("Content-Type: text/html; charset=UTF-8",true);

include "dbconfig.php";

//recebendo variável da ação
$request_action = trim(strtolower($_REQUEST['action']));
$request_reference = trim(strtolower($_REQUEST['reference']));

switch ($request_reference)
{
    case "curriculo":
        switch ($request_action)
        {

            case "inserir_curriculo":


                //Recebe as variáveis do datastring
                $request_perfil_profissional = trim(($_REQUEST['perfil_profissional']));
                $request_ultimo_emprego = trim(($_REQUEST['ultimo_emprego']));
                $request_cargo = trim($_REQUEST['cargo']);
                $request_id_curriculo = trim($_REQUEST['id_curriculo']);

                //Busca id do cargo
                $SQL_Cargo = ("SELECT id_cargo FROM Cargo WHERE descricao='$request_cargo'");
                $result_cargo = pg_query( $SQL_Cargo ) or die("Não foi possível encontrar o cargo.".pg_last_error());
                $rowCargo = pg_fetch_array($result_cargo);
                $idCargo = $rowCargo[id_cargo];

                //Busca o número de matrícula do Docente
                $SQL_Doc = ("SELECT matricula FROM Docente WHERE matricula IN (SELECT identificador FROM Autenticacao WHERE identificador='12310488')");
                $result_doc = pg_query( $SQL_Doc ) or die("Não foi possível encontrar a matricula do Docente.".pg_last_error());
                $rowDoc = pg_fetch_array($result_doc);
                $doc_matricula = $rowDoc[matricula];

                if($idCargo=="")
                {
                    //Insere no banco
                    $SQL = ("INSERT INTO Curriculo (ultimo_emprego, perfil_profissional, doc_matricula, id_cargo_atual) VALUES ('$request_ultimo_emprego', '$request_perfil_profissional', '$doc_matricula', NULL)");

                    //Verifica se foi inserido com sucesso
                    $result = pg_query( $SQL ) or die("Não foi possível cadastrar currículo".pg_last_error());
                    
                    echo "Cadastro realizado com sucesso";
                }
                else
                {
                    //Insere no banco
                    $SQL = ("INSERT INTO Curriculo (ultimo_emprego, perfil_profissional, doc_matricula, id_cargo_atual) VALUES ('$request_ultimo_emprego', '$request_perfil_profissional', '$doc_matricula', '$idCargo')");

                    //Verifica se foi inserido com sucesso
                    $result = pg_query( $SQL ) or die("Não foi possível cadastrar currículo".pg_last_error());

                    echo "Cadastro realizado com sucesso";

                }
                           
                
            break;


            case "update_curriculo":

                //Recebe as variáveis do datastring
                $request_perfil_profissional = trim(($_REQUEST['perfil_profissional']));
                $request_ultimo_emprego = trim(($_REQUEST['ultimo_emprego']));
                $request_cargo = trim($_REQUEST['cargo']);
                $request_id_curriculo = trim($_REQUEST['id_curriculo']);


                //Busca id do cargo
                $SQL_Cargo = ("SELECT id_cargo FROM Cargo WHERE descricao='$request_cargo'");
                $result_cargo = pg_query( $SQL_Cargo ) or die("Não foi possível encontrar o cargo.".pg_last_error());
                $rowCargo = pg_fetch_array($result_cargo);
                $idCargo = $rowCargo[id_cargo];

                //Busca o número de matrícula do Docente
                $SQL_Doc = ("SELECT matricula FROM Docente WHERE matricula IN (SELECT identificador FROM Autenticacao WHERE identificador='12310488')");
                $result_doc = pg_query( $SQL_Doc ) or die("Não foi possível encontrar a matricula do Docente.".pg_last_error());
                $rowDoc = pg_fetch_array($result_doc);
                $doc_matricula = $rowDoc[matricula];

                if($idCargo=="")
                {
                    //Atualiza no banco
                    $SQL = ("UPDATE Curriculo SET perfil_profissional = '$request_perfil_profissional', ultimo_emprego = '$request_ultimo_emprego', id_cargo_atual = NULL, doc_matricula = '$doc_matricula' WHERE (id_curriculo = $request_id_curriculo)");

                    //Verifica se foi inserido com sucesso
                    $result = pg_query( $SQL ) or die("Couldn t execute query".pg_last_error());
                     echo "Atualização realizada com sucesso";
                }
                else
                {
                    //Atualiza no banco
                    $SQL = ("UPDATE Curriculo SET perfil_profissional = '$request_perfil_profissional', ultimo_emprego = '$request_ultimo_emprego', id_cargo_atual = '$idCargo', doc_matricula = '$doc_matricula' WHERE (id_curriculo = $request_id_curriculo)");

                    //Verifica se foi inserido com sucesso
                    $result = pg_query( $SQL ) or die("Couldn t execute query".pg_last_error());
                     echo "Atualização realizada com sucesso";
                }
 
            break;


            case "apagar_curriculo":

                //Recebe as variáveis do datastring
                $request_id = trim($_REQUEST['id_curriculo']);

                //Insere no bano
                $SQL = ("DELETE FROM Curriculo WHERE (id_curriculo = $request_id)");

                //Verifica se foi inserido com sucesso
                $result = pg_query( $SQL ) or die("Couldn t execute query".pg_last_error());
                
            break;

        }

}

//Fecha conexão com o banco de dados
pg_close($bd);
?>
