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

<p class="heading">Adicionar Notícia</p>

<div id="form_noticia">

    <form class="form_conf_noticia">

            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome"/><br>
            <label for="email">E-mail</label>
            <input type="text" id="email" name="email"/><br />
            <label for="titulo">Titulo da Noticia</label>
            <input type="text" id="titulo" name="titulo"/><br />
            <label for="tipo">Tipo</label>
            <select size="1">
                <option selected></option>
                <option>Emprego</option>
                <option>Edital</option>
                <option>Reportagem</option>
                <option>Outro</option>
            </select><br />
            <label for="senha">Noticia</label>
            <TEXTAREA COLS=50 ROWS=8>  </TEXTAREA><br />


    </form>
</div>

<div id="button">
    
    <div id="bt_salvar_editar_dados" class="button_salvar" onclick="">
        <p class="button">Salvar</p>
    </div>
    
</div>




