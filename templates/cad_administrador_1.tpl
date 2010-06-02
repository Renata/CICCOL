<script type="text/javascript">

// ------------------------------------Grid administrador---------------------------

var lastsel;
$("#list_cad_administrador").jqGrid({
    url:'libs/lib_administrador.php?reference=administrador&action=grid_buscar_administrador',
    width: 500,
    datatype: "xml",
    loadtext: "Carregando...",
    colNames:['Matricula','Nome','Sobrenome', 'E-Mail', 'CPF', 'Nasc'],
    colModel:[
        {name:'cad_administrador_matricula',index:'cad_administrador_matricula', width:60,align:"center"},
        {name:'cad_administrador_nome',index:'cad_administrador_nome', width:80,align:"center"},
        {name:'cad_administrador_sobrenome',index:'cad_administrador_sobrenome', width:100, align:"center"},
        {name:'cad_administrador_email',index:'cad_administrador_email', width:100, align:"center"},
        {name:'cad_administrador_cpf',index:'cad_administrador_cpf', width:60, align:"center"},
        {name:'cad_administrador_nascimento',index:'cad_administrador_nascimento', width:60, align:"center"},
    ],
    rowNum:10,
    rowList:[10,20,30],
    pager: '#pager_cad_administrador',
    sortname: 'id',
    viewrecords: true,
    sortorder: "desc",
    imgpath: 'themes/steel/images',
    onSelectRow: function(id){
        if(id && id!==lastsel){
            $('#list').restoreRow(lastsel);
            lastsel=id;
        }
    },
    //editurl: "libs/lib_administrador.php?reference=administrador&action=grid_buscar_administrador'",
    caption: "Administrador"
})


 //-----------------------------------Ação sobre o botão Novo ----------------------------
 
    $('#bt_add_cad_administrador').click(function(){


        //Deixa os campos apenas como leitura
        $("#msg").hide();
        $("#bt_ok_busca_admin").show();
        $('.aux').addClass("readonly");
        $('.aux').attr("readonly", "readonly");

                      
       //Reseta o formulário
       $('#form_cad_administrador').each(function(){
	        this.reset();
       });
        
       //Abre formulário
       $("#cad_adm").show();
    });

//--------------------------------------Ação sobre o botão Editar ----------------------------

    $('#bt_editar_cad_administrador').click(function(){


       //Libera os campos para escrita
       $("#msg").hide();
       $("#bt_ok_busca_admin").hide();
       $('.aux').removeClass("readonly");
       $('.aux').removeAttr("readonly");


       //Reseta o formulário
       $('#form_cad_administrador').each(function(){
	        this.reset();
       });

            
       //Pega o id e armazena em gsr
       var gsr = $("#list_cad_administrador").getGridParam('selrow');
       $("#cad_administrador_id").val(gsr);
       
       if(gsr){

           //Abre formulário
            $("#cad_adm").show();

           //Pega os valores da grid e coloca no formuário
           $("#list_cad_administrador").GridToForm(gsr,"#form_cad_administrador");

       }else{
           alert("Selecione uma linha!");
       }
    });


// ----------------------------------------Ação sobre o botão Apagar----------------------------------------

    $('#bt_apagar_cad_administrador').click(function(){

        //Esconde formulário
        $("#cad_adm").hide();

        //Pega o id e armazena em gsr
        var gsr = $("#list_cad_administrador").getGridParam('selrow');
        var dataString = 'cad_id='+ gsr;

        //Envia o id correspondente a linha que será apagada no banco
        if(gsr){
            $.ajax({
                type: "GET",
                url: "libs/lib_administrador.php?reference=administrador&action=apagar_administrador",
                processData: false,
                data: dataString,
                success: function(){
                    $("#list_cad_administrador").trigger("reloadGrid");
                }
            });
        }else{
            alert("Selecione uma linha!");
            }
    });

//-------------------------------------------- Ação sobre o botão OK --------------------------------------

  $('#bt_ok_cad_administrador').click(function(){
        
            //Pega os valores do formulário
            var matricula = $("#cad_administrador_matricula").val();
            var cpf = $("#cad_administrador_cpf").val();
            var nome = $("#cad_administrador_nome").val();
            var sobrenome = $("#cad_administrador_sobrenome").val();
            var email = $("#cad_administrador_email").val();
            var nascimento = $("#cad_administrador_nascimento").val();
            var id_cad = $("#cad_administrador_id").val();
           
            //Armazena os valores do formulário na variável dataString
            var dataString = 'matricula='+ matricula + '&cpf='  + cpf + '&nome=' + nome + '&sobrenome=' + sobrenome + '&email=' +  email + '&nascimento=' + nascimento + '&cad_id=' + id_cad;
           
            //Defique qual action será passada na url
            if (id_cad=="")
                var opcao= 'inserir_administrador';
            else
                var opcao='update_administrador';

            //Envia a variável dataString para a lib que insere no banco de dados
            $.ajax({
                    type: "GET",
                    url: "libs/lib_administrador.php?reference=administrador&action="+ opcao,
                    processData: false,
                    data: dataString,
                    //dataType: "html",
                    success: function(){
                     $("#cad_adm").hide();
                     $("#list_cad_administrador").trigger("reloadGrid");
                    }
                });
            
    })

//------------------------------------ Ação sobre o botão Cancelar ----------------------

$('#bt_cancelar_cad_administrador').click(function(){
    //Esconde formulário
    $("#cad_adm").hide();
    
})

//------------------------- Ação sobre o botão OK que verifica o cadastro de matrícula ----------------------

