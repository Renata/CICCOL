<?php

header("Content-Type: text/html; charset=UTF-8",true);

include "dbconfig.php";
@session_start();

//recebendo variável da ação
$request_action = trim(strtolower($_REQUEST['action']));
$request_reference = trim(strtolower($_REQUEST['reference']));

switch ($request_reference) {
    case "celular":
        switch ($request_action) {

            case "inserir_celular":


                //Recebe as variáveis do datastring
                $request_ddd = trim($_REQUEST['ddd']);
                $request_numero= trim($_REQUEST['numero']);
                $request_operadora = trim($_REQUEST['operadora']);
                $idUsuario = $_SESSION['idUsuario'];
                
                // Verifica se já existe celular cadastrado
                $SQL =  ("SELECT * FROM telefone");
                $result = pg_query($SQL) or die("Couldn t execute query".pg_last_error());
                $row1 = pg_fetch_array($result);
               
               if($row1 == ""){
                    //Insere no bano
                    $SQL1 = pg_query("INSERT INTO telefone (ddd, telefone, operadora, id_user) VALUES ('$request_ddd', '$request_numero', '$request_operadora', '$idUsuario')") or die("Não foi possível inserir telefone".pg_last_error());
                    

                    echo "Cadastro realizado com sucesso";

               }else{
                echo "Você só pode cadastar um número de celular";
               }


            break;


            case "update_celular":

                //Recebe as variáveis do datastring
                $request_ddd = trim($_REQUEST['ddd']);
                $request_numero= trim($_REQUEST['numero']);
                $request_operadora = trim($_REQUEST['operadora']);
                $request_id = trim($_REQUEST['cad_id']);

                //Atualiza no banco
                $SQL = ("UPDATE telefone SET ddd = '$request_ddd', telefone = '$request_numero', operadora= '$request_operadora'WHERE (id = $request_id)");
               
                //Verifica se foi inserido com sucesso
                $result = pg_query( $SQL ) or die("Couldn t execute query".pg_last_error());
               
                echo "Atualização realizada com sucesso";
            break;


            case "apagar_celular":

                //Recebe as variáveis do datastring
                $request_id = trim($_REQUEST['cad_id']);

                //Apaga do banco
                $SQL = ("DELETE FROM telefone WHERE (id = $request_id)");
               
                //Verifica se foi inserido com sucesso
                $result = pg_query( $SQL ) or die("Couldn t execute query".pg_last_error());
                
            break;


            case "grid_buscar_celular":
                
                $idUsuario = $_SESSION['idUsuario'];
                              
                $SQL = "SELECT ddd, telefone, operadora, id FROM telefone WHERE id_user = '$idUsuario'";
                $result = pg_query( $SQL ) or die("Couldn t execute query.".pq_last_error());

                if ( stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml") ) {
                        header("Content-type: application/xhtml+xml;charset=utf-8"); }
                else { header("Content-type: text/xml;charset=utf-8");
                }

                        echo "<?xml version='1.0' encoding='utf-8'?>";
                        echo "<rows>";
 

                while($row = pg_fetch_array($result)) {

                         //Renomear operadora
                        $oper = $row[operadora];

                        if($oper == "1"){
                            $oper = "VIVO";
                        }elseif($oper == "2"){
                            $oper = "OI";
                        }
                        elseif($oper == "3"){
                            $oper = "TIM";
                        }
                        elseif($oper == "4"){
                            $oper = "CLARO";
                        }
                        
                        echo "<row id='". $row[id]."'>";
                        echo "<cell>". $row[ddd]."</cell>";
                        echo "<cell>". $row[telefone]."</cell>";
                        echo "<cell>". $oper."</cell>";
                        echo "</row>";
                }
                echo "</rows>";

            break;
        }

        }

//Fecha conexão com o banco de dados
pg_close($bd);
?>
