<?php /* Smarty version 2.6.26, created on 2010-05-24 22:57:01
         compiled from cad_perfil_celular.tpl */ ?>
<script type="text/javascript">
// ------------------------------------Grid celular---------------------------

var lastsel;
jQuery("#list_cad_celular").jqGrid({
    url:"libs/lib_celular.php?reference=celular&action=grid_buscar_celular",
    width: 500,
    height: 25,
    datatype: "xml",
   colNames:['DDD','Número','Operadora'],
    colModel:[
        {name:'cad_celular_ddd',index:'cad_celular_ddd', width:60,align:"center"},
        {name:'cad_celular_numero',index:'cad_celular_numero', width:120,align:"center"},
        {name:'cad_celular_operadora',index:'cad_celular_operadora', width:120,align:"center"},
        
    ],
    //rowNum:10,
    //rowList:[10,20,30],
    pager: '',
    sortname: 'id',
    //viewrecords: true,
    //sortorder: "desc",
    imgpath: 'themes/steel/images',
    onSelectRow: function(id){
        if(id && id!=lastsel){
            $('#list_cad_celular').restoreRow(lastsel);
            lastsel=id;
        }
    }
   // editurl: "local",
   // caption: "celular"
});

//-----------------------------------Ação sobre o botão Novo ----------------------------

    $('#bt_add_cad_celular').click(function(){

       //Reseta o formulário
       $('#form_cad_celular').each(function(){
	        this.reset();
       });

       //Abre formulário
       $("#cad_adm").show();
    });

//--------------------------------------Ação sobre o botão Editar ----------------------------

    $('#bt_editar_cad_celular').click(function(){


       //Reseta o formulário
       $('#form_cad_celular').each(function(){
	        this.reset();
       });

       //Pega o id e armazena em gsr
       var gsr = $("#list_cad_celular").getGridParam('selrow');
       $("#cad_celular_id").val(gsr);

       if(gsr){

           //Abre formulário
            $("#cad_adm").show();

           //Pega os valores da grid e coloca no formuário
           $("#list_cad_celular").GridToForm(gsr,"#form_cad_celular");

       }else{
           alert("Selecione uma linha!");
       }
    });


// ----------------------------------------Ação sobre o botão Apagar----------------------------------------

    $('#bt_apagar_cad_celular').click(function(){

        //Esconde formulário
        $("#cad_adm").hide();

        //Pega o id e armazena em gsr
        var gsr = $("#list_cad_celular").getGridParam('selrow');
        var dataString = 'cad_id='+ gsr;

        //Envia o id correspondente a linha que será apagada no banco
        if(gsr){
            $.ajax({
                type: "GET",
                url: "libs/lib_celular.php?reference=celular&action=apagar_celular",
                processData: false,
                data: dataString,
                success: function(){

                    $("#list_cad_celular").trigger("reloadGrid");
               }
            });
        }else{
            alert("Selecione uma linha!");
            }
    });

//-------------------------------------------- Ação sobre o botão OK --------------------------------------

  $('#bt_ok_cad_celular').click(function(){

            //Pega os valores do formulário
            var ddd = $("#cad_celular_ddd").val();
            var numero = $("#cad_celular_numero").val();
            var operadora = $("#cad_celular_operadora").val();
            var id_cad = $("#cad_celular_id").val();
            
            //Renomeia a variaável operadora
            if(operadora == "VIVO")
                operadora = "1"
            else if (operadora == "OI")
                operadora = "2";
            else if (operadora == "TIM")
                operadora = "3";
            else if (operadora == "CLARO")
                operadora = "4";
             alert(operadora);
            //Armazena os valores do formulário na variável dataString
            var dataString = 'ddd=' + ddd + '&numero=' + numero + '&operadora=' + operadora + '&cad_id=' + id_cad;

            //Defique qual action será passada na url
            if (id_cad=="")
                var opcao= 'inserir_celular';
            else
                var opcao='update_celular';

            //Envia a variável dataString para a lib que insere no banco de dados
            $.ajax({
                    type: "GET",
                    url: "libs/lib_celular.php?reference=celular&action="+ opcao,
                    processData: false,
                    data: dataString,
                    success: function(msg){
                        alert(msg);
                     $("#cad_adm").hide();
                     $("#list_cad_celular").trigger("reloadGrid");
                     }
                });

    })

//------------------------------------ Ação sobre o botão Cancelar ----------------------

$('#bt_cancelar_cad_celular').click(function(){
    //Esconde formulário
    $("#cad_adm").hide();

})

$(function() {

                $("#cad_celular_nascimento").datepicker({
                    changeMonth: true,
                    changeYear: true

                });

	});


//--------------------------------------Máscaras---------------------------------------------------

$("#cad_celular_ddd").mask("999",{placeholder:" "});
$("#cad_celular_numero").mask("99999999",{placeholder:" "});


</script>


<p class="heading">Cadastrar Celular</p>


<!-- Botões-->
<div id="button">
        <ul class="icons ui-widget">
            <!-- Botão Adicionar -->
	    <li id="bt_add_cad_celular" onClick="" class="ui-state-default ui-corner-all" title="Novo">
                 <span class="ui-icon ui-icon-plusthick"></span>
                 <span>Novo</span>
            </li>

             <!-- Botão Editar -->
	    <li id="bt_editar_cad_celular" onClick="" class="ui-state-default ui-corner-all" title="Editar">
	         <span class="ui-icon ui-icon-pencil"></span>
	         <span>Editar</span>
	    </li>

                    <!-- Botão Apagar -->
	    <li id="bt_apagar_cad_celular" onClick="" class="ui-state-default ui-corner-all" title="Editar">
	         <span class="ui-icon ui-icon-trash"></span>
	         <span>Apagar</span>
	    </li>

	</ul>
</div>


<!-- Formulário de cadastro e edição-->
<div id="cad_adm" style="display:none;">
    <form class="dialog-form" id="form_cad_celular" >
        <fieldset class="ui-widget ui-widget-content ui-corner-all">
		<legend class="ui-widget ui-widget-header ui-corner-all">Celular</legend>

                    <label>DDD</label>
                    <input type="text" name="cad_celular_ddd" id="cad_celular_ddd" maxlength="3" size="3" class="text ui-widget-content ui-corner-all" /><br/>

                    <label>Numero</label>
                    <input type="text" id="cad_celular_numero" name="cad_celular_numero" maxlength="8" size="8" class="text ui-widget-content ui-corner-all" /><br/>

                    <label>Operadora</label>
                    <select name="cad_celular_operadora" id="cad_celular_operadora" size="1">
                        <option selected>- - -</option>
                        <option>OI</option>
                        <option>VIVO</option>
                        <option>CLARO</option>
                        <option>TIM</option>
                    </select><br/><br/>

                    <input type="text" id="cad_celular_id" name="cad_celular_id" style="display:none;">
                   
                    <div id="dialog-form_button"
                        <input type="button" value="OK" id="bt_ok_cad_celular" title="OK" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">
                        <input type="button" value="Cancelar" id="bt_cancelar_cad_celular" title="Cancelar" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">

                    </div>
        </fieldset>
    </form>
  </div>



<!--Exibe a Grid-->
<div id="form">
    <table id="list_cad_celular" class="scroll" cellpadding="0" cellspacing="0"> </table>
    <div id="pager_cad_celular" class="scroll" style="text-align:center;"></div>
</div>