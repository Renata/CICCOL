<?php
header("Content-Type: text/html; charset=utf-8",true);

require_once 'libs/Smarty.class.php';
require_once 'head.php';

$smarty = new Smarty();

$smarty->template_dir = 'templates/';
$smarty->compile_dir = 'templates_c/';
$smarty->left_delimiter = '<!--{';
$smarty->right_delimiter = '}-->';

$smarty->compile_check = true;
$smarty->debugging = false;

$atuacao = 'Áreas de atuaçao insridas pelo administrador/moderador';

$smarty->assign('opcao', $atuacao);


$smarty->display('adm_contato.tpl');

?>