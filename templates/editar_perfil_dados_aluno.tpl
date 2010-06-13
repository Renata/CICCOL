<script type="text/javascript" charset="ISO-8859-1">

// ------------------------------------Grid Aluno---------------------------

var lastsel;
$("#list_editar_perfil_dados").jqGrid({
    url:"libs/lib_perfil_aluno.php?reference=aluno&action=grid_buscar_dados_aluno",
    width: 480,
    height: 50,
    datatype: "xml",
    colNames:['Matricula','Nome','Sobrenome', 'E-Mail', 'CPF', 'Nasc', 'Entrada','Periodo', 'Sem','Situação'],
    colModel:[
        {name:'editar_perfil_dados_matricula',index:'editar_perfil_dados_matricula', width:70,align:"left"},
        {name:'editar_perfil_dados_nome',index:'editar_perfil_dados_nome', width:100,align:"center"},
        {name:'editar_perfil_dados_sobrenome',index:'editar_perfil_dados_sobrenome', width:100, editable:false,align:"center"},
        {name:'editar_perfil_dados_email',index:'editar_perfil_dados_email', width:100,editable:true,sorttype:"date", align:"center"},
        {name:'editar_perfil_dados_cpf',index:'editar_perfil_dados_cpf', width:70, align:"center",editable:false},
        {name:'editar_perfil_dados_nascimento',index:'editar_perfil_dados_nascimento', width:60, align:"center",editable:false},
        {name:'editar_perfil_dados_entrada',index:'editar_perfil_dados_entrada', width:10, align:"center",editable:false, hidden:true},
        {name:'editar_perfil_dados_periodo',index:'editar_perfil_dados_periodo', width:10, align:"center",editable:false, hidden:true},
        {name:'editar_perfil_dados_semestre',index:'editar_perfil_dados_semestre', width:50, align:"center",editable:false},
        {name:'editar_perfil_dados_situacao',index:'editar_perfil_dados_situacao', width:60, align:"center",editable:false}

    ],
    pager: '',
    sortname: 'id',
    sortorder: "desc",
    pgbuttons: false,
    pgtext: false,
    pginput:false,
    shrinkToFit:false,
    scrollOffset: true,
    imgpath: 'themes/steel/images',
    onSelectRow: function(id){
        if(id && id!=lastsel){
            $('#list_editar_perfil_dados').restoreRow(lastsel);
            lastsel=id;
        }
    },
   caption: "Meus dados"
});


//--------------------------------------Ação sobre o botão Editar ----------------------------

    $('#bt_editar_perfil_aluno').click(function(){

       //Reseta o formulário
       $('#form_editar_perfil_dados').each(function(){
	        this.reset();
       });

       //Pega o id e armazena em gsr
       var gsr = $("#list_editar_perfil_dados").getGridParam('selrow');
       $("#editar_perfil_dados_id").val(gsr);

       if(gsr){

           //Abre formulário
            $("#cad_adm").show();

           //Pega os valores da grid e coloca no formuário
           $("#list_editar_perfil_dados").GridToForm(gsr,"#form_editar_perfil_dados");

       }else{
           alert("Selecione uma linha!");
       }
    });


//-------------------------------------------- Ação sobre o botão OK --------------------------------------

  $('#bt_ok_editar_perfil_dados').click(function(){

            //Pega os valores do formulário
            
            var sobrenome = $("#editar_perfil_dados_sobrenome").val();
            var email = $("#editar_perfil_dados_email").val();
            var nascimento = $("#editar_perfil_dados_nascimento").val();
            var id_cad = $("#editar_perfil_dados_id").val();

                      
            //Armazena os valores do formulário na variável dataString
            var dataString = 'sobrenome=' + sobrenome + '&email=' + email + '&nascimento=' + nascimento + '&cad_id=' + id_cad;

            //Envia a variável dataString para a lib que insere no banco de dados
            $.ajax({
                    type: "GET",
                    url: "libs/lib_perfil_aluno.php?reference=aluno&action=update_dados_aluno",
                    processData: false,
                    data: dataString,
                    success: function(msg){
                        alert(msg);
                     $("#cad_adm").hide();
                     $("#list_editar_perfil_dados").trigger("reloadGrid");
                     }
                });

    })

//------------------------------------ Ação sobre o botão Cancelar ----------------------

$('#bt_cancelar_editar_perfil_dados').click(function(){
    //Esconde formulário
    $("#cad_adm").hide();

})


//--------------------------------------Máscaras---------------------------------------------------

$("#perfil_aluno_nascimento").mask("99/99/9999",{placeholder:" "});


         
</script>




<p class="heading">Alterar Dados</p>

<!-- Botões-->
<div id="button">
        <ul class="icons ui-widget">

            <!-- Botão Editar -->
	    <li id="bt_editar_perfil_aluno" onClick="" class="ui-state-default ui-corner-all" title="Editar">
	         <span class="ui-icon ui-icon-pencil"></span>
	         <span>Editar</span>
	    </li>


	</ul>
</div>

<!-- Formulário de cadastro e edição-->
<div id="cad_adm" style="display:none;">
    <form class="dialog-form" id="form_editar_perfil_dados" >
        <fieldset class="ui-widget ui-widget-content ui-corner-all">
		<legend class="ui-widget ui-widget-header ui-corner-all">Editar</legend>

                    <label>Matrícula</label>
                    <input type="text" size="9" name="editar_perfil_dados_matricula" id="editar_perfil_dados_matricula" readonly="readonly" class="text ui-widget-content ui-corner-all readonly" /><br/>

                    <label>CPF</label>
                    <input type="text" maxlength="11" size="11" name="editar_perfil_dados_cpf" id="editar_perfil_dados_cpf" readonly="readonly" class="text ui-widget-content ui-corner-all readonly" /><br/>

                    <label>Nome</label>
                    <input type="text" size="30" name="editar_perfil_dados_nome" id="editar_perfil_dados_nome" readonly="readonly" class="text ui-widget-content ui-corner-all readonly" /><br/>

                    <label>Sobrenome</label>
                    <input type="text" size="30" name="editar_perfil_dados_sobrenome" id="editar_perfil_dados_sobrenome" class="text ui-widget-content ui-corner-all" /><br/>

                    <label>E-mail</label>
                    <input type="text" size="30" name="editar_perfil_dados_email" id="editar_perfil_dados_email" class="text ui-widget-content ui-corner-all" /><br/>
                   
                    <label>Nascimento</label>
                    <input type="text" size="10" name="editar_perfil_dados_nascimento" id="editar_perfil_dados_nascimento" class="text ui-widget-content ui-corner-all" /><br/>
                   
                    <input type="text" id="editar_perfil_dados_id" name="editar_perfil_dados_id" style="display:none;">

                    <div id="dialog-form_button"
                        <input type="button" value="OK" id="bt_ok_editar_perfil_dados" title="OK" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">
                        <input type="button" value="Cancelar" id="bt_cancelar_editar_perfil_dados" title="Cancelar" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">
                    </div>
        </fieldset>
    </form>
  </div>


 <!--Exibe a Grid-->
<div id="form">
    <table id="list_editar_perfil_dados" class="scroll" cellpadding="0" cellspacing="0"> </table>
    <div id="pager_editar_perfil_dados" class="scroll" style="text-align:center;"></div>
</div>

