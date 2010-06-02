<script type="text/javascript" charset="ISO-8859-1">

// ------------------------------------Grid Disciplina---------------------------

var lastsel;
jQuery("#list_cad_disciplina").jqGrid({
    url: "libs/lib_disciplina.php?reference=disciplina&action=grid_buscar_disciplina",
    width: 490,
    datatype: "xml",
    colNames:['Codigo','Nome','Materia', 'CH', 'Cred', 'Sem', 'Alunos', 'Turma', 'Opt'],
    colModel:[
        {name:'cad_disciplina_codigo',index:'cad_disciplina_codigo', width:30,align:"center"},
        {name:'cad_disciplina_nome',index:'cad_disciplina_nome', width:50,align:"center"},
        {name:'cad_disciplina_materia',index:'cad_disciplina_materia', width:50, editable:false,align:"center"},
        {name:'cad_disciplina_ch',index:'cad_disciplina_ch', width:15,editable:true, align:"center"},
        {name:'cad_disciplina_num_cred',index:'cad_disciplina_num_cred', width:15, align:"center",editable:false},
        {name:'cad_disciplina_semestre',index:'cad_disciplina_semestre', width:15,editable:false, align:"center"},
        {name:'cad_disciplina_qt_max_aluno',index:'cad_disciplina_qt_max_aluno', width:20, align:"center",editable:false},
        {name:'cad_disciplina_turma',index:'cad_disciplina_turma', width:20, align:"center",editable:false},
        {name:'cad_disciplina_opt',index:'cad_disciplina_opt', width:20, align:"center", hidden:true}

    ],
    rowNum:10,
    rowList:[10,20,30],
    pager: '#pager_cad_disciplina',
    sortname: 'r',
    viewrecords: true,
    sortorder: "desc",
    imgpath: 'themes/steel/images',
    onSelectRow: function(id){
        if(id && id!=lastsel){
            $('#list_cad_disciplina').restoreRow(lastsel);
            lastsel=id;
        }
    },
    //editurl: "local",
    caption: "Disciplinas"
});


