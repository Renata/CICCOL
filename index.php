<?php
header("Content-Type: text/html; charset=utf-8",true);

require_once 'libs/Smarty.class.php';
require_once 'head.php';
require_once 'templates/header.tpl';


$smarty = new Smarty();

$smarty->template_dir = 'templates/';
$smarty->compile_dir = 'templates_c/';
$smarty->left_delimiter = '<!--{';
$smarty->right_delimiter = '}-->';

$smarty->compile_check = true;
$smarty->debugging = false;

$smarty->display('index.tpl');

?>