<script type="text/javascript" charset="ISO-8859-1">
//----------------------------------Grid alocar professor---------------------

var lastsel;
jQuery("#list_alocar_professor").jqGrid({
    url: "libs/lib_alocar_professor.php?reference=alocar_professor&action=grid_buscar_alocar_professor",
    width: 530,
    datatype: "xml",
    colNames:['Disciplina', 'Professor', 'Turma'],
    colModel:[
        {name:'cad_alocar_professor_disciplina',index:'cad_alocar_professor_disciplina', width:160,sortable:false,align:"center"},
        {name:'cad_alocar_professor_professor',index:'cad_alocar_professor_professor', width:160,sortable:false,align:"center"},
        {name:'cad_alocar_professor_turma',index:'cad_alocar_professor_turma', width:160,sortable:false,align:"center"}
    ],
    rowNum:10,
    rowList:[10,20,30],
    pager: '#pager_alocar_professor',
    sortname: 'id',
    viewrecords: true,
    sortorder: "desc",
    imgpath: 'themes/steel/images',
    onSelectRow: function(id){
        if(id && id!=lastsel){
            $('#list_alocar_professor').restoreRow(lastsel);
            lastsel=id;
        }
    },
    editurl: "local",
    caption: "Alocar Professor"
});

//-----------------------------------Ação sobre o botão Novo ----------------------------

    $('#bt_add_alocar_professor').click(function(){

       //Reseta o formulário
       $('#form_alocar_professor').each(function(){
	        this.reset();
       });

       //Abre formulário
       $("#cad_adm").show();
    });

//--------------------------------------Ação sobre o botão Editar ----------------------------

    $('#bt_editar_alocar_professor_').click(function(){


       //Reseta o formulário
       $('#form_alocar_professor').each(function(){
	        this.reset();
       });

       //Pega o id e armazena em gsr
       var gsr = $("#list_alocar_professor").getGridParam('selrow');
       $("#cad_alocar_professor_id").val(gsr);

       if(gsr){

           //Abre formulário
            $("#cad_adm").show();

           //Pega os valores da grid e coloca no formuário
           $("#list_alocar_professor").GridToForm(gsr,"#form_alocar_professor");

       }else{
           alert("Selecione uma linha!");
       }
    });


// ----------------------------------------Ação sobre o botão Apagar----------------------------------------

    $('#bt_apagar_alocar_professor').click(function(){

        //Esconde formulário
        $("#cad_adm").hide();

        //Pega o id e armazena em gsr
        var gsr = $("#list_alocar_professor").getGridParam('selrow');
        var dataString = 'cad_id='+ gsr;
      
        //Envia o id correspondente a linha que será apagada no banco
        if(gsr){
            $.ajax({
                type: "GET",
                url: "libs/lib_alocar_professor.php?reference=alocar_professor&action=apagar_alocar_professor",
                processData: false,
                data: dataString,
                success: function(){

                    $("#list_alocar_professor").trigger("reloadGrid");
               }
            });
        }else{
            alert("Selecione uma linha!");
            }
    });

//-------------------------------------------- Ação sobre o botão OK --------------------------------------

  $('#bt_ok_alocar_professor').click(function(){

            //Pega os valores do formulário
            var disciplina = $("#cad_alocar_professor_disciplina").val();
            var professor = $("#cad_alocar_professor_professor").val();
            var turma = $("#cad_alocar_professor_turma").val();
            var id_cad = $("#cad_alocar_professor_id").val();
           
            //Armazena os valores do formulário na variável dataString
            var dataString = 'disciplina=' + disciplina + '&professor=' + professor + '&turma=' + turma + '&cad_id=' + id_cad;
         
            //Defique qual action será passada na url
            if (id_cad=="")
                var opcao= 'inserir_alocar_professor';
            else
                var opcao='update_alocar_professor';

            //Envia a variável dataString para a lib que insere no banco de dados
            $.ajax({
                    type: "GET",
                    url: "libs/lib_alocar_professor.php?reference=alocar_professor&action="+ opcao,
                    processData: false,
                    data: dataString,
                    //dataType: "html",
                    success: function(msg){
                        alert(msg);
                     $("#cad_adm").hide();
                     $("#list_alocar_professor").trigger("reloadGrid");

                     }
                });

    })

