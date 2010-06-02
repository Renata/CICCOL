<?php

header("Content-Type: text/html; charset=utf-8",true);
require_once 'libs/Smarty.class.php';
require_once 'head_1.php';
include ("libs/dbconfig.php");


$smarty = new Smarty();

$smarty->template_dir = 'templates/';
$smarty->compile_dir = 'templates_c/';
$smarty->left_delimiter = '<!--{';
$smarty->right_delimiter = '}-->';

$smarty->compile_check = true;
$smarty->debugging = false;


//Pega a lista de materias cadastrados no banco
$SQL = pg_query("SELECT id_materia, nome FROM materia ORDER BY nome") or die("Couldn t execute query".pg_last_error());


while ($row_nome = pg_fetch_array($SQL)){
     $materia[] = array("materia"=>$row_nome["nome"], "id"=>$row_nome["id_materia"]);
}

$smarty->assign('mat',$materia);



$smarty->display('cad_disciplina.tpl');

?>