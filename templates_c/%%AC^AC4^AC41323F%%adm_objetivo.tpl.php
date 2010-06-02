<?php /* Smarty version 2.6.26, created on 2010-05-05 19:08:10
         compiled from adm_objetivo.tpl */ ?>
<script type="text/javascript">
    $(document).ready(function() {
        $("#bt_salvar_objetivo").click(function() {
           alert("Ainda nao foi implementado!")
        });
    });
    </script>


    <p class="heading">Adicione o objetivo do curso!</p>



<div id="form">
    <TEXTAREA COLS=60 ROWS=8> <?php echo $this->_tpl_vars['opcao']; ?>
 </TEXTAREA>
</div>

<div id="button">
    <div id="bt_salvar_objetivo" class="button_salvar" onclick="">
        <p class="button">Salvar</p>
    </div>  
</div>