//-----------------------------------Ação sobre o botão Novo ----------------------------

    $('#bt_add_cad_disciplina').click(function(){

       //Reseta o formulário
       $('#form_cad_disciplina').each(function(){
	        this.reset();
       });
     
      $("#span_disciplina_turma").show();

       //Abre formulário
       $("#cad_adm").show();
    });

  //--------------------------------------Ação sobre o botão Editar ----------------------------

    $('#bt_editar_cad_disciplina').click(function(){


       //Reseta o formulário
       $('#form_cad_disciplina').each(function(){
	        this.reset();
       });

        $("#span_disciplina_turma").hide();
       // $("#label_turma").html("<style=\"display:none;\"");


       //Pega o id e armazena em gsr
       var gsr = $("#list_cad_disciplina").getGridParam('selrow');
       $("#cad_disciplina_id").val(gsr);

       if(gsr){

           //Abre formulário
            $("#cad_adm").show();

           //Pega os valores da grid e coloca no formuário
           $("#list_cad_disciplina").GridToForm(gsr,"#form_cad_disciplina");

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

  $('#bt_ok_cad_disciplina').click(function(){

            //Pega os valores do formulário
            var codigo = $("#cad_disciplina_codigo").val();
            var nome = $("#cad_disciplina_nome").val();
            var materia = $("#cad_disciplina_materia").val();
            var ch = $("#cad_disciplina_ch").val();
            var credito = $("#cad_disciplina_num_cred").val();
            var semestre = $("#cad_disciplina_semestre").val();
            var aluno = $("#cad_disciplina_qt_max_aluno").val();
            var turma = $("#cad_disciplina_turma").val();
            var opt = $("#cad_disciplina_opt").val();
            var id_cad = $("#cad_disciplina_id").val();
          
            //Armazena os valores do formulário na variável dataString
           var dataString = 'codigo=' + codigo + '&nome=' + nome + '&materia=' + materia + '&ch=' + ch + '&credito=' + credito + '&semestre=' + semestre + '&aluno=' + aluno + '&turma=' + turma + '&opt=' + opt + '&cad_id=' + id_cad;


            //Defique qual action será passada na url
            if (id_cad=="")
                var opcao= 'inserir_disciplina';
            else
                var opcao='update_disciplina';

            //Envia a variável dataString para a lib que insere no banco de dados
            $.ajax({
                    type: "GET",
                    url: "libs/lib_disciplina.php?reference=disciplina&action="+ opcao,
                    processData: false,
                    data: dataString,
                    //dataType: "html",
                    success: function(){
                        //alert(msg);
                     $("#cad_adm").hide();
                     $("#list_cad_disciplina").trigger("reloadGrid");
                     }
                });

    })

//------------------------------------ Ação sobre o botão Cancelar ----------------------

$('#bt_cancelar_cad_disciplina').click(function(){
    //Esconde formulário
    $("#cad_adm").hide();

})

//--------------------------------------Máscaras---------------------------------------------------

$("#cad_disciplina_codigo").mask("aaa999");
$("#cad_disciplina_ch").mask("99");
$("#cad_disciplina_qt_max_aluno").mask("99");
$("#cad_disciplina_num_cred").mask("9");

</script>



<p class="heading">Cadastro de Disciplinas</p>

<!-- Botões-->
<div id="button">
        <ul class="icons ui-widget">
            <!-- Botão Adicionar -->
	    <li id="bt_add_cad_disciplina" onClick="" class="ui-state-default ui-corner-all" title="Novo">
                 <span class="ui-icon ui-icon-plusthick"></span>
                 <span>Novo</span>
            </li>

             <!-- Botão Editar -->
	    <li id="bt_editar_cad_disciplina" onClick="" class="ui-state-default ui-corner-all" title="Editar">
	         <span class="ui-icon ui-icon-pencil"></span>
	         <span>Editar</span>
	    </li>

                    <!-- Botão Apagar -->
	    <li id="bt_apagar_cad_disciplina" onClick="" class="ui-state-default ui-corner-all" title="Editar">
	         <span class="ui-icon ui-icon-trash"></span>
	         <span>Apagar</span>
	    </li>

	</ul>
</div>

<!-- Formulário de cadastro e edição-->
<div id="cad_adm" style="display:none;">
    <form class="dialog-form" id="form_cad_disciplina" >
        <fieldset class="ui-widget ui-widget-content ui-corner-all">
		<legend class="ui-widget ui-widget-header ui-corner-all">Disciplina</legend>

                    <label>Código</label>
                    <input type="text" size="6" name="cad_disciplina_codigo" id="cad_disciplina_codigo" class="text ui-widget-content ui-corner-all" /><br/>
                    
                    <label>Nome</label>
                    <input type="text" size="30" name="cad_disciplina_nome" id="cad_disciplina_nome" class="text ui-widget-content ui-corner-all" /><br/>

                    <label>CH</label>
                    <input type="text" size="2" name="cad_disciplina_ch" id="cad_disciplina_ch" class="text ui-widget-content ui-corner-all" /><br/>

                    <label>Alunos</label>
                    <input type="text" size="2" name="cad_disciplina_qt_max_aluno" id="cad_disciplina_qt_max_aluno" class="text ui-widget-content ui-corner-all" /><br/>

                    <label>Créditos</label>
                    <input type="text" size="1" name="cad_disciplina_num_cred" id="cad_disciplina_num_cred" class="text ui-widget-content ui-corner-all" /><br/>

                   <label>Semestre</label>
                   <select id="cad_disciplina_semestre" name="cad_disciplina_semestre">
                        <option>Selecione</option>
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                        <option>5</option>
                        <option>6</option>
                        <option>7</option>
                        <option>8</option>
                   </select><br/><br/>

                    <label>Materia</label>
                    <select id="cad_disciplina_materia" name="cad_disciplina_materia">
                        <option>Selecione</option>
                        <!--{section name=cont_materia loop=$mat}-->
                        <option><!--{$mat[cont_materia].materia}--></option>
                       <!--{/section}-->
                    </select><br/><br/>

                   <span id="span_disciplina_turma">
                   <label>Turmas</label>
                   
                   <select id="cad_disciplina_turma" name="cad_disciplina_turma">
                        <option>Selecione</option>
                        <option>Única</option>
                        <option>Duas</option>
                   </select><br/><br/>
                   </span>


                  <label>Tipo</label>
                  <select id="cad_disciplina_opt" name="cad_disciplina_opt">
                        <option>Selecione</option>
                        <option value="1">Obrigatória</option>
                        <option value="2">Optativa</option>
                   </select><br/><br/>

                    <input type="text" id="cad_disciplina_id" name="cad_disciplina_id" style="display:none;">

                    <div id="dialog-form_button"
                        <input type="button" value="OK" id="bt_ok_cad_disciplina" title="OK" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">
                        <input type="button" value="Cancelar" id="bt_cancelar_cad_disciplina" title="Cancelar" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">
                    </div>
        </fieldset>
    </form>
  </div>


 <!--Exibe a Grid-->
<div id="form">
    <table id="list_cad_disciplina" class="scroll" cellpadding="0" cellspacing="0"> </table>
    <div id="pager_cad_disciplina" class="scroll" style="text-align:center;"></div>
</div>






