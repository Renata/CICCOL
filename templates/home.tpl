        <script type="text/javascript">

            $(function() {

                $('#content_home').tabs();

            });
        </script>

 
<body>
  

     
        <div id="content_home">
            <ul>
                <li><a href="#noticia"><span>Noticia</span></a></li>
                <li><a href="#buscar_noticia"><span>Busca</span></a></li>
            </ul>

            <div id="noticia">
                <p>Noticia 1 <br/>
                <a href="#">link para a noticia 1</a></p>

                <p>Noticia 2 <br/>
                <a href="#">link para a noticia 1</a></p>

                <p>Noticia 3 <br/>
                <a href="#">link para a noticia 1</a></p>

            </div>

           <div id="buscar_noticia" class="noticia">
               <form class="form_conf_noticia">
                   <label for="tipo">Tipo</label>
                   <select size="1">
                        <option selected></option>
                        <option>Emprego</option>
                        <option>Edital</option>
                        <option>Reportagem</option>
                   </select>
                   <input type="button" value="OK"/>
               </form>
           </div>

       </div>


</body>
