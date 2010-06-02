<?php
header("Content-Type: text/html; charset=utf-8",true);
require_once 'libs/Smarty.class.php';
require_once 'head_1.php';
include('libs/dbconfig.php');


$smarty = new Smarty();

$smarty->template_dir = 'templates/';
$smarty->compile_dir = 'templates_c/';
$smarty->left_delimiter = '<!--{';
$smarty->right_delimiter = '}-->';

$smarty->compile_check = true;
$smarty->debugging = false;


//Pega a lista de disciplinas cadastradas no banco
$SQL = pg_query("SELECT id_grade FROM GradeCurricular ORDER BY dt_implantacao") or die("Couldn t execute query".pg_last_error());

while ($row_grade = pg_fetch_array($SQL)){
     $grade[] = array("id_grade"=>$row_grade["id_grade"]);
}


$smarty->assign('grade', $grade);

$smarty->display('menu_princ_grade.tpl');

?>