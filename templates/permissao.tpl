<script src="js/md5.js" type="text/javascript"></script>
<script type="text/javascript" src="js/jquery.maskedinput-1.2.2.js"></script>


<script type="text/javascript">

$("#msg_sucess_solic").hide();

$("#submit").click(function(){
	
	var matricula = $('#numero_matricula').val();
	var email = $('#email').val();
	var senha = $.md5($('#senha').val());
	var rsenha = $.md5($('#rsenha').val());
	var cpf = $('#cpf').val();
		
	if(senha == rsenha)
	{
	
        dataString = 'email=' + email + '&matricula=' + matricula + '&senha=' + senha + '&cpf' + cpf ;
	
                    $.ajax({
                    type: "GET",
                    url: "libs/lib_permissao_acesso.php",
                    processData: false,
                    data: dataString,
                    success: function(msg){
                        alert(msg);
                        if(msg == 0){
                            alert("Verifique se você digitou seus dados corretamente ou entre em contado com o colegiado do seu curso!");
                        }
                        else if(msg == 1){
                             $("#form_solicitacao").hide();
                             $("#msg_sucess_solic").show();
                             $("#msg_sucess_solic").html('<span>* Você receberá um e-mail com o seu login! </span>');

                        }
                    }
                });
	
	}
	else 
		alert("Senha não confere");
})


$("#numero_matricula").mask("999999999");

</script>
<body>

    <p class="heading">Solicitação de ativação</p>

    <div id="msg_sucess_solic"></div>


    <div id="cad_adm">
    <form class="dialog-form" id="form_solicitacao" >
        <fieldset class="ui-widget ui-widget-content ui-corner-all">
		<legend class="ui-widget ui-widget-header ui-corner-all">Alguma coisa</legend>

                  

                    <label>Matrícula</label>
                    <input type="text" size="9" name="numero_matricula" id="numero_matricula" class="text ui-widget-content ui-corner-all" /><br/>
					<label>CPF</label>
					<input type="text" size="11" name="cpf" id="cpf" class="text ui-widget-content ui-corner-all" /><br/>

                    <label>E-mail</label>
                    <input type="text" size="30" name="email" id="email" class="text ui-widget-content ui-corner-all" /><br/>

                    <label>Senha</label>
                    <input type="password" size="10" name="senha" id="senha" class="text ui-widget-content ui-corner-all" /><br/>

                    <label>Repita a senha</label>
                    <input type="password" size="10" name="rsenha" id="rsenha" class="text ui-widget-content ui-corner-all" /><br/>

                    
                    <div id="dialog-form_button"
                        <input type="button" value="Cadastrar" id="submit" title="OK" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">
                    </div>
        </fieldset>
    </form>
  </div>
<!--
	<div>
	  <form >
    	<label></label>
    	<p>Quero ter acesso ao sistema<br>
    	  Para ter acesso ao CICCOL, &eacute; necess&aacute;rio cadastra-se:<br>
    	  <br>
    	</p>
    	<table width="304" border="0" cellpadding="3" cellspacing="0">
          <tr>
            <td width="140">N&uacute;mero de matr&iacute;cula:</td>
            <td width="152"><input name="numero_matricula" type="text" id="numero_matricula"></td>
          </tr>
          <tr>
            <td>Endere&ccedil;o de e-mail: </td>
            <td><input name="email" type="text" id="email"></td>
          </tr>
          <tr>
            <td>Senha:</td>
            <td><input name="senha" type="text" id="senha"></td>
          </tr>
          <tr>
            <td>Repita a senha: </td>
            <td><input name="rsenha" type="text" id="rsenha"></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td><label></label></td>
          </tr>
        </table>
    	<br>
	    <input type="button" id="submit" value="Cadastrar">
	  </form>
	</div>
-->
</body>