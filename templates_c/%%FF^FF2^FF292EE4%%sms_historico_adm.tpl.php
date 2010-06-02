<?php /* Smarty version 2.6.26, created on 2010-05-05 20:06:52
         compiled from sms_historico_adm.tpl */ ?>
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

<p class="heading">Buscar Historico</p>

<div id="form">

    <form >

            <label for="tipo">SMS enviados por</label>
            <select size="1">
                <option selected></option>
                <option>Todos</option>
                <option>Professor</option>
                <option>Aluno</option>
                <option>Disciplina</option>
            </select>
            <input type="button" value="OK"/>


    </form>
</div>




