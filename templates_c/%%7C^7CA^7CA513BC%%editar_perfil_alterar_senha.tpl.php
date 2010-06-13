<?php /* Smarty version 2.6.26, created on 2010-06-07 22:57:29
         compiled from editar_perfil_alterar_senha.tpl */ ?>
<script src="js/md5.js" type="text/javascript"></script>

<script type="text/javascript">

$("#alt_senha_ok").click(function(){

    var senha_atual = $.md5($('#alt_senha_atual').val());
    var senha_nova = $.md5($('#alt_senha_nsenha').val());
    var senha_rnova = $.md5($('#alt_senha_rnsenha').val());

    if (senha_nova == senha_rnova)
        {

    dataString = 'senha_atual='+ senha_atual + '&senha_nova='+ senha_nova;

                $.ajax({
                    type: "GET",
                    url: "libs/lib_perfil_alterar_senha.php",
                    processData: false,
                    data: dataString,
                    success: function (msg){
                        alert(msg);
                    }

                });
        }
    else
        {
            alert('senhas nao conferem');
        }

})

</script>
<body>
 <p class="heading">Alterar senha</p>

 <div id="cad_adm">
    <form class="dialog-form" id="form_alt_senha" >
        <fieldset class="ui-widget ui-widget-content ui-corner-all">
		<legend class="ui-widget ui-widget-header ui-corner-all">Dados</legend>


                    <label>Senha atual</label>
                    <input type="password" size="11" name="alt_senha_atual" id="alt_senha_atual" class="text ui-widget-content ui-corner-all" /><br/>

                    <label>Nova senha</label>
                    <input type="password" size="10" name="alt_senha_nsenha" id="alt_senha_nsenha" class="text ui-widget-content ui-corner-all" /><br/>
                    <label>Repita a nova senha</label>
                    <input type="password" size="10" name="alt_senha_rnsenha" id="alt_senha_rnsenha" class="text ui-widget-content ui-corner-all" /><br/><br/>

                    <div id="dialog-form_button"
                        <input type="button" value="Alterar" id="alt_senha_ok" title="OK" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">
                    </div>
        </fieldset>
    </form>
  </div>
</body>