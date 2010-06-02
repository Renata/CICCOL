<script type="text/javascript">
    $(document).ready(function() {
        $("#bt_salvar_editar_senha").click(function() {
           alert("Ainda nao foi implementado!")
        });
    });

</script>

<head>
    <script src="js/jquery.js" type="text/javascript"></script>
    <script src="js/jquery.jqGrid.js" type="text/javascript"></script>
    <script src="js/jqModal.js" type="text/javascript"></script>
    <script src="js/jqDnR.js" type="text/javascript"></script>
    <script src="js/ui.datepicker.js" type="text/javascript"></script> 
    <link rel="stylesheet" type="text/css" media="screen" href="themes/ui.datepicker.css" />

</head>

<p class="heading">Enviar SMS</p>

<div id="form_type">

    <form class="form_conf">

            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome"/><br>
            <label for="tipo">Disciplina</label>
            <select size="1">
                <option selected>- - -- - - - - - </option>
                <option>D1</option>
                <option>D2</option>
                <option>D3</option>
                <option>D4</option>
            </select><br />
            <label for="tipo">Mensagem</label>
            <TEXTAREA COLS=20 ROWS=5>  </TEXTAREA><br />


    </form>
</div>

<div id="button">
    
    <div id="bt_salvar_editar_dados" class="button_salvar" onclick="">
        <p class="button">Salvar</p>
    </div>
    
</div>




