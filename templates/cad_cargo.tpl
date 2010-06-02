<script type="text/javascript">

// ------------------------------------Grid Cargo---------------------------
var lastsel;
jQuery("#list_cad_cargo").jqGrid({
    url:'libs/lib_cargo.php?reference=cargo&action=grid_buscar_cargo',
    width: 530,
    datatype: "xml",
    colNames:['Cargo'],
    colModel:[
        {name:'cad_cargo_descricao',index:'cad_cargo_descricao', width:150,sortable:false,align:"left"}
    ],
    rowNum:10,
    rowList:[10,20,30],
    pager: '#pager_cad_cargo',
    sortname: '',
    viewrecords: true,
    sortorder: "desc",
    imgpath: 'themes/steel/images',
    onSelectRow: function(id){
        if(id && id!=lastsel){
            $('#list_cad_cargo').restoreRow(lastsel);

            lastsel=id;
        }
    },
    //editurl: "local",
    caption: "Cargo"
});


 //-----------------------------------Ação sobre o botão Novo ----------------------------

    $('#bt_add_cad_cargo').click(function(){

       //Reseta o formulário
       $('#form_cad_cargo').each(function(){
	        this.reset();
       });

       //Abre formulário
       $("#cad_adm").show();
    });

//--------------------------------------Ação sobre o botão Editar ----------------------------

    $('#bt_editar_cad_cargo').click(function(){


       //Reseta o formulário
       $('#form_cad_cargo').each(function(){
	        this.reset();
       });

       //Pega o id e armazena em gsr
       var gsr = $("#list_cad_cargo").getGridParam('selrow');
       $("#cad_cargo_id").val(gsr);
       
       if(gsr){

           //Abre formulário
            $("#cad_adm").show();

           //Pega os valores da grid e coloca no formuário
           $("#list_cad_cargo").GridToForm(gsr,"#form_cad_cargo");

       }else{
           alert("Selecione uma linha!");
       }
    });


// ----------------------------------------Ação sobre o botão Apagar----------------------------------------

    $('#bt_apagar_cad_cargo').click(function(){

        //Esconde formulário
        $("#cad_adm").hide();

        //Pega o id e armazena em gsr
        var gsr = $("#list_cad_cargo").getGridParam('selrow');
        var dataString = 'cad_id='+ gsr;
        
        //Envia o id correspondente a linha que será apagada no banco
        if(gsr){
            $.ajax({
                type: "GET",
                url: "libs/lib_cargo.php?reference=cargo&action=apagar_cargo",
                processData: false,
                data: dataString,
                success: function(){
                    $("#list_cad_cargo").trigger("reloadGrid");
               }
            });
        }else{
            alert("Selecione uma linha!");
            }
    });

//-------------------------------------------- Ação sobre o botão OK --------------------------------------

  $('#bt_ok_cad_cargo').click(function(){

            //Pega os valores do formulário
            var descricao = $("#cad_cargo_descricao").val();
            var id_cad = $("#cad_cargo_id").val();

            //Armazena os valores do formulário na variável dataString
            var dataString = 'descricao=' + descricao + '&cad_id=' + id_cad;


            //Defique qual action será passada na url
            if (id_cad=="")
                var opcao= 'inserir_cargo';
            else
                var opcao='update_cargo';

            //Envia a variável dataString para a lib que insere no banco de dados
            $.ajax({
                    type: "GET",
                    url: "libs/lib_cargo.php?reference=cargo&action="+ opcao,
                    processData: false,
                    data: dataString,
                    success: function(msg){
                        alert(msg);
                     $("#cad_adm").hide();
                     $("#list_cad_cargo").trigger("reloadGrid");
                     }
                });

    })

//------------------------------------ Ação sobre o botão Cancelar ----------------------

$('#bt_cancelar_cad_cargo').click(function(){
    //Esconde formulário
    $("#cad_adm").hide();

})


</script>


<p class="heading">Cadastro de Cargos</p>

<!-- Botões-->
<div id="button">
        <ul class="icons ui-widget">
            <!-- Botão Adicionar -->
	    <li id="bt_add_cad_cargo" onClick="" class="ui-state-default ui-corner-all" title="Novo">
                 <span class="ui-icon ui-icon-plusthick"></span>
                 <span>Novo</span>
            </li>

             <!-- Botão Editar -->
	    <li id="bt_editar_cad_cargo" onClick="" class="ui-state-default ui-corner-all" title="Editar">
	         <span class="ui-icon ui-icon-pencil"></span>
	         <span>Editar</span>
	    </li>

                    <!-- Botão Apagar -->
	    <li id="bt_apagar_cad_cargo" onClick="" class="ui-state-default ui-corner-all" title="Editar">
	         <span class="ui-icon ui-icon-trash"></span>
	         <span>Apagar</span>
	    </li>

	</ul>
</div>


<!-- Formulário de cadastro e edição-->
<div id="cad_adm" style="display:none;">
    <form class="dialog-form" id="form_cad_cargo" >
        <fieldset class="ui-widget ui-widget-content ui-corner-all">
		<legend class="ui-widget ui-widget-header ui-corner-all">Cargo</legend>

                    <label>Nome</label>
                    <input type="text" size="30" name="cad_cargo_descricao" id="cad_cargo_descricao" class="text ui-widget-content ui-corner-all" /><br/>
                    <input type="text" id="cad_cargo_id" name="cad_cargo_id" style="display:none;">

                    <div id="dialog-form_button"
                        <input type="button" value="OK" id="bt_ok_cad_cargo" title="OK" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">
                        <input type="button" value="Cancelar" id="bt_cancelar_cad_cargo" title="Cancelar" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">
                    </div>
        </fieldset>
    </form>
  </div>


 <!--Exibe a Grid-->
<div id="form">
    <table id="list_cad_cargo" class="scroll" cellpadding="0" cellspacing="0"> </table>
    <div id="pager_cad_cargo" class="scroll" style="text-align:center;"></div>
</div>



