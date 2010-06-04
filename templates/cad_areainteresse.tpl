<script type="text/javascript" charset="ISO-8859-1">

// ------------------------------------Grid Area de Interesse---------------------------

var lastsel;
jQuery("#list_cad_areainteresse").jqGrid({
    url: "libs/lib_cad_areainteresse.php?reference=areainteresse&action=grid_buscar_areainteresse",
    width: 490,
    datatype: "xml",
    colNames:['Descriçao'],
    colModel:[
        {name:'cad_areainteresse_descricao',index:'cad_areainteresse_descricao', width:50,align:"center"}

    ],
    rowNum:10,
    rowList:[10,20,30],
    pager: '#pager_cad_areainteresse',
    sortname: 'r',
    viewrecords: true,
    sortorder: "desc",
    imgpath: 'themes/steel/images',
    onSelectRow: function(id){
        if(id && id!=lastsel){
            $('#list_cad_areainteresse').restoreRow(lastsel);
            lastsel=id;
        }
    },
    caption: "Areas de Interesse"
});


//-----------------------------------Ação sobre o botão Novo ----------------------------

    $('#bt_add_cad_areainteresse').click(function(){

       //Reseta o formulário
       $('#form_cad_areainteresse').each(function(){
	        this.reset();
       });

       //Abre formulário
       $("#cad_adm").show();
    });

  //--------------------------------------Ação sobre o botão Editar ----------------------------

    $('#bt_editar_cad_areainteresse').click(function(){


       //Reseta o formulário
       $('#form_cad_areainteresse').each(function(){
	        this.reset();
       });

       //Pega o id e armazena em gsr
       var gsr = $("#list_cad_areainteresse").getGridParam('selrow');
       $("#cad_areainteresse_id").val(gsr);

       if(gsr){

           //Abre formulário
            $("#cad_adm").show();

           //Pega os valores da grid e coloca no formuário
           $("#list_cad_areainteresse").GridToForm(gsr,"#form_cad_areainteresse");

       }else{
           alert("Selecione uma linha!");
       }
    });


 // ----------------------------------------Ação sobre o botão Apagar----------------------------------------

    $('#bt_apagar_cad_areainteresse').click(function(){

        //Esconde formulário
        $("#cad_adm").hide();

        //Pega o id e armazena em gsr
        var gsr = $("#list_cad_areainteresse").getGridParam('selrow');
        var dataString = 'cad_id='+ gsr;

        //Envia o id correspondente a linha que será apagada no banco
        if(gsr){
            $.ajax({
                type: "GET",
                url: "libs/lib_cad_areainteresse.php?reference=areainteresse&action=apagar_areainteresse",
                processData: false,
                data: dataString,
                success: function(){
                    $("#list_cad_areainteresse").trigger("reloadGrid");
               }
            });
        }else{
            alert("Selecione uma linha!");
            }
    });


//-------------------------------------------- Ação sobre o botão OK --------------------------------------

  $('#bt_ok_cad_areainteresse').click(function(){

            //Pega os valores do formulário
            var descricao = $("#cad_areainteresse_descricao").val();
            var id_cad = $("#cad_areainteresse_id").val();
          
            //Armazena os valores do formulário na variável dataString
           var dataString = 'descricao=' + descricao + '&cad_id=' + id_cad;
           
            //Defique qual action será passada na url
            if (id_cad=="")
                var opcao= 'inserir_areainteresse';
            else
                var opcao='update_areainteresse';

            //Envia a variável dataString para a lib que insere no banco de dados
            $.ajax({
                    type: "GET",
                    url: "libs/lib_cad_areainteresse.php?reference=areainteresse&action="+ opcao,
                    processData: false,
                    data: dataString,
                    //dataType: "html",
                    success: function(msg){
                        alert(msg);
                     $("#cad_adm").hide();
                     $("#list_cad_areainteresse").trigger("reloadGrid");
                     }
                });

    })

//------------------------------------ Ação sobre o botão Cancelar ----------------------

$('#bt_cancelar_cad_areainteresse').click(function(){
    //Esconde formulário
    $("#cad_adm").hide();

})



</script>



<p class="heading">Cadastro de Áreas de Interesse</p>

<!-- Botões-->
<div id="button">
        <ul class="icons ui-widget">
            <!-- Botão Adicionar -->
	    <li id="bt_add_cad_areainteresse" onClick="" class="ui-state-default ui-corner-all" title="Novo">
                 <span class="ui-icon ui-icon-plusthick"></span>
                 <span>Novo</span>
            </li>

             <!-- Botão Editar -->
	    <li id="bt_editar_cad_areainteresse" onClick="" class="ui-state-default ui-corner-all" title="Editar">
	         <span class="ui-icon ui-icon-pencil"></span>
	         <span>Editar</span>
	    </li>

                    <!-- Botão Apagar -->
	    <li id="bt_apagar_cad_areainteresse" onClick="" class="ui-state-default ui-corner-all" title="Editar">
	         <span class="ui-icon ui-icon-trash"></span>
	         <span>Apagar</span>
	    </li>

	</ul>
</div>

<!-- Formulário de cadastro e edição-->
<div id="cad_adm" style="display:none;">
    <form class="dialog-form" id="form_cad_areainteresse" >
        <fieldset class="ui-widget ui-widget-content ui-corner-all">
		<legend class="ui-widget ui-widget-header ui-corner-all">Disciplina</legend>


                    <label>Descrição</label>
                    <input type="text" id="cad_areainteresse_descricao" name="cad_areainteresse_descricao"  class="text ui-widget-content ui-corner-all"><br/><br/>


                    <input type="text" id="cad_areainteresse_id" name="cad_disciplina_id" style="display:none;">

                    <div id="dialog-form_button"
                        <input type="button" value="OK" id="bt_ok_cad_areainteresse" title="OK" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">
                        <input type="button" value="Cancelar" id="bt_cancelar_cad_areainteresse" title="Cancelar" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">
                    </div>
        </fieldset>
    </form>
  </div>


 <!--Exibe a Grid-->
<div id="form">
    <table id="list_cad_areainteresse" class="scroll" cellpadding="0" cellspacing="0"> </table>
    <div id="pager_cad_areainteresse" class="scroll" style="text-align:center;"></div>
</div>



