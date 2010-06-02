<script type="text/javascript" charset="ISO-8859-1">

var lastsel;
jQuery("#list_cad_horario").jqGrid({
    url: "libs/lib_horario.php?reference=horario&action=grid_buscar_horario",
    width: 510,
    datatype: "xml",
    colNames:['Disciplina', 'Turma', 'Dia', 'Início', 'Fim' ],
    colModel:[
        {name:'cad_horario_disciplina',index:'cad_horario_disciplina', width:80,sortable:false,align:"center"},
        {name:'cad_horario_turma',index:'cad_horario_turma', width:50,sortable:false,align:"center"},
        {name:'cad_horario_dia',index:'cad_horario_dia', width:100,sortable:false,align:"center"},
        {name:'cad_horario_inicio',index:'cad_horario_inicio', width:50,sortable:false,align:"center"},
        {name:'cad_horario_fim',index:'cad_horario_fim', width:50,sortable:false,align:"center"}
    ],
    rowNum:10,
    rowList:[10,20,30],
    pager: '#pager_cad_horario',
    sortname: 'id',
    viewrecords: true,
    sortorder: "desc",
    imgpath: 'themes/steel/images',
    onSelectRow: function(id){
        if(id && id!=lastsel){
            $('#list_cad_horario').restoreRow(lastsel);
            
            lastsel=id;
        }
    },
    //editurl: "local",
    caption: "Cadastrar Horario"
});


//-----------------------------------Ação sobre o botão Novo ----------------------------

    $('#bt_add_cad_horario').click(function(){

       //Reseta o formulário
       $('#form_cad_horario').each(function(){
	        this.reset();
       });

       //Abre formulário
       $("#cad_adm").show();
    });

  //--------------------------------------Ação sobre o botão Editar ----------------------------

    $('#bt_editar_cad_horario').click(function(){


       //Reseta o formulário
       $('#form_cad_horario').each(function(){
	        this.reset();
       });

       //Pega o id e armazena em gsr
       var gsr = $("#list_cad_horario").getGridParam('selrow');
       $("#cad_horario_id").val(gsr);
      
       if(gsr){

           //Abre formulário
            $("#cad_adm").show();

           //Pega os valores da grid e coloca no formuário
           $("#list_cad_horario").GridToForm(gsr,"#form_cad_horario");

       }else{
           alert("Selecione uma linha!");
       }
    });


 // ----------------------------------------Ação sobre o botão Apagar----------------------------------------

    $('#bt_apagar_cad_horario').click(function(){

        //Esconde formulário
        $("#cad_adm").hide();

        //Pega o id e armazena em gsr
        var gsr = $("#list_cad_horario").getGridParam('selrow');
        var dataString = 'cad_id='+ gsr;

        //Envia o id correspondente a linha que será apagada no banco
        if(gsr){
            $.ajax({
                type: "GET",
                url: "libs/lib_horario.php?reference=horario&action=apagar_horario",
                processData: false,
                data: dataString,
                success: function(){
                    $("#list_cad_horario").trigger("reloadGrid");
               }
            });
        }else{
            alert("Selecione uma linha!");
            }
    });


//-------------------------------------------- Ação sobre o botão OK --------------------------------------

  $('#bt_ok_cad_horario').click(function(){

            //Pega os valores do formulário
            var disciplina = $("#cad_horario_disciplina").val();
            var dia = $("#cad_horario_dia").val();
            var turma = $("#cad_horario_turma").val();
            var inicio = $("#cad_horario_inicio").val();
            var fim = $("#cad_horario_fim").val();
            var id_cad = $("#cad_horario_id").val();
           
           //Armazena os valores do formulário na variável dataString
           var dataString = 'disciplina=' + disciplina + '&dia=' + dia + '&turma=' + turma + '&inicio=' + inicio + '&fim=' + fim + '&cad_id=' + id_cad;


            //Defique qual action será passada na url
            if (id_cad=="")
                var opcao= 'inserir_horario';
            else
                var opcao='update_horario';

            //Envia a variável dataString para a lib que insere no banco de dados
            $.ajax({
                    type: "GET",
                    url: "libs/lib_horario.php?reference=horario&action="+ opcao,
                    processData: false,
                    data: dataString,
                    //dataType: "html",
                    success: function(){
                        //alert(msg);
                     $("#cad_adm").hide();
                     $("#list_cad_horario").trigger("reloadGrid");
                     }
                });

    })

//------------------------------------ Ação sobre o botão Cancelar ----------------------

$('#bt_cancelar_cad_horario').click(function(){
    //Esconde formulário
    $("#cad_adm").hide();

})

// --------------------------------- Mostra a quantidade de turmas de acordo com a disciplina selecionada

$("#cad_horario_disciplina").blur(function(){

    //Pega os valores do formulário
    var disciplina = $("#cad_horario_disciplina").val();


    //Armazena os valores do formulário na variável dataString
    var dataString = 'disciplina=' + disciplina;

    $.ajax({
        type: "GET",
        url: "libs/lib_horario.php?reference=horario&action=buscar_turma",
        processData: false,
        data: dataString,
        success: function(msg){

            if(msg == 3)
                $("#cad_horario_turma").html("<option value=3>Turma Única</option>");
            else
                $("#cad_horario_turma").html("<option value=\"1\">Turma A</option><option value=\"2\">Turma B</option>");
        }
    });
});

//--------------------------------------Máscaras---------------------------------------------------

$("#cad_horario_inicio").mask("99:99",{placeholder:" "});
$("#cad_horario_fim").mask("99:99",{placeholder:" "});

</script>



<p class="heading">Cadastro de Horários</p>

<!-- Botões-->
<div id="button">
        <ul class="icons ui-widget">
            <!-- Botão Adicionar -->
	    <li id="bt_add_cad_horario" onClick="" class="ui-state-default ui-corner-all" title="Novo">
                 <span class="ui-icon ui-icon-plusthick"></span>
                 <span>Novo</span>
            </li>

             <!-- Botão Editar -->
	    <li id="bt_editar_cad_horario" onClick="" class="ui-state-default ui-corner-all" title="Editar">
	         <span class="ui-icon ui-icon-pencil"></span>
	         <span>Editar</span>
	    </li>

            <!-- Botão Apagar -->
	    <li id="bt_apagar_cad_horario" onClick="" class="ui-state-default ui-corner-all" title="Editar">
	         <span class="ui-icon ui-icon-trash"></span>
	         <span>Apagar</span>
	    </li>

	</ul>
</div>

<!-- Formulário de cadastro e edição-->
<div id="cad_adm" style="display:none;">
    <form class="dialog-form" id="form_cad_horario" >
        <fieldset class="ui-widget ui-widget-content ui-corner-all">
		<legend class="ui-widget ui-widget-header ui-corner-all">Horario</legend>

                    <label>Disciplina:</label>
                    <select id="cad_horario_disciplina" name="cad_horario_disciplina">
                        <option>Selecione</option>
                        <!--{section name=cont_disciplina loop=$disc}-->
                        <option><!--{$disc[cont_disciplina].disciplina}--></option>
                        <!--{/section}-->
                    </select><br/><br/>

                    <label>Turma:</label>
                    <select id="cad_horario_turma" name="cad_horario_turma">
                       <option>Selecione</option>
                    </select><br/><br/>

                    <label>Dia:</label>
                    <select id="cad_horario_dia" name="cad_horario_dia">
                       <option>Selecione</option>
                       <option>Segunda-feira</option>
                       <option>Terça-feira</option>
                       <option>Quarta-feira</option>
                       <option>Quinta-feira</option>
                       <option>Sexta-feira</option>
                       <option>Sábado</option>
                    </select><br/><br/>

                    <label>Início</label>
                    <input type="text" size="5" id="cad_horario_inicio" name="cad_horario_inicio" class="text ui-widget-content ui-corner-all"><br/>

                    <label>Fim</label>
                    <input type="text" size="5" id="cad_horario_fim" name="cad_horario_fim" class="text ui-widget-content ui-corner-all"><br/>

                    <input type="text" id="cad_horario_id" name="cad_horario_id" style="display:none;">

                    <div id="dialog-form_button"
                        <input type="button" value="OK" id="bt_ok_cad_horario" title="OK" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">
                        <input type="button" value="Cancelar" id="bt_cancelar_cad_horario" title="Cancelar" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">
                    </div>

                    
        </fieldset>
    </form>
  </div>


 <!--Exibe a Grid-->
<div id="form">
    <table id="list_cad_horario" class="scroll" cellpadding="0" cellspacing="0"> </table>
    <div id="pager_cad_horario" class="scroll" style="text-align:center;"></div>
</div>