//------------------------------------ Ação sobre o botão Cancelar ----------------------

$('#bt_cancelar_alocar_professor').click(function(){
    //Esconde formulário
    $("#cad_adm").hide();

})


// --------------------------------- Mostra a quantidade de turmas de acordo com a disciplina selecionada

$("#cad_alocar_professor_disciplina").blur(function(){

    //Pega os valores do formulário
    var disciplina = $("#cad_alocar_professor_disciplina").val();


    //Armazena os valores do formulário na variável dataString
    var dataString = 'disciplina=' + disciplina;
            
    $.ajax({
        type: "GET",
        url: "libs/lib_alocar_professor.php?reference=alocar_professor&action=buscar_turma",
        processData: false,
        data: dataString,
        success: function(msg){
            if(msg == 3)
                $("#cad_alocar_professor_turma").html("<option value=3>Turma Única</option>");
            else
                $("#cad_alocar_professor_turma").html("<option value=\"1\">Turma A</option><option value=\"2\">Turma B</option>");
        }
    });
});


</script>


<p class="heading">Alocar Professor</p>



<!-- Botões-->
<div id="button">
        <ul class="icons ui-widget">

            <!-- Botão Adicionar -->
            <li id="bt_add_alocar_professor"onClick="" class="ui-state-default ui-corner-all" title="Novo">
                <span class="ui-icon ui-icon-plusthick"></span>
                <span>Novo</span>
	    </li>

            <!-- Botão Editar -->
	    <li id="bt_editar_alocar_professor" onClick="" class="ui-state-default ui-corner-all" title="Editar">
	        <span class="ui-icon ui-icon-pencil"></span>
	        <span class="sell_control_name">Editar</span>
	    </li>

            <!-- Botão Apagar -->
	    <li id="bt_apagar_alocar_professor" onClick="" class="ui-state-default ui-corner-all" title="Editar">
	        <span class="ui-icon ui-icon-trash"></span>
	        <span class="sell_control_name">Apagar</span>
	    </li>
	</ul>
</div>

<!-- Formulário para seleção-->
<div id="cad_adm" style="display:none;">
    <form class="dialog-form" id="form_alocar_professor" >
        <fieldset class="ui-widget ui-widget-content ui-corner-all">
		<legend class="ui-widget ui-widget-header ui-corner-all">Alocar Professor</legend>

                    <label>Disciplina:</label>
                    <select id="cad_alocar_professor_disciplina">
                        <option>Selecione</option>
                        <!--{section name=cont_disciplina loop=$disc}-->
                        <option><!--{$disc[cont_disciplina].disciplina}--></option>
                        <!--{/section}-->
                    </select><br/><br/>

                    <label>Professor:</label>
                    <select id="cad_alocar_professor_professor">
                        <option>Selecione</option>
                        <!--{section name=cont_professor loop=$prof}-->
                        <option><!--{$prof[cont_professor].professor}--></option>
                        <!--{/section}-->
                    </select><br/><br/>


                    <label>Turma:</label>
                    <select id="cad_alocar_professor_turma">
                       <option>Selecione</option>
                    </select><br/><br/>

                    <input type="text" id="cad_alocar_professor_id" name="cad_alocar_professor_id" style="display:none;">

                    <div id="dialog-form_button"
                        <input type="button" value="OK" id="bt_ok_alocar_professor" title="OK" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">
                        <input type="button" value="Cancelar" id="bt_cancelar_alocar_professor" title="Cancelar" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">
                    </div>
        </fieldset>
    </form>
  </div>

<!--Exibe a Grid-->
<div id="form"> 
    <table id="list_alocar_professor" class="scroll" cellpadding="0" cellspacing="0"> </table>
    <div id="pager_alocar_professor" class="scroll" style="text-align:center;"></div>
</div>

