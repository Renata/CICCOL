<?php
header("Content-Type: text/html; charset=utf-8",true);
require_once 'libs/Smarty.class.php';

$smarty = new Smarty();

$smarty->template_dir = 'templates/';
$smarty->compile_dir = 'templates_c/';
$smarty->left_delimiter = '<{!--';
$smarty->right_delimiter = '--}>';

$smarty->compile_check = true;
$smarty->debugging = false;

$objetivo = 'O Curso de Bacharelado em Ciência da Computação da Universidade Estadual de Santa Cruz visa formar profissionais capacitados a atuar, tanto no mercado de aplicações, como prosseguir estudos em cursos de pós-graduação, além de realizar atividades de pesquisa e desenvolvimento de novos produtos. A formação fundamental ampla  em computação é importante para garantir a sobrevivência profissional em uma área sujeita a transformações aceleradas.
    oakoakjhdjhuysefgd';

$smarty->assign('opcao', $objetivo);

$smarty->display('menu_princ_docentes.tpl');

?>