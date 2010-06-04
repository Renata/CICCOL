<?php /* Smarty version 2.6.26, created on 2010-06-04 09:22:17
         compiled from index.tpl */ ?>
<script type="text/javascript">
    $(document).ready(function () {

            $('#mostra_noticia_').load('templates/home.tpl');

            $("#btnEnviar").click(function(){

                var login = $('#txtLogin').val();
                var senha = $.md5($('#pwdSenha').val());


                dataString = 'login=' + login + '&senha=' + senha;
                
                    $.ajax({
                    type: "GET",
                    url: "libs/lib_login.php",
                    processData: false,
                    data: dataString
                    
                    
                    
                });


            })

   
});



</script>


<body>
    <div id="todo">

        <div id="todo_in">
            <div id="sidebar_left_index">
                <div id="mostra_noticia_"></div>

                <div id="mais" class="saibamais">
                     <h1 class="titulo">Saiba Mais</h1>
                     <ul>
                         <li>Deixe sua notícia</li>
                         <li>Conheça ...</li>
                         <li>Conheça ...</li>
                         <li>Conheça ...</li>

                     </ul>
                </div>
                
           </div>
            
            <div id="content">

                <h2>Titulação</h2>
                     <p class="center">Bacharel em Ciência da Computação</p>
                <h2>Duração sugerida pela UESC</h2>
                      <p class="center">4 anos</p>
                <h2>Duração máxima permitida pela legislação Federal</h2>
                      <p class="center">8 anos</p>



                      <a href="manage_moderador.php">Moderador | </a>
                    <a href="manage_administrador.php">Admnistrador |</a>
                    <a href="manage_professor.php">Professor |</a>
                    <a href="manage_aluno.php">Aluno</a>
		
            </div>
            
            <div id="sidebar_right_index">
               
                <form name="form_login" method="post">
                    <table id="tb_login" >
                        <tr>
                          <td width="70%" align="left">Login:</td>
                          <td width="30%" align="left"><input type="text" id="txtLogin" value="LOGIN" size="10" ></td>
                        </tr>
                        <tr>
                          <td width="70%" align="left">Senha:</td>
                          <td width="30%" align="left"><input type="password" id="pwdSenha" value="SENHA" size="10"></td>
                        </tr>
                        <tr>
                          <td width="100%" align="left" colspan="2">&nbsp;</td>
                        </tr>
                        <tr>
                          <td width="100%" align="right" colspan="2"><input type="button" id="btnEnviar" value="Enviar">&nbsp;&nbsp;&nbsp;</td>
                        </tr>

                    </table>
                    <br>
                     <a href="#" class="mostra" id="recovery">Esqueceu sua senha?</a><br>
                    <a href="#" class="mostra" id="permissao_acesso">Quero ter acesso ao sistema.</a>

                </form>

                <h1 class="titulo">Links</h1>
                <div id="mais" class="tecno">
                    <img src="style/images/logo_tecno.png"/>
                </div>
                <div id="mais">
                    <img src="style/images/logo_cacic.png"/>
                </div>

            </div>
        </div>
    </div>

    <div id="footer">
	<p> &copy; 2010  | Desenvolvido por: NAUGENIE</p>
    </div>
</body>