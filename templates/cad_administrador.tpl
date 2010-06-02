<script type="text/javascript">

// ------------------------------------Grid administrador---------------------------
var lastsel;
$("#list_cad_administrador").jqGrid({
    url:'libs/lib_administrador.php?reference=administrador&action=grid_buscar_administrador',
    width: 510,
    datatype: "xml",
    loadtext: "Carregando...",
    colNames:['Matricula','Nome','E-Mail', 'Cargo'],
    colModel:[
       {name:'cad_adm_matricula',index:'cad_adm_matricula', width:70,align:"center"},
       {name:'cad_adm_nome',index:'cad_adm_nome', width:150,align:"center"},
       {name:'cad_adm_email',index:'cad_adm_email',width:200, editable:false,align:"center"},
       {name:'cad_adm_cargo',index:'cad_adm_cargo', width:80, align:"center",editable:false},
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


 //-----------------------------Chama a função que abre o formulário para cadastro-----------------

   $('#bt_add_cad_administrador').click(function(){
        //Reseta o formulário
        
       $dialog.dialog('option', 'title', 'Cadastrar Administrador');
       $dialog.dialog('open');
       $('#form_cad_adm').each(function(){
	        this.reset();
	});
    })

// -----------------------------Chama a função que abre o formulário para edição--------------------
    $('#bt_editar_cad_administrador').click(function(){
       
       //Reseta o formulário
       $('#form_cad_adm').each(function(){
	        this.reset();
	});
         
       //Pega o id e armazena em gsr
       var gsr = $("#list_cad_administrador").getGridParam('selrow');
       $("#cad_adm_id").val(gsr);
       alert(gsr);
       if(gsr){
           //Pega os valores da grid e coloca no formuário
           $("#list_cad_administrador").GridToForm(gsr,"#form_cad_adm");
           $dialog.dialog('option', 'title', 'Editar Administrador');

          $dialog.dialog('open');
       }else{
           alert("Selecione uma linha!");
       }
    })

// -------------------------------------------------Apagar----------------------------------------
    $('#bt_apagar_cad_administrador').click(function(){

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
                    $("#list_cad_administrador").setGridParam({url:'libs/lib_administrador.php?reference=administrador&action=grid_buscar_administrador'});
                }
            });
        }else{
            alert("Selecione uma linha!");
            }
    });

// -------------------------------------ABRE FORMULÁRIO PARA CADASTRO E EDIÇÃO--------------------------
   var $dialog = $('#form_cad_adm').dialog({
        width:350,
        height:260,
        modal: true,
        autoOpen: false,
        buttons : {

        'Fechar': function() {
          $dialog.dialog('close');
        },
        
        'Ok': function() {
            alert("pegando valores...");
            
            //Pega os valores do formulário
            var matricula = $("#cad_adm_matricula").val();
            var nome = $("#cad_adm_nome").val();
            var email = $("#cad_adm_email").val();
            var cargo= $("#cad_adm_cargo").val();
            var id_cad = $("#cad_adm_id").val();
                   
            //Armazena os valores do formulário na variável dataString
            var dataString = 'matricula='+ matricula + '&nome=' + nome + '&email=' + email + '&cargo='+ cargo + '&cad_id=' + id_cad;
          
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
                     $("#list_cad_administrador").trigger("reloadGrid");
                    }
                });
            
            
         }    
      }
    });
    



//--------------------------------------Máscaras---------------------------------------------------
$(function($){
        $("#cad_adm_matricula").mask("999999999");
 });

</script>



<p class="heading">Cadastro de Administrador</p>

 <!--Exibe a Grid-->
<div id="form">
    <table id="list_cad_administrador" class="scroll" cellpadding="0" cellspacing="0"> </table>
    <div id="pager_cad_administrador" class="scroll" style="text-align:center;"></div>
</div>


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
	         <span class="sell_control_name">Editar</span>
	    </li>

                    <!-- Botão Apagar -->
	    <li id="bt_apagar_cad_administrador" onClick="" class="ui-state-default ui-corner-all" title="Editar">
	         <span class="ui-icon ui-icon-trash"></span>
	         <span class="sell_control_name">Apagar</span>
	    </li>

	</ul>
</div>


<!-- Formulário de cadastro e edição-->
<div id="form_cad" style="display:none;">
    <form class="dialog-form" id="form_cad_adm" >
        <label>Matrícula</label>
        <input type="text" maxlength="9" size="9" name="cad_adm_matricula" id="cad_adm_matricula" class="text ui-widget-content ui-corner-all" value="" /><br/>
        <label>Nome</label>
	<input type="text" size="30" name="cad_adm_nome" id="cad_adm_nome" value="" class="text ui-widget-content ui-corner-all" /><br/>
        <label>Email</label>
	<input type="text"  size="30" name="cad_adm_email" id="cad_adm_email" value="" class="text ui-widget-content ui-corner-all" /><br/>
        <label>Cargo</label>
	<input type="text"  size="30" name="cad_adm_cargo" id="cad_adm_cargo" value="" class="text ui-widget-content ui-corner-all" /><br/>
        <input type="text" id="cad_adm_id" name="cad_adm_id" style="display:none;">
    </form>
  </div>
  

