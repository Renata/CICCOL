<?php
ob_start();
session_start();
include ("dbconfig.php");
include("lib_valida_sessao.php");



$iden = $_REQUEST['login'];


// Liga o ID a pagina certa

$consulta = pg_query("SELECT id_tpuser FROM autenticacao WHERE identificador = '$iden'") or die("Erro tabela usuario".pg_last_error());
$saida = pg_fetch_array($consulta);

//echo $saida[id_tpuser]; // Comecei do tp_user = 2 porque o 1 é o root!

// dependendo do valor de tp_user ele vai encaminhar o usuário para a página correta!

if($saida[id_tpuser]=='3')
  header("Location:manage_aluno.php");
  //  echo "<script language='javaScript'>window.location.href='manage_aluno.php'</script>";
 // $redireciona = "$.ajax({  url:\"manage_aluno.php\",  cache: false,  });";
     
  
/*
if($saida=='3')
    header("Location:manage_administrador.php");

if($saida=='4')
    header("Location:manage_professor.php");

if($saida=='5')
    header("Location:manage_aluno.php");

*/
ob_end_flush(); 
?>