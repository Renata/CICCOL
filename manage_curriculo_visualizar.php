<?php

header("Content-Type: text/html; charset=utf-8",true);
require_once 'libs/Smarty.class.php';
include ("libs/dbconfig.php");

$smarty = new Smarty();

$smarty->template_dir = 'templates/';
$smarty->compile_dir = 'templates_c/';
$smarty->left_delimiter = '<!--{';
$smarty->right_delimiter = '--}>';

$smarty->compile_check = true;
$smarty->debugging = false;

/* Faz uma consulta ao banco para pegar o nome e sobrenome do docente */
$SQL = pg_query("SELECT nome, sobrenome FROM Usuario WHERE id_user IN (SELECT id_user FROM Autenticacao WHERE identificador = '12310488')") or die ("NAOOOO".pg_last_error());

while ($row_nome = pg_fetch_array($SQL)){
    $doc = $row_nome["nome"] . ' '. $row_nome["sobrenome"];
}

$smarty->assign('docente',$doc);

$smarty->display('curriculo_visualizar.tpl');

?>