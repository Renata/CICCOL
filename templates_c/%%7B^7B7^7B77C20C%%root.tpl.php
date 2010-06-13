<?php /* Smarty version 2.6.26, created on 2010-06-12 21:50:48
         compiled from root.tpl */ ?>
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


        <p class="msg_perfil">Bem-vindo(a), <?php echo $this->_tpl_vars['nome']; ?>
</p>
       

        <form class="relogio_perfil" name="form_relogio">
        <input type="text" name="relogio" size="10" style="background-color : #f2f2f2; color : black; font-family : Verdana, Arial, Helvetica; font-size : 8pt; text-align : center;" onfocus="window.document.form_relogio.relogio.blur()">
       </form>


          <div id="menu_right">

           <h3>Cadastrar</h3>
                <ul>
                    <li id="cad_administrador"><a href="#">Administrador</a></li>
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