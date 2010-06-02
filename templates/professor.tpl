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
                    <li id="editar_perfil_dados_professor"><a href="#">Alterar Dados</a></li>
                    <li id="editar_perfil_imagem"><a href="#">Alterar Imagem</a></li>
                    <li id="editar_perfil_senha"><a href="#">Alterar Senha</a></li>
                    <li id="cad_perfil_celular"><a href="#">Cadastrar Celular</a></li>
                </ul>
            <h3>Currículo</h3>
                <ul>
                    <li id="cad_curriculo"><a href="#">Cadastrar Currículo</a></li>
                    <li id="cad_projeto"><a href="#">Cadastrar Projetos</a></li>
                    <li id="curriculo_visualizar"><a href="#">Meu Currículo</a></li>
                </ul>
            <h3>Buscar</h3>
                <ul>
                    <li id="buscar_aluno"><a href="#">Alunos</a></li>
                    <li id="buscar_professor"><a href="#">Professores</a></li>
                    <li id="buscar_disciplina"><a href="#">Disciplina</a></li>
                </ul>
            <h3>Noticias</h3>
                <ul>
                    <li id="noticia_adicionar"><a href="#">Adicionar Noticia</a></li>
                </ul>
            <h3>SMS</h3>
                <ul>
                    <li id="sms_enviar"><a href="#">Enviar SMS</a></li>
                    <li id="sms_historico"><a href="#">Histórico</a></li>
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

       <div id="footer">
        <p> &copy; 2010  | Desenvolvido por: NAUGENIE</p>
       </div>
      
  </body>
</html>
