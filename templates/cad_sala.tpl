<script type="text/javascript" charset="ISO-8859-1">

// ------------------------------------Grid Disciplina---------------------------

var lastsel;
jQuery("#list_cad_sala").jqGrid({
    url: "libs/lib_sala.php?reference=sala&action=grid_buscar_sala",
    width: 490,
    datatype: "xml",
    colNames:['Numero','Localização'],
    colModel:[
        {name:'cad_sala_numero',index:'cad_sala_numero', width:30,align:"center"},
        {name:'cad_sala_localizacao',index:'cad_sala_localizacao', width:150,align:"center"},
    ],
    rowNum:10,
    rowList:[10,20,30],
    pager: '#pager_cad_sala',
    sortname: 'r',
    viewrecords: true,
    sortorder: "desc",
    imgpath: 'themes/steel/images',
    onSelectRow: function(id){
        if(id && id!=lastsel){
            $('#list_cad_sala').restoreRow(lastsel);
            lastsel=id;
        }
    },
    //editurl: "local",
    caption: "Salas"
});


//-----------------------------------Ação sobre o botão Novo ----------------------------

    $('#bt_add_cad_sala').click(function(){

       //Reseta o formulário
       $('#form_cad_sala').each(function(){
	        this.reset();
       });
     
       //Abre formulário
       $("#cad_adm").show();
    });

  //--------------------------------------Ação sobre o botão Editar ----------------------------

    $('#bt_editar_cad_sala').click(function(){


       //Reseta o formulário
       $('#form_cad_sala').each(function(){
	        this.reset();
       });

       //Pega o id e armazena em gsr
       var gsr = $("#list_cad_sala").getGridParam('selrow');
       $("#cad_sala_id").val(gsr);

       if(gsr){

           //Abre formulário
            $("#cad_adm").show();

           //Pega os valores da grid e coloca no formuário
           $("#list_cad_sala").GridToForm(gsr,"#form_cad_sala");

       }else{
           alert("Selecione uma linha!");
       }
    });


 // ----------------------------------------Ação sobre o botão Apagar----------------------------------------

    $('#bt_apagar_cad_sala').click(function(){

        //Esconde formulário
        $("#cad_adm").hide();

        //Pega o id e armazena em gsr
        var gsr = $("#list_cad_sala").getGridParam('selrow');
        var dataString = 'cad_id='+ gsr;

        //Envia o id correspondente a linha que será apagada no banco
        if(gsr){
            $.ajax({
                type: "GET",
                url: "libs/lib_sala.php?reference=sala&action=apagar_sala",
                processData: false,
                data: dataString,
                success: function(){
                    $("#list_cad_sala").trigger("reloadGrid");
               }
            });
        }else{
            alert("Selecione uma linha!");
            }
    });


//-------------------------------------------- Ação sobre o botão OK --------------------------------------

  $('#bt_ok_cad_sala').click(function(){

            //Pega os valores do formulário
            var numero = $("#cad_sala_numero").val();
            var localizacao = $("#cad_sala_localizacao").val();
            var id_cad = $("#cad_sala_id").val();


            //Armazena os valores do formulário na variável dataString
            var dataString = 'numero=' + numero + '&localizacao=' + localizacao + '&cad_id=' + id_cad;


            //Defique qual action será passada na url
            if (id_cad=="")
                var opcao= 'inserir_sala';
            else
                var opcao='update_sala';

            //Envia a variável dataString para a lib que insere no banco de dados
            $.ajax({
                    type: "GET",
                    url: "libs/lib_sala.php?reference=sala&action="+ opcao,
                    processData: false,
                    data: dataString,
                    //dataType: "html",
                    success: function(){
                        //alert(msg);
                     $("#cad_adm").hide();
                     $("#list_cad_sala").trigger("reloadGrid");
                     }
                });

    })

//------------------------------------ Ação sobre o botão Cancelar ----------------------

$('#bt_cancelar_cad_sala').click(function(){
    //Esconde formulário
    $("#cad_adm").hide();

})


</script>



<p class="heading">Cadastro de Salas</p>

<!-- Botões-->
<div id="button">
        <ul class="icons ui-widget">
            <!-- Botão Adicionar -->
	    <li id="bt_add_cad_sala" onClick="" class="ui-state-default ui-corner-all" title="Novo">
                 <span class="ui-icon ui-icon-plusthick"></span>
                 <span>Novo</span>
            </li>

             <!-- Botão Editar -->
	    <li id="bt_editar_cad_sala" onClick="" class="ui-state-default ui-corner-all" title="Editar">
	         <span class="ui-icon ui-icon-pencil"></span>
	         <span>Editar</span>
	    </li>

                    <!-- Botão Apagar -->
	    <li id="bt_apagar_cad_sala" onClick="" class="ui-state-default ui-corner-all" title="Editar">
	         <span class="ui-icon ui-icon-trash"></span>
	         <span>Apagar</span>
	    </li>

	</ul>
</div>

<!-- Formulário de cadastro e edição-->
<div id="cad_adm" style="display:none;">
    <form class="dialog-form" id="form_cad_sala" >
        <fieldset class="ui-widget ui-widget-content ui-corner-all">
		<legend class="ui-widget ui-widget-header ui-corner-all">Sala</legend>

                    <label>Número</label>
                    <input type="text" size="6" name="cad_sala_numero" id="cad_sala_numero" class="text ui-widget-content ui-corner-all" /><br/>
                    
                    <label>Localização</label>
                    <input type="text" size="30" name="cad_sala_localizacao" id="cad_sala_localizacao" class="text ui-widget-content ui-corner-all" /><br/>

                    <input type="text" id="cad_sala_id" name="cad_sala_id" style="display:none;">

                    <div id="dialog-form_button"
                        <input type="button" value="OK" id="bt_ok_cad_sala" title="OK" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">
                        <input type="button" value="Cancelar" id="bt_cancelar_cad_sala" title="Cancelar" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">
                    </div>
        </fieldset>
    </form>
  </div>


 <!--Exibe a Grid-->
<div id="form">
    <table id="list_cad_sala" class="scroll" cellpadding="0" cellspacing="0"> </table>
    <div id="pager_cad_sala" class="scroll" style="text-align:center;"></div>
</div>