$("#bt_ok_busca_admin").click(function(){

     //Pega os valores do formulário
    var matricula = $("#cad_administrador_matricula").val();


    //Armazena os valores do formulário na variável dataString
    var dataString = 'matricula=' + matricula;

    //Verifica se a matricula já está cadastrada
    $.ajax({
        type: "GET",
        url: "libs/lib_administrador.php?reference=administrador&action=buscar_administrador",
        processData: false,
        data: dataString,
        success: function(msg){

            // msg -> variavel que retorna a consulta no banco

            if(msg == '0'){
                
                $("#msg").show();
                $("#msg").html('<span style= "color:red;">* Administrador já cadastrado</span>');

                //Deixa os campos apenas como leitura
                $('.aux').addClass("readonly");
                $('.aux').attr("readonly", "readonly");
            }
            else if(msg == 'cadastrar'){

                $("#msg").show();
                $("#msg").html('<span style= "color:green;">* Matricula não cadastrada </span>');


                //Reseta o formulário
                $('#cad_administrador_nome').val('');
                $('#cad_administrador_sobrenome').val('');
                $('#cad_administrador_email').val('');
                $('#cad_administrador_nascimento').val('');
                $('#cad_administrador_cpf').val('');



                //Libera os campos para escrita
                $('.aux').removeClass("readonly");
                $('.aux').removeAttr("readonly");
         
            }
            else{
                $("#msg").show();
                $("#msg").html('<span style= "color:red;>* O úsuario também está cadsatrado como professor</span>');

                //Deixa os campos apenas como leitura
                $('.aux').addClass("readonly");
                $('.aux').attr("readonly", "readonly");
               
                //recebe a string com elementos separados, vindos do PHP
                var string_array = msg;

                //transforma esta string em um array próprio do Javascript
                var array_dados = string_array.split("|");

                //insere nos inputs os valores retornados do banco
                $('#cad_administrador_nome').val(array_dados[0]);
                $('#cad_administrador_sobrenome').val(array_dados[1]);
                $('#cad_administrador_email').val(array_dados[2]);
                $('#cad_administrador_nascimento').val(array_dados[3]);
                $('#cad_administrador_cpf').val(array_dados[4]);
               

            }//Fim do else
       }//Fim do sucess
    });//Fim do ajax

    

});


//--------------------------------------Máscaras---------------------------------------------------

$("#cad_administrador_matricula").mask("999999999");
$("#cad_administrador_nascimento").mask("99/99/9999",{placeholder:" "});
</script>


<p class="heading">Cadastro de Administrador</p>

<!-- Botões-->
<div id="button">
        <ul class="icons ui-widget">
            <!-- Botão Adicionar -->
	    <li id="bt_add_cad_administrador"onClick="" class="ui-state-default ui-corner-all" title="Novo">
                 <span class="ui-icon ui-icon-plusthick"></span>
                 <span>Novo</span>
            </li>

             <!-- Botão Editar -->
	    <li id="bt_editar_cad_administrador" onClick="" class="ui-state-default ui-corner-all" title="Editar">
	         <span class="ui-icon ui-icon-pencil"></span>
	         <span>Editar</span>
	    </li>

                    <!-- Botão Apagar -->
	    <li id="bt_apagar_cad_administrador" onClick="" class="ui-state-default ui-corner-all" title="Editar">
	         <span class="ui-icon ui-icon-trash"></span>
	         <span>Apagar</span>
	    </li>

	</ul>
</div>


<!-- Formulário de cadastro e edição-->
<div id="cad_adm" style="display:none;">
    <form class="dialog-form" id="form_cad_administrador" >
        <fieldset class="ui-widget ui-widget-content ui-corner-all">
		<legend class="ui-widget ui-widget-header ui-corner-all">Administrador</legend>


                    <label>Matrícula</label>
                    <input type="text" maxlength="9" size="9" name="cad_administrador_matricula" id="cad_administrador_matricula" class="text ui-widget-content ui-corner-all" />

                    <input type="button" id="bt_ok_busca_admin" value="OK" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"/><br/>

                    <div id="msg"></div> 

                    <label>CPF</label>
                    <input type="text" maxlength="11" size="11" name="cad_administrador_cpf" id="cad_administrador_cpf" class="text ui-widget-content ui-corner-all aux" /><br/>

                    <label>Nome</label>
                    <input type="text" size="30"  name="cad_administrador_nome" id="cad_administrador_nome" class="text ui-widget-content ui-corner-all aux" /><br/>

                    <label>Sobrenome</label>
                    <input type="text" size="30" name="cad_administrador_sobrenome" id="cad_administrador_sobrenome" class="text ui-widget-content ui-corner-all aux" /><br/>
                   
                    <label>E-mail</label>
                    <input type="text"  size="30" name="cad_administrador_email" id="cad_administrador_email" class="text ui-widget-content ui-corner-all aux" /><br/>

                    <label>Nascimento</label>
                    <input type="text" size="10" name="cad_administrador_nascimento" id="cad_administrador_nascimento" class="text ui-widget-content ui-corner-all aux" /><br/>

                    <input type="text" id="cad_administrador_id" name="cad_administrador_id" style="display:none;">

                    <div id="dialog-form_button"
                        <input type="button" value="OK" id="bt_ok_cad_administrador" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">
                        <input type="button" value="Cancelar" id="bt_cancelar_cad_administrador" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">
                    </div>
        </fieldset>
    </form>
  </div>


 <!--Exibe a Grid-->
<div id="form">
    <table id="list_cad_administrador" class="scroll" cellpadding="0" cellspacing="0"> </table>
    <div id="pager_cad_administrador" class="scroll" style="text-align:center;"></div>
</div>



