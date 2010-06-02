<?php /* Smarty version 2.6.26, created on 2010-05-05 15:50:46
         compiled from adm_atuacao.tpl */ ?>
<script type="text/javascript">
    $(document).ready(function() {
        $("#bt_salvar_atuacao").click(function() {
           alert("Ainda nao foi implementado!")
        });
    });
    </script>


<p class="heading">Adicione as areas de atuacao do curso!</p>


<div id="form">
    <TEXTAREA COLS=60 ROWS=8> <?php echo $this->_tpl_vars['opcao']; ?>
 </TEXTAREA>
</div>

<div id="button">
    <div id="bt_salvar_atuacao" class="button_salvar" onclick="">
        <p class="button">Salvar</p>
    </div>
</div>



