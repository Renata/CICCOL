<?php


$db = pg_connect("host=localhost dbname=ciccol user=root password=naugenie321 ")
      or die(" Erro na conexão com o banco de dados!".pg_last_error());

?>
