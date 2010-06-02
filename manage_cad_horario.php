<?php

header("Content-Type: text/html; charset=utf-8",true);
require_once 'libs/Smarty.class.php';
require_once 'head_1.php';
include("libs/dbconfig.php");


$smarty = new Smarty();

$smarty->template_dir = 'templates/';
$smarty->compile_dir = 'templates_c/';
$smarty->left_delimiter = '<!--{';
$smarty->right_delimiter = '}-->';

$smarty->compile_check = true;
$smarty->debugging = false;


//Pega a lista de disciplinas cadastradas no banco
$SQL = pg_query("SELECT nome FROM disciplina GROUP BY nome") or die("Couldn t execute query".pg_last_error());

while ($row_disciplina = pg_fetch_array($SQL)){
     $disciplina[] = array("disciplina"=>$row_disciplina["nome"]);
}


$smarty->assign('disc',$disciplina);


$smarty->display('cad_horario.tpl');

?>