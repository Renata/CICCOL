<?php /* Smarty version 2.6.26, created on 2010-06-03 16:24:11
         compiled from cad_curriculo.tpl */ ?>
<script type="text/javascript" charset="ISO-8859-1">
    //-------------------------------------------- Ação sobre o botão OK --------------------------------------

  $('#bt_ok_cad_curriculo').click(function(){

            //Pega os valores do formulário
            var perfil_profissional = $("#cad_curriculo_perfil_profissional").val();
            var ultimo_emprego = $("#cad_curriculo_ultimo_emprego").val();
            var cargo = $("#cad_curriculo_cargo").val();
            var id_curriculo = $("#cad_curriculo_id").val();

            var interesse = [];

            //Função para pegar os valores do segundo select
            $("#select2 option:selected").each(function() {
                interesse.push($(this).val());
            });


            //Armazena os valores do formulário na variável dataString
           var dataString = 'perfil_profissional=' + perfil_profissional + '&ultimo_emprego=' + ultimo_emprego + '&cargo=' + cargo + '&id_curriculo=' + id_curriculo + '&interesse=' + interesse;


            //Defique qual action será passada na url
            if (id_curriculo=="")
                var opcao= 'inserir_curriculo';
            else
                var opcao='update_curriculo';

            //Envia a variável dataString para a lib que insere no banco de dados
            $.ajax({
                    type: "GET",
                    url: "libs/lib_cad_curriculo.php?reference=curriculo&action="+ opcao,
                    processData: false,
                    data: dataString,
                    //dataType: "html",
                    success: function(){
                      //alert(msg);
                      alert("Currículo feito com sucesso!");
                    }
                });

    })

//------------------------------------ Ação sobre o botão Limpar ----------------------

$('#bt_limpar_cad_curriculo').click(function(){

    var id_curriculo = $("#cad_curriculo_id").val();

    if (id_curriculo=="")
    {
        //Limpa o formulário
        $('#form_cad_curriculo').each(function(){
                    this.reset();
        });
    }
    else
    {
        //Armazena os valores do formulário na variável dataString
        var dataString = 'id_curriculo=' + id_curriculo;

        var opcao='apagar_curriculo';

        //Envia a variável dataString para a lib que insere no banco de dados
            $.ajax({
                    type: "GET",
                    url: "libs/lib_cad_curriculo.php?reference=curriculo&action="+ opcao,
                    processData: false,
                    data: dataString,
                    //dataType: "html",
                    success: function(){
                       //Limpa o formulário
                       alert("Currículo apagado com sucesso!");

                    }
             });
    }

})


$('#add').click(function() {
      return !$('#select1 option:selected').remove().appendTo('#select2');

});

$('#remove').click(function() {
      return !$('#select2 option:selected').remove().appendTo('#select1');
});


</script>


<p class="heading">Cadastro de Currículo</p>

<!-- Formulário de cadastro e edição -->
<div id="cad_cur">
    <form class="dialog-form" id="form_cad_curriculo" >
        <fieldset class="ui-widget ui-widget-content ui-corner-all">
		<legend class="ui-widget ui-widget-header ui-corner-all">Currículo</legend>
                    <br/>

                    <label>Perfil Profissional</label>
                    <TEXTAREA type="text" name="cad_curriculo_perfil_profissional" id="cad_curriculo_perfil_profissional" class="text ui-widget-content ui-corner-all" COLS="40" ROWS="6"><?php echo $this->_tpl_vars['perfil']; ?>
</TEXTAREA><br/><br/>

                    <label>Último Emprego</label>
                    <TEXTAREA type="text" name="cad_curriculo_ultimo_emprego" id="cad_curriculo_ultimo_emprego" class="text ui-widget-content ui-corner-all" COLS="20" ROWS="5"><?php echo $this->_tpl_vars['ultEmprego']; ?>
</TEXTAREA><br/><br/>


                    <label>Cargo Atual</label>
                    <select id="cad_curriculo_cargo" name="cad_curriculo_cargo">
                        <option>Selecione</option>
                        <?php unset($this->_sections['cont_cargo']);
$this->_sections['cont_cargo']['name'] = 'cont_cargo';
$this->_sections['cont_cargo']['loop'] = is_array($_loop=$this->_tpl_vars['cargo']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['cont_cargo']['show'] = true;
$this->_sections['cont_cargo']['max'] = $this->_sections['cont_cargo']['loop'];
$this->_sections['cont_cargo']['step'] = 1;
$this->_sections['cont_cargo']['start'] = $this->_sections['cont_cargo']['step'] > 0 ? 0 : $this->_sections['cont_cargo']['loop']-1;
if ($this->_sections['cont_cargo']['show']) {
    $this->_sections['cont_cargo']['total'] = $this->_sections['cont_cargo']['loop'];
    if ($this->_sections['cont_cargo']['total'] == 0)
        $this->_sections['cont_cargo']['show'] = false;
} else
    $this->_sections['cont_cargo']['total'] = 0;
if ($this->_sections['cont_cargo']['show']):

            for ($this->_sections['cont_cargo']['index'] = $this->_sections['cont_cargo']['start'], $this->_sections['cont_cargo']['iteration'] = 1;
                 $this->_sections['cont_cargo']['iteration'] <= $this->_sections['cont_cargo']['total'];
                 $this->_sections['cont_cargo']['index'] += $this->_sections['cont_cargo']['step'], $this->_sections['cont_cargo']['iteration']++):
$this->_sections['cont_cargo']['rownum'] = $this->_sections['cont_cargo']['iteration'];
$this->_sections['cont_cargo']['index_prev'] = $this->_sections['cont_cargo']['index'] - $this->_sections['cont_cargo']['step'];
$this->_sections['cont_cargo']['index_next'] = $this->_sections['cont_cargo']['index'] + $this->_sections['cont_cargo']['step'];
$this->_sections['cont_cargo']['first']      = ($this->_sections['cont_cargo']['iteration'] == 1);
$this->_sections['cont_cargo']['last']       = ($this->_sections['cont_cargo']['iteration'] == $this->_sections['cont_cargo']['total']);
?>
                        <option><?php echo $this->_tpl_vars['cargo'][$this->_sections['cont_cargo']['index']]['cargo']; ?>
</option>
                       <?php endfor; endif; ?>
                    </select><br/><br/><br/>

                    <label>Área de Interesse</label>
                    <table width="55%" cellspacing="12">
                        <tr><td align="center"><select multiple="multiple" id="select1" size="5" name="select1">
                            <?php unset($this->_sections['cont_interesse']);
$this->_sections['cont_interesse']['name'] = 'cont_interesse';
$this->_sections['cont_interesse']['loop'] = is_array($_loop=$this->_tpl_vars['interesse']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['cont_interesse']['show'] = true;
$this->_sections['cont_interesse']['max'] = $this->_sections['cont_interesse']['loop'];
$this->_sections['cont_interesse']['step'] = 1;
$this->_sections['cont_interesse']['start'] = $this->_sections['cont_interesse']['step'] > 0 ? 0 : $this->_sections['cont_interesse']['loop']-1;
if ($this->_sections['cont_interesse']['show']) {
    $this->_sections['cont_interesse']['total'] = $this->_sections['cont_interesse']['loop'];
    if ($this->_sections['cont_interesse']['total'] == 0)
        $this->_sections['cont_interesse']['show'] = false;
} else
    $this->_sections['cont_interesse']['total'] = 0;
if ($this->_sections['cont_interesse']['show']):

            for ($this->_sections['cont_interesse']['index'] = $this->_sections['cont_interesse']['start'], $this->_sections['cont_interesse']['iteration'] = 1;
                 $this->_sections['cont_interesse']['iteration'] <= $this->_sections['cont_interesse']['total'];
                 $this->_sections['cont_interesse']['index'] += $this->_sections['cont_interesse']['step'], $this->_sections['cont_interesse']['iteration']++):
$this->_sections['cont_interesse']['rownum'] = $this->_sections['cont_interesse']['iteration'];
$this->_sections['cont_interesse']['index_prev'] = $this->_sections['cont_interesse']['index'] - $this->_sections['cont_interesse']['step'];
$this->_sections['cont_interesse']['index_next'] = $this->_sections['cont_interesse']['index'] + $this->_sections['cont_interesse']['step'];
$this->_sections['cont_interesse']['first']      = ($this->_sections['cont_interesse']['iteration'] == 1);
$this->_sections['cont_interesse']['last']       = ($this->_sections['cont_interesse']['iteration'] == $this->_sections['cont_interesse']['total']);
?>
                                <option value="<?php echo $this->_tpl_vars['interesse'][$this->_sections['cont_interesse']['index']]['id']; ?>
"><?php echo $this->_tpl_vars['interesse'][$this->_sections['cont_interesse']['index']]['interesse']; ?>
</option>
                            <?php endfor; endif; ?>
                            </select></td>


                            <td align="center"><select multiple="multiple" id="select2" name="select2" size="5"></select></td>
                       </tr>

                        <tr><td align="center"><a href="#" id="add"><input type="button" value="ADD &gt;&gt;" id="add" title="OK" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"></a></td>

                            <td align="center"><a href="#" id="remove"><input type="button" value="&lt;&lt; SUB" id="remove" title="OK" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"></a></td>
                        </tr>
                    </table>


                    <input type="text" id="cad_curriculo_id" name="cad_curriculo_id" value="<?php echo $this->_tpl_vars['idCurriculo']; ?>
" style="display:none;" /><br/><br/><br/>

                    <div id="dialog-form_button"
                        <input type="button" value="OK" id="bt_ok_cad_curriculo" title="OK" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">
                        <input type="button" value="Limpar" id="bt_limpar_cad_curriculo" title="Limpar" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">
                    </div>
         </fieldset>
    </form>
  </div>







