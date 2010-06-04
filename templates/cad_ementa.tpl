<script type="text/javascript" charset="ISO-8859-1">

// ------------------------------------Grid Ementa---------------------------

var lastsel;
jQuery("#list_cad_ementa").jqGrid({
    url: "libs/lib_cad_ementa.php?reference=ementa&action=grid_buscar_ementa",
    width: 490,
    datatype: "xml",
    colNames:['Ementa','Disciplina', 'Descriçao'],
    colModel:[
        {name:'ver',index:'ver', width:30,align:"center"},
        {name:'cad_ementa_disciplina',index:'cad_ementa_disciplina', width:50,align:"center"},
        {name:'cad_ementa_ementa',index:'cad_ementa_ementa', width:50,align:"center"}

    ],
    rowNum:10,
    rowList:[10,20,30],
    pager: '#pager_cad_ementa',
    sortname: 'r',
    viewrecords: true,
    sortorder: "desc",
    imgpath: 'themes/steel/images',
    onSelectRow: function(id){
        if(id && id!=lastsel){
            $('#list_cad_ementa').restoreRow(lastsel);
            lastsel=id;
        }
    },
    gridComplete: function(){
        var ids = $("#list_cad_ementa").getDataIDs();
        for(var i=0;i < ids.length;i++){
            var cl = ids[i];
            bv = "<input style='height:22px;width:80px;' type='button' id='bt_ver_ementa' value='Visualizar' onclick=\"$dialog.dialog('option', 'title', 'Nome da disciplina'); $dialog.dialog('open'); exibe(); \"  />";

            $("#list_cad_ementa").setRowData(ids[i],{ver:bv});

         }
    },
    //editurl: "local",
    caption: "Ementas"
});


var $dialog = $('#form_exibe_ementa').dialog({
        width:350,
        height:260,
        modal: true,
        autoOpen: false

    });

 function exibe(){
     $("#bt_ver_ementa").click(function(){
         var gsr = $("#list_cad_ementa").getGridParam('selrow');
       $("#list_cad_ementa").GridToForm(gsr,"#form_exibe_ementa");
       alert(gsr);

     })

    }

//-----------------------------------Ação sobre o botão Novo ----------------------------

    $('#bt_add_cad_ementa').click(function(){

       //Reseta o formulário
       $('#form_cad_ementa').each(function(){
	        this.reset();
       });

       //Abre formulário
       $("#cad_adm").show();
    });

  //--------------------------------------Ação sobre o botão Editar ----------------------------

    $('#bt_editar_cad_ementa').click(function(){


       //Reseta o formulário
       $('#form_cad_ementa').each(function(){
	        this.reset();
       });

       //Pega o id e armazena em gsr
       var gsr = $("#list_cad_ementa").getGridParam('selrow');
       $("#cad_ementa_id").val(gsr);

       if(gsr){

           //Abre formulário
            $("#cad_adm").show();

           //Pega os valores da grid e coloca no formuário
           $("#list_cad_ementa").GridToForm(gsr,"#form_cad_ementa");

       }else{
           alert("Selecione uma linha!");
       }
    });


 // ----------------------------------------Ação sobre o botão Apagar----------------------------------------

    $('#bt_apagar_cad_disciplina').click(function(){

        //Esconde formulário
        $("#cad_adm").hide();

        //Pega o id e armazena em gsr
        var gsr = $("#list_cad_disciplina").getGridParam('selrow');
        var dataString = 'cad_id='+ gsr;

        //Envia o id correspondente a linha que será apagada no banco
        if(gsr){
            $.ajax({
                type: "GET",
                url: "libs/lib_disciplina.php?reference=disciplina&action=apagar_disciplina",
                processData: false,
                data: dataString,
                success: function(){
                    $("#list_cad_disciplina").trigger("reloadGrid");
               }
            });
        }else{
            alert("Selecione uma linha!");
            }
    });


//-------------------------------------------- Ação sobre o botão OK --------------------------------------

  $('#bt_ok_cad_ementa').click(function(){

            //Pega os valores do formulário
            var disciplina = $("#cad_ementa_disciplina").val();
            var ementa = $("#cad_ementa_ementa").val();
            var id_cad = $("#cad_ementa_id").val();
          
            //Armazena os valores do formulário na variável dataString
           var dataString = 'disciplina=' + disciplina + '&ementa=' + ementa + '&cad_id=' + id_cad;
           
            //Defique qual action será passada na url
            if (id_cad=="")
                var opcao= 'inserir_ementa';
            else
                var opcao='update_ementa';

            //Envia a variável dataString para a lib que insere no banco de dados
            $.ajax({
                    type: "GET",
                    url: "libs/lib_cad_ementa.php?reference=ementa&action="+ opcao,
                    processData: false,
                    data: dataString,
                    //dataType: "html",
                    success: function(){
                        //alert(msg);
                     $("#cad_adm").hide();
                     $("#list_cad_ementa").trigger("reloadGrid");
                     }
                });

    })

//------------------------------------ Ação sobre o botão Cancelar ----------------------

$('#bt_cancelar_cad_ementa').click(function(){
    //Esconde formulário
    $("#cad_adm").hide();

})



</script>



<p class="heading">Cadastro de Ementas das Disciplinas</p>

<!-- Botões-->
<div id="button">
        <ul class="icons ui-widget">
            <!-- Botão Adicionar -->
	    <li id="bt_add_cad_ementa" onClick="" class="ui-state-default ui-corner-all" title="Novo">
                 <span class="ui-icon ui-icon-plusthick"></span>
                 <span>Novo</span>
            </li>

             <!-- Botão Editar -->
	    <li id="bt_editar_cad_ementa" onClick="" class="ui-state-default ui-corner-all" title="Editar">
	         <span class="ui-icon ui-icon-pencil"></span>
	         <span>Editar</span>
	    </li>

                    <!-- Botão Apagar -->
	    <li id="bt_apagar_cad_ementa" onClick="" class="ui-state-default ui-corner-all" title="Editar">
	         <span class="ui-icon ui-icon-trash"></span>
	         <span>Apagar</span>
	    </li>

	</ul>
</div>

<!-- Formulário de cadastro e edição-->
<div id="cad_adm" style="display:none;">
    <form class="dialog-form" id="form_cad_ementa" >
        <fieldset class="ui-widget ui-widget-content ui-corner-all">
		<legend class="ui-widget ui-widget-header ui-corner-all">Disciplina</legend>

                    <label>Disciplina</label>
                    <select id="cad_ementa_disciplina" name="cad_ementa_disciplina">
                        <option>Selecione</option>
                        <!--{section name=cont_disciplina loop=$disc}-->
                        <option><!--{$disc[cont_disciplina].disciplina}--></option>
                       <!--{/section}-->
                    </select><br/><br/>

                    <label>Ementa</label>
                    <textarea id="cad_ementa_ementa" name="cad_ementa_ementa" cols="28" rows="10" class="text ui-widget-content ui-corner-all aux"></textarea><br/><br/>


                    <input type="text" id="cad_ementa_id" name="cad_disciplina_id" style="display:none;">

                    <div id="dialog-form_button"
                        <input type="button" value="OK" id="bt_ok_cad_ementa" title="OK" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">
                        <input type="button" value="Cancelar" id="bt_cancelar_cad_ementa" title="Cancelar" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">
                    </div>
        </fieldset>
    </form>
  </div>


 <!--Exibe a Grid-->
<div id="form">
    <table id="list_cad_ementa" class="scroll" cellpadding="0" cellspacing="0"> </table>
    <div id="pager_cad_ementa" class="scroll" style="text-align:center;"></div>
</div>




    <form class="dialog-form" id="form_exibe_ementa" style="display:none; background-color: white;">
        <textarea name="cad_ementa_ementa" cols="28" rows="10" class="text ui-widget-content ui-corner-all aux"></textarea>

    </form>
 


