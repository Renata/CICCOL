<?php /* Smarty version 2.6.26, created on 2010-05-31 12:06:24
         compiled from cad_grade_1.tpl */ ?>
<script type="text/javascript">

// ------------------------------------Grid Grade---------------------------
var lastsel;
jQuery("#list_cad_grade").jqGrid({
    url:'libs/lib_cad_grade.php?reference=grade&action=grid_buscar_grade',
    width: 530,
    datatype: "xml",
    colNames:['Data de Implantação'],
    colModel:[
        {name:'cad_grade_dt_implantacao',index:'cad_grade_dt_implantacao', width:150,sortable:false,align:"left"}
    ],
    rowNum:10,
    rowList:[10,20,30],
    pager: '#pager_cad_grade',
    sortname: '',
    viewrecords: true,
    sortorder: "desc",
    imgpath: 'themes/steel/images',
    onSelectRow: function(id){
        if(id && id!=lastsel){
            $('#list_cad_grade').restoreRow(lastsel);

            lastsel=id;
        }
    },
    //editurl: "local",
    caption: "Grade Acadêmica"
});


 //-----------------------------------Ação sobre o botão Novo ----------------------------

    $('#bt_add_cad_grade').click(function(){

       //Reseta o formulário
       $('#form_cad_grade').each(function(){
	        this.reset();
       });

       //Abre formulário
       $("#cad_adm").show();
    });

//--------------------------------------Ação sobre o botão Editar ----------------------------

    $('#bt_editar_cad_grade').click(function(){


       //Reseta o formulário
       $('#form_cad_grade').each(function(){
	        this.reset();
       });

       //Pega o id e armazena em gsr
       var gsr = $("#list_cad_grade").getGridParam('selrow');
       $("#cad_grade_id").val(gsr);
       
       if(gsr){

           //Abre formulário
            $("#cad_adm").show();

           //Pega os valores da grid e coloca no formulário
           $("#list_cad_grade").GridToForm(gsr,"#form_cad_grade");

       }else{
           alert("Selecione uma linha!");
       }
    });


// ----------------------------------------Ação sobre o botão Apagar----------------------------------------

    $('#bt_apagar_cad_grade').click(function(){

        //Esconde formulário
        $("#cad_adm").hide();

        //Pega o id e armazena em gsr
        var gsr = $("#list_cad_grade").getGridParam('selrow');
        var dataString = 'cad_id='+ gsr;
        
        //Envia o id correspondente a linha que será apagada no banco
        if(gsr){
            $.ajax({
                type: "GET",
                url: "libs/lib_cad_grade.php?reference=grade&action=apagar_grade",
                processData: false,
                data: dataString,
                success: function(){
                    $("#list_cad_grade").trigger("reloadGrid");
               }
            });
        }else{
            alert("Selecione uma linha!");
            }
    });

//-------------------------------------------- Ação sobre o botão OK --------------------------------------

  $('#bt_ok_cad_grade').click(function(){

            //Pega os valores do formulário
            var dt_implantacao = $("#cad_grade_dt_implantacao").val();
            var id_cad = $("#cad_grade_id").val();

            //Armazena os valores do formulário na variável dataString
            var dataString = 'dt_implantacao=' + dt_implantacao + '&cad_id=' + id_cad;


            //Defique qual action será passada na url
            if (id_cad=="")
                var opcao= 'inserir_grade';
            else
                var opcao='update_grade';

            //Envia a variável dataString para a lib que insere no banco de dados
            $.ajax({
                    type: "GET",
                    url: "libs/lib_cad_grade.php?reference=grade&action="+ opcao,
                    processData: false,
                    data: dataString,
                    //dataType: "html",
                    success: function(msg){
                        alert(msg);
                     $("#cad_adm").hide();
                     $("#list_cad_grade").trigger("reloadGrid");
                     }
                });

    })

//------------------------------------ Ação sobre o botão Cancelar ----------------------

$('#bt_cancelar_cad_grade').click(function(){
    //Esconde formulário
    $("#cad_adm").hide();

})

$(function() {

                $("#cad_grade_dt_implantacao").datepicker({
                    changeMonth: true,
                    changeYear: true

                });

});

//--------------------------------------Máscaras---------------------------------------------------

/* Na máscara criada o 9 representa qualquer número de 0 a 9 */
//$("#cad_grade_dt_implantacao").mask("99/99/9999",{placeholder:" "});


</script>


<p class="heading">Cadastro da Grade Acadêmica</p>

<!-- Botões-->
<div id="button">
        <ul class="icons ui-widget">
            <!-- Botão Adicionar -->
	    <li id="bt_add_cad_grade" onClick="" class="ui-state-default ui-corner-all" title="Novo">
                 <span class="ui-icon ui-icon-plusthick"></span>
                 <span>Novo</span>
            </li>

             <!-- Botão Editar -->
	    <li id="bt_editar_cad_grade" onClick="" class="ui-state-default ui-corner-all" title="Editar">
	         <span class="ui-icon ui-icon-pencil"></span>
	         <span>Editar</span>
	    </li>

                    <!-- Botão Apagar -->
	    <li id="bt_apagar_cad_grade" onClick="" class="ui-state-default ui-corner-all" title="Editar">
	         <span class="ui-icon ui-icon-trash"></span>
	         <span>Apagar</span>
	    </li>

	</ul>
</div>


<!-- Formulário de cadastro e edição-->
<div id="cad_adm" style="display:none;">
    <form class="dialog-form" id="form_cad_aluno" >
        <fieldset class="ui-widget ui-widget-content ui-corner-all">
		<legend class="ui-widget ui-widget-header ui-corner-all">Implantação da Nova Grade Acadêmica</legend>

                    <label>Data</label>
                    <input type="text" size="10" name="cad_grade_dt_implantacao" id="cad_grade_dt_implantacao" class="text ui-widget-content ui-corner-all" /><br/>

                    <input type="text" id="cad_grade_id" name="cad_grade_id" style="display:none;">

                    <div id="dialog-form_button"
                        <input type="button" value="OK" id="bt_ok_cad_grade" title="OK" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">
                        <input type="button" value="Cancelar" id="bt_cancelar_cad_grade" title="Cancelar" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">
                    </div>
        </fieldset>
    </form>
  </div>


 <!--Exibe a Grid-->
<div id="form">
    <table id="list_cad_grade" class="scroll" cellpadding="0" cellspacing="0"> </table>
    <div id="pager_cad_grade" class="scroll" style="text-align:center;"></div>
</div>


