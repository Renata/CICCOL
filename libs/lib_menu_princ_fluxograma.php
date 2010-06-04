<?php

include "dbconfig.php";

$mostrar = 0;
$inicio = "$(document).ready(function() {

	// notice the 'curviness' argument to this Bezier curve.  the curves on this page are far smoother
	// than the curves on the first demo, which use the default curviness value.
	jsPlumb.DEFAULT_CONNECTOR = new jsPlumb.Connectors.Bezier(100);
	jsPlumb.DEFAULT_DRAG_OPTIONS = { cursor: 'pointer', zIndex:2000 };
	jsPlumb.DEFAULT_PAINT_STYLE = { strokeStyle:'black' };
	jsPlumb.DEFAULT_ENDPOINT_STYLE = { radius:4 };
        jsPlumb.DEFAULT_DRAG_OPTIONS = { cursor: 'crosshair' };
\n";
                                


$final = "
});
";


$Arquivo = fopen('../js/fluxograma/fluxograma.js',"w"); // abro arquivo somente para escrita
     fwrite($Arquivo, $inicio); // gravo no arquivo
fclose($Arquivo); // fecho o arquivo.

$sql_requisito = pg_query("SELECT requisito_cod_disciplina, cod_disciplina FROM disciplinarequisitadisciplina");

$Arquivo = fopen('../js/fluxograma/fluxograma.js',"a"); // abro arquivo somente para escrita

while($pega_sql_requisito = pg_fetch_array($sql_requisito)){

    $disciplina = $pega_sql_requisito['cod_disciplina'];
    $requisito_disciplina = $pega_sql_requisito['requisito_cod_disciplina'];

    $meio1 = '$("';
    $meio2 = "#";
    $meio3 = '").plumb({target:';
    $meio4 = "'";
    $meio5 = "'});";

    $meio = $meio1.$meio2.$requisito_disciplina.$meio3.$meio4.$disciplina.$meio5."\n";

    fwrite($Arquivo, $meio); // gravo no arquivo
}

fclose($Arquivo); // fecho o arquivo.


$Arquivo = fopen('../js/fluxograma/fluxograma.js',"a"); // abro arquivo somente para escrita, excluo suas linhas e coloco o ponteiro no começo.
     fwrite($Arquivo, $final); // gravo no arquivo
fclose($Arquivo); // fecho o arquivo.

$mostrar = 1;

?>




<?php
if($mostrar = 1){
?>

<!doctype html>
<html>
	<head>
		<title>jsPlumb demo 2</title>
		<meta http-equiv="X-UA-Compatible" content="IE=7">
		<script type="text/javascript" src="../js/fluxograma/excanvas.js"></script>
		<script type="text/javascript" src="../js/fluxograma/jquery.min.js"></script>
		<script type="text/javascript" src="../js/fluxograma/jquery-ui.min.js"></script>

		<script type="text/javascript" src="../js/fluxograma/jquery.jsPlumb-1.0.4-min.js"></script>
                <script type="text/javascript" src="../js/fluxograma/fluxograma.js"></script> 

<?php

echo '<style type="text/css">';
echo '.window { border:2px dotted #346789; opacity:0.8; filter:alpha(opacity=80); width:10em; height:4em; z-index:20; position:absolute; color:white;font-family:helvetica;padding-top:0.4em; font-size:0.8em;text-align:center;}';


$sql_qtd_semestre = pg_query("SELECT distinct semestre FROM disciplina");
$qtd_semestres = pg_num_rows($sql_qtd_semestre);

$top = 5;
$left = 5;

$cores = array(0 => 0, 1 => "006400", 2 => "ffa500", 3 => "000000", 4 => "ff4500", 
               5 => "882255", 6 => "ff69b4", 7 => "fa8072", 8 => "6495ed", 9 => "708090",
               10 => "ff6347", 11 => "9acd32", 12 => "daa520");

for($i = 1; $i<= $qtd_semestres; $i++){
$sql_requisito_css = pg_query("SELECT cod_disciplina, nome, semestre FROM disciplina");
//$mostra_semestre = 1;
    while($pega_sql_css = pg_fetch_array($sql_requisito_css)){

        $semestre = $pega_sql_css['semestre'];
        $codigo = $pega_sql_css['cod_disciplina'];

        if($semestre == $i){
         //   if($mostra_semestre = 1){
          //  echo "#"."$codigo"." {top:"."$top"."em;"." left:"."$left"."em;"." background-color:"."#"."$cores[$i]".";}"."\n";
          //  $mostra_semestre = 1;
           // }
            echo "#"."$codigo"." {top:"."$top"."em;"." left:"."$left"."em;"." background-color:"."#"."$cores[$i]".";}"."\n";
            $left += 15;
        }
    }
    $top += 15;
    $left = 5;
}

// <img title="esse usu"></img>
//echo "#CET635 {top:0em; left:10em; background-color:#882255;}
//#FHC310 {top:0em; left:25em; background-color:#882255;}
//#CET641 {top:0em; left:40em; background-color:#882255;}
//#CET078 {top:0em; left:55em; background-color:#882255;}
//#CET077 {top:0em; left:70em; background-color:#882255;}
//#CET079 {top:0em; left:85em; background-color:#882255;}
//#CET080 {top:0em; left:100em; background-color:#882255;}
// #CET082 {top:0em; left:115em; background-color:#882255;}
// #CET090 {top:0em; left:130em; background-color:#882255;}
// #CET087 {top:0em; left:145em; background-color:#882255;}
// #CET096 {top:0em; left:160em; background-color:#882255;}
// #CET091 {top:0em; left:175em; background-color:#882255;}
// #CET085 {top:0em; left:190em; background-color:#882255;}
// #CET095 {top:0em; left:205em; background-color:#882255;}
// #CET102 {top:0em; left:220em; background-color:#882255;}
//  #CET092 {top:0em; left:235em; background-color:#882255;}
//   #CET097 {top:0em; left:250em; background-color:#882255;}";

//echo '#window1 {background-color:#225588; left:10em;}';
//echo '#window2 {background-color:#225588; left:25em;}';
//echo '#window3 {top:10em; left:25em; background-color:#882255;}';
//echo '#window4 {top:4em; left:45em;background-color:#122704; left:4em;top:24em;}';
//echo '#window5 {top:27em;left:18em;background-color:#041227;left:22em;top:24em;}';
//echo '#window6 {top:35em;left:42em;background-color:#333300;left:47em;top:24em;}';

	echo '</style>';
?>
	</head>

	<body onunload="jsPlumb.unload();">


 <?php

$sql_requisito_html = pg_query("SELECT cod_disciplina, nome FROM disciplina");

 while($pega_sql_html = pg_fetch_array($sql_requisito_html)){

 	$codigo2 = $pega_sql_html['cod_disciplina'];
        $nome = $pega_sql_html['nome'];

        echo '<div class="window" id="'.$codigo2.'">'.$nome.'</div>';

    }
    /*
		echo '<div class="window" id="CET635">window one</div>';
                echo '<div class="window" id="FHC310">window one</div>';
                echo '<div class="window" id="CET641">window one</div>';

                echo '<div class="window" id="CET078">window one</div>';
                echo '<div class="window" id="CET077">window one</div>';
                echo '<div class="window" id="CET079">window one</div>';
                echo '<div class="window" id="CET080">window one</div>';
                echo '<div class="window" id="CET082">window one</div>';
                echo '<div class="window" id="CET090">window one</div>';
                echo '<div class="window" id="CET087">window one</div>';
                echo '<div class="window" id="CET096">window one</div>';
                echo '<div class="window" id="CET091">window one</div>';
                echo '<div class="window" id="CET085">window one</div>';
                echo '<div class="window" id="CET095">window one</div>';
                echo '<div class="window" id="CET102">window one</div>';
                echo '<div class="window" id="CET092">window one</div>';
                echo '<div class="window" id="CET097">window one</div>';
*/
 ?>

	</body>
</html>
<?php
}

pg_close($db);
?>