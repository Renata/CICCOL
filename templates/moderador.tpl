<script type="text/javascript">

$(document).ready(function() {
	$("#mostra_noticia").load("templates/home.tpl");
    });


$(function() {
		$("#datepicker").datepicker();
	});

</script>

<body onload="moveRelogio()">
    <div id="sidebar_left">

        <div id="perfil">
            <img class="photo_perfil" src="style/images/no_photo.jpg" alt="box" />
 
        </div>

      
        <p class="msg_perfil">Bem-vindo(a), Fulano!</p>

        <form class="relogio_perfil" name="form_relogio">
        <input type="text" name="relogio" size="10" style="background-color : #f2f2f2; color : black; font-family : Verdana, Arial, Helvetica; font-size : 8pt; text-align : center;" onfocus="window.document.form_relogio.relogio.blur()">
       </form> 
            

          <div id="menu_right">

            <h3>Editar Perfil</h3>
                <ul>
                    <li id="editar_perfil_dados"><a href="#">Alterar Dados</a></li>
                    <li id="editar_perfil_imagem"><a href="#">Alterar Imagem</a></li>
                    <li id="editar_perfil_senha"><a href="#">Alterar Senha</a></li>
                    <li id="cad_perfil_celular"><a href="#">Cadastrar Celular</a></li>
                </ul>
            <h3>Cadastrar</h3>
                <ul>
                    <li id="cad_cargo"><a href="#">Cargo</a></li>
                    <li id="cad_grade"><a href="#">Grade Acadêmica</a></li>
                    <li id="cad_materia"><a href="#">Matéria</a></li>
                    <li id="cad_disciplina"><a href="#">Disciplina</a></li>
                    <li id="cad_horario"><a href="#">Horario</a></li>
                    <li id="cad_professor"><a href="#">Professor</a></li>
                    <li id="cad_aluno"><a href="#">Aluno</a></li>
                    <li id="alocar_professor"><a href="#">Alocar Professor</a></li>
                </ul>
            <h3>Pagina Inicial</h3>
                <ul>
                    <li id="adm_objetivo"><a href="#">Objetivo</a></li>
                    <li id="adm_atuacao"><a href="#">Atuacao</a></li>
                    <li id="adm_contato"><a href="#">Contato</a></li>
                   
                </ul>
            <h3>Banner</h3>
                <ul>
                    <li id="alt_banner"><a href="#">Alterar Banner</a></li>
                </ul>
            <h3>Noticias</h3>
                <ul>
                    <li id="noticia_adicionar"><a href="#">Adicionar Noticia</a></li>
                    <li id="noticia_aprovar"><a href="#">Aprovar Noticia</a></li>
                </ul>
            <h3>SMS</h3>
                <ul>
                    <li id="sms_enviar"><a href="#">Enviar SMS</a></li>
                    <li id="sms_historico_adm"><a href="#">Histórico</a></li>
                </ul>

            <h3>Email</h3>
                <ul>
                    <li id="email_enviar"><a href="#">Enviar Email</a></li>
                    <li id="email_historico"><a href="#">Histórico</a></li>
                </ul>
 
    </div>

      </div>

      <div id="sidebar_right">
 
      <div id="data">
              <div id="datepicker"></div>
      </div>

        <div id="mostra_noticia">

        </div>
  </div>
    

      <div id="content">
          
          <!--CONTEUDO -->
      </div>

       <div id="footer">  &copy; 2010  | Desenvolvido por: NAUGENIE </div>
      
  </body>
</html>
