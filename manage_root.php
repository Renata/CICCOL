<?php

header("Content-Type: text/html; charset=utf-8",true);

require_once 'libs/Smarty.class.php';
require_once 'head.php';
require_once 'templates/header.tpl';
include ("libs/dbconfig.php");
@session_start();


$smarty = new Smarty();

$smarty->template_dir = 'templates/';
$smarty->compile_dir = 'templates_c/';
$smarty->left_delimiter = '<!--{';
$smarty->right_delimiter = '}-->';

$smarty->compile_check = true;
$smarty->debugging = false;

$idUsuario = $_SESSION['idUsuario'];

$SQL = pg_query ("SELECT nome FROM usuario WHERE id_user = '$idUsuario'") or die ("erro".pg_last_error());
$result = pg_fetch_array($SQL);
$nome=$result[nome];

$smarty->assign('nome', $nome);



$smarty->display('root.tpl');

?>