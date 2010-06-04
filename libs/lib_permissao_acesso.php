<?php

include ("dbconfig.php");

$email = trim($_REQUEST['email']);
$matricula = trim($_REQUEST['matricula']);
$senha = trim($_REQUEST['senha']);
$cpf = trim($_REQUEST['cpf']);



// Verifica se ja existe no banco!

$consulta = pg_query("SELECT id_user FROM aluno WHERE matricula = '$matricula'") or die("Erro tabela usuario".pg_last_error());
$saida = pg_fetch_array($consulta);

	if(!$saida[id_user])
	{
            $consulta1 = pg_query("SELECT id_user FROM docente WHERE matricula = '$matricula'") or die("Erro tabela usuario".						    	pg_last_error());
            $saida1 = pg_fetch_array($consulta1);

            if(!$saida1[id_user])
            {
                $consulta2 = pg_query("SELECT id_user FROM adminmoderador WHERE matricula = '$matricula'") or die("Erro tabela 				        	usuario".pg_last_error());
                $saida2 = pg_fetch_array($consulta2);

                if(!$saida2[id_user])
                {
                    $retorno = 0;
                    echo $retorno;
                    //echo "Verifique se você digitou seus dados corretamente ou entre em contado com o colegiado do seu curso!";
                }
                else
                {
                    $id_user = $saida2[id_user];
                    $tpuser = '1';
                }
            }
            else
            {
                $id_user = $saida1[id_user];
                $tpuser = 3;
            }
        }
        else
        {
            $id_user = $saida[id_user];
            $tpuser = 4;
        }

       if($id_user){
    
        $atualiza_email = pg_query("UPDATE usuario SET email= '$email' WHERE id_user = '$id_user'") or die ("Erro!".pg_last_error());
        $preenche_dados = pg_query("UPDATE autenticacao SET senha = '$senha' WHERE id_user = '$id_user' ") or die("   >> Erro   ".pg_last_error());
        
        $retorno = 1;
        echo $retorno;
        //echo "Você receberá um e-mail com o seu login!";
        }
     
?>