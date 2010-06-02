<script type="text/javascript">

// ------------------------------------Grid materia---------------------------
var lastsel;
jQuery("#list_cad_materia").jqGrid({
    url:'libs/lib_materia.php?reference=materia&action=grid_buscar_materia',
    width: 530,
    datatype: "xml",
    colNames:['Materia'],
    colModel:[
        {name:'cad_materia_nome',index:'cad_materia_nome', width:150,sortable:false,align:"left"}
    ],
    rowNum:10,
    rowList:[10,20,30],
    pager: '#pager_cad_materia',
    sortname: '',
    viewrecords: true,
    sortorder: "desc",
    imgpath: 'themes/steel/images',
    onSelectRow: function(id){
        if(id && id!=lastsel){
            $('#list_cad_materia').restoreRow(lastsel);

            lastsel=id;
        }
    },
    //editurl: "local",
    caption: "Materia"
});


 //-----------------------------------Ação sobre o botão Novo ----------------------------

    $('#bt_add_cad_materia').click(function(){

       //Reseta o formulário
       $('#form_cad_materia').each(function(){
	        this.reset();
       });

       //Abre formulário
       $("#cad_adm").show();
    });

//--------------------------------------Ação sobre o botão Editar ----------------------------

    $('#bt_editar_cad_materia').click(function(){


       //Reseta o formulário
       $('#form_cad_materia').each(function(){
	        this.reset();
       });

       //Pega o id e armazena em gsr
       var gsr = $("#list_cad_materia").getGridParam('selrow');
       $("#cad_materia_id").val(gsr);
       
       if(gsr){

           //Abre formulário
            $("#cad_adm").show();

           //Pega os valores da grid e coloca no formuário
           $("#list_cad_materia").GridToForm(gsr,"#form_cad_materia");

       }else{
           alert("Selecione uma linha!");
       }
    });


// ----------------------------------------Ação sobre o botão Apagar----------------------------------------

    $('#bt_apagar_cad_materia').click(function(){

        //Esconde formulário
        $("#cad_adm").hide();

        //Pega o id e armazena em gsr
        var gsr = $("#list_cad_materia").getGridParam('selrow');
        var dataString = 'cad_id='+ gsr;
        
        //Envia o id correspondente a linha que será apagada no banco
        if(gsr){
            $.ajax({
                type: "GET",
                url: "libs/lib_materia.php?reference=materia&action=apagar_materia&action=apagar_materia",
                processData: false,
                data: dataString,
                success: function(){
                    $("#list_cad_materia").trigger("reloadGrid");
               }
            });
        }else{
            alert("Selecione uma linha!");
            }
    });

//-------------------------------------------- Ação sobre o botão OK --------------------------------------

  $('#bt_ok_cad_materia').click(function(){

            //Pega os valores do formulário
            var nome = $("#cad_materia_nome").val();
            var id_cad = $("#cad_materia_id").val();

            //Armazena os valores do formulário na variável dataString
            var dataString = 'nome=' + nome + '&cad_id=' + id_cad;


            //Defique qual action será passada na url
            if (id_cad=="")
                var opcao= 'inserir_materia';
            else
                var opcao='update_materia';

            //Envia a variável dataString para a lib que insere no banco de dados
            $.ajax({
                    type: "GET",
                    url: "libs/lib_materia.php?reference=materia&action="+ opcao,
                    processData: false,
                    data: dataString,
                    //dataType: "html",
                    success: function(msg){
                        alert(msg);
                     $("#cad_adm").hide();
                     $("#list_cad_materia").trigger("reloadGrid");
                     }
                });

    })

//------------------------------------ Ação sobre o botão Cancelar ----------------------

$('#bt_cancelar_cad_materia').click(function(){
    //Esconde formulário
    $("#cad_adm").hide();

})


</script>


<p class="heading">Cadastro de Materias</p>

<!-- Botões-->
<div id="button">
        <ul class="icons ui-widget">
            <!-- Botão Adicionar -->
	    <li id="bt_add_cad_materia" onClick="" class="ui-state-default ui-corner-all" title="Novo">
                 <span class="ui-icon ui-icon-plusthick"></span>
                 <span>Novo</span>
            </li>

             <!-- Botão Editar -->
	    <li id="bt_editar_cad_materia" onClick="" class="ui-state-default ui-corner-all" title="Editar">
	         <span class="ui-icon ui-icon-pencil"></span>
	         <span>Editar</span>
	    </li>

                    <!-- Botão Apagar -->
	    <li id="bt_apagar_cad_materia" onClick="" class="ui-state-default ui-corner-all" title="Editar">
	         <span class="ui-icon ui-icon-trash"></span>
	         <span>Apagar</span>
	    </li>

	</ul>
</div>


<!-- Formulário de cadastro e edição-->
<div id="cad_adm" style="display:none;">
    <form class="dialog-form" id="form_cad_materia" >
        <fieldset class="ui-widget ui-widget-content ui-corner-all">
		<legend class="ui-widget ui-widget-header ui-corner-all">Materia</legend>

                    <label>Nome</label>
                    <input type="text" size="30" name="cad_materia_nome" id="cad_materia_nome" class="text ui-widget-content ui-corner-all" /><br/>
                    <input type="text" id="cad_materia_id" name="cad_materia_id" style="display:none;">

                    <div id="dialog-form_button"
                        <input type="button" value="OK" id="bt_ok_cad_materia" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">
                        <input type="button" value="Cancelar" id="bt_cancelar_cad_materia" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">
                    </div>
        </fieldset>
    </form>
  </div>


 <!--Exibe a Grid-->
<div id="form">
    <table id="list_cad_materia" class="scroll" cellpadding="0" cellspacing="0"> </table>
    <div id="pager_cad_materia" class="scroll" style="text-align:center;"></div>
</div>



