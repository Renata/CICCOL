<script type="text/javascript" charset="ISO-8859-1">

// ------------------------------------Grid Aluno---------------------------

var lastsel;
jQuery("#list_cad_aluno").jqGrid({
    url:"libs/lib_aluno.php?reference=aluno&action=grid_buscar_aluno",
    width: 500,
    datatype: "xml",
   colNames:['Matricula','Nome','Sobrenome', 'E-Mail', 'CPF', 'Nasc', 'Entrada','Periodo', 'Sem','Situação'],
    colModel:[
        {name:'cad_aluno_matricula',index:'cad_aluno_matricula', width:70,align:"left"},
        {name:'cad_aluno_nome',index:'cad_aluno_nome', width:100,align:"center"},
        {name:'cad_aluno_sobrenome',index:'cad_aluno_sobrenome', width:120, editable:false,align:"center"},
        {name:'cad_aluno_email',index:'cad_aluno_email', width:100,editable:true,sorttype:"date", align:"center"},
        {name:'cad_aluno_nascimento',index:'cad_aluno_nascimento', width:60, align:"center",editable:false},
        {name:'cad_aluno_cpf',index:'cad_aluno_cpf', width:60, align:"center",editable:false},
        {name:'cad_aluno_entrada',index:'cad_aluno_entrada', width:10, align:"center",editable:false, hidden:true},
        {name:'cad_aluno_periodo',index:'cad_aluno_periodo', width:10, align:"center",editable:false, hidden:true},
        {name:'cad_aluno_semestre',index:'cad_aluno_semestre', width:50, align:"center",editable:false},
        {name:'cad_aluno_situacao',index:'cad_aluno_situacao', width:60, align:"center",editable:false}

    ],
    rowNum:10,
    rowList:[10,20,30],
    pager: '#pager_cad_aluno',
    sortname: 'id',
    viewrecords: true,
    sortorder: "desc",
    imgpath: 'themes/steel/images',
    onSelectRow: function(id){
        if(id && id!=lastsel){
            $('#list_cad_aluno').restoreRow(lastsel);
            lastsel=id;
        }
    },
   // editurl: "local",
    caption: "Aluno"
});


//-----------------------------------Ação sobre o botão Novo ----------------------------

    $('#bt_add_cad_aluno').click(function(){

        //Deixa os campos apenas como leitura
        $("#msg").hide();
        $("#bt_ok_busca_aluno").show();
        $('.aux1').hide();
        $('.aux').addClass("readonly");
        $('.aux').attr("readonly", "readonly");

       //Reseta o formulário
       $('#form_cad_aluno').each(function(){
	        this.reset();
       });

       //Abre formulário
       $("#cad_adm").show();
    });

//--------------------------------------Ação sobre o botão Editar ----------------------------

    $('#bt_editar_cad_aluno').click(function(){

        //Libera os campos para escrita
       $("#msg").hide();
       $("#bt_ok_busca_admin").hide();
       $('.aux1').show();
       $('.aux').removeClass("readonly");
       $('.aux').removeAttr("readonly");

       //Reseta o formulário
       $('#form_cad_aluno').each(function(){
	        this.reset();
       });

       //Pega o id e armazena em gsr
       var gsr = $("#list_cad_aluno").getGridParam('selrow');
       $("#cad_aluno_id").val(gsr);

       if(gsr){

           //Abre formulário
            $("#cad_adm").show();

           //Pega os valores da grid e coloca no formuário
           $("#list_cad_aluno").GridToForm(gsr,"#form_cad_aluno");

       }else{
           alert("Selecione uma linha!");
       }
    });


// ----------------------------------------Ação sobre o botão Apagar----------------------------------------

    $('#bt_apagar_cad_aluno').click(function(){

        //Esconde formulário
        $("#cad_adm").hide();

        //Pega o id e armazena em gsr
        var gsr = $("#list_cad_aluno").getGridParam('selrow');
        var dataString = 'cad_id='+ gsr;

        //Envia o id correspondente a linha que será apagada no banco
        if(gsr){
            $.ajax({
                type: "GET",
                url: "libs/lib_aluno.php?reference=aluno&action=apagar_aluno",
                processData: false,
                data: dataString,
                success: function(){

                    $("#list_cad_aluno").trigger("reloadGrid");
               }
            });
        }else{
            alert("Selecione uma linha!");
            }
    });

//-------------------------------------------- Ação sobre o botão OK --------------------------------------

  $('#bt_ok_cad_aluno').click(function(){

            //Pega os valores do formulário
            var matricula = $("#cad_aluno_matricula").val();
            var cpf = $("#cad_aluno_cpf").val();
            var nome = $("#cad_aluno_nome").val();
            var sobrenome = $("#cad_aluno_sobrenome").val();
            var email = $("#cad_aluno_email").val();
            var nascimento = $("#cad_aluno_nascimento").val();
            var periodo =$("#cad_aluno_periodo").val();
            var entrada =$("#cad_aluno_entrada").val();
            var situacao =$("#cad_aluno_situacao").val();
            var id_cad = $("#cad_aluno_id").val();

            //Renomeia a variaável situacao
            if(situacao == "Cursando")
                situacao = "1"
            else if (situacao == "Matricula Trancada")
                situacao = "2";
           
            //Armazena os valores do formulário na variável dataString
            var dataString = 'matricula=' + matricula + '&cpf=' + cpf + '&nome=' + nome + '&sobrenome=' + sobrenome + '&email=' + email + '&nascimento=' + nascimento + '&periodo=' + periodo + '&entrada=' + entrada + '&situacao=' + situacao + '&cad_id=' + id_cad;

            //Defique qual action será passada na url
            if (id_cad=="")
                var opcao= 'inserir_aluno';
            else
                var opcao='update_aluno';

            //Envia a variável dataString para a lib que insere no banco de dados
            $.ajax({
                    type: "GET",
                    url: "libs/lib_aluno.php?reference=aluno&action="+ opcao,
                    processData: false,
                    data: dataString,
                    success: function(msg){
                        alert(msg);
                     $("#cad_adm").hide();
                     $("#list_cad_aluno").trigger("reloadGrid");
                     }
                });

    })

//------------------------------------ Ação sobre o botão Cancelar ----------------------

$('#bt_cancelar_cad_aluno').click(function(){
    //Esconde formulário
    $("#cad_adm").hide();

})



//------------------------- Ação sobre o botão OK que verifica o cadastro de matrícula ----------------------

$("#bt_ok_busca_aluno").click(function(){

     //Pega os valores do formulário
    var matricula = $("#cad_aluno_matricula").val();


    //Armazena os valores do formulário na variável dataString
    var dataString = 'matricula=' + matricula;

    //Verifica se a matricula já está cadastrada
    $.ajax({
        type: "GET",
        url: "libs/lib_aluno.php?reference=aluno&action=buscar_aluno",
        processData: false,
        data: dataString,
        success: function(msg){
       
            // msg -> variavel que retorna a consulta no banco

            if(msg == '0'){
                $("#msg").show();
                $("#msg").html('<span style= "color:red;">* Matricula já cadastrada</span>');

                //Deixa os campos apenas como leitura
                $('.aux1').hide("readonly");
                $('.aux').addClass("readonly");
                $('.aux').attr("readonly", "readonly");
            }
            else if(msg == 'cadastrar'){

                $("#msg").show();
                $("#msg").html('<span style= "color:green;">* Matricula não cadastrada</span>');

                //Reseta o formulário
                $('#cad_aluno_nome').val('');
                $('#cad_aluno_sobrenome').val('');
                $('#cad_aluno_email').val('');
                $('#cad_aluno_nascimento').val('');
                $('#cad_aluno_entrada').val('');

                //Libera os campos para escrita
                $('.aux1').show();
                $('.aux').removeClass("readonly");
                $('.aux').removeAttr("readonly");

            }
           
       }//Fim do sucess
    });//Fim do ajax



});

//--------------------------------------Máscaras---------------------------------------------------

$("#cad_aluno_nascimento").mask("99/99/9999",{placeholder:" "});
$("#cad_aluno_matricula").mask("999999999");

         
</script>




<p class="heading">Cadastro de Aluno</p>

<!-- Botões-->
<div id="button">
        <ul class="icons ui-widget">
            <!-- Botão Adicionar -->
	    <li id="bt_add_cad_aluno" onClick="" class="ui-state-default ui-corner-all" title="Novo">
                 <span class="ui-icon ui-icon-plusthick"></span>
                 <span>Novo</span>
            </li>

             <!-- Botão Editar -->
	    <li id="bt_editar_cad_aluno" onClick="" class="ui-state-default ui-corner-all" title="Editar">
	         <span class="ui-icon ui-icon-pencil"></span>
	         <span>Editar</span>
	    </li>

                    <!-- Botão Apagar -->
	    <li id="bt_apagar_cad_aluno" onClick="" class="ui-state-default ui-corner-all" title="Editar">
	         <span class="ui-icon ui-icon-trash"></span>
	         <span>Apagar</span>
	    </li>

	</ul>
</div>

<!-- Formulário de cadastro e edição-->
<div id="cad_adm" style="display:none;">
    <form class="dialog-form" id="form_cad_aluno" >
        <fieldset class="ui-widget ui-widget-content ui-corner-all">
		<legend class="ui-widget ui-widget-header ui-corner-all">Aluno</legend>

                    <label>Matrícula</label>
                    <input type="text" size="9" name="cad_aluno_matricula" id="cad_aluno_matricula" class="text ui-widget-content ui-corner-all" />

                    <input type="button" id="bt_ok_busca_aluno" value="OK" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only"/><br/>

                    <div id="msg"></div>
                   
                    <label>CPF</label>
                    <input type="text" maxlength="11" size="11" name="cad_aluno_cpf" id="cad_aluno_cpf" class="text ui-widget-content ui-corner-all aux" /><br/>

                    <label>Nome</label>
                    <input type="text" size="30" name="cad_aluno_nome" id="cad_aluno_nome" class="text ui-widget-content ui-corner-all aux" /><br/>

                    <label>Sobrenome</label>
                    <input type="text" size="30" name="cad_aluno_sobrenome" id="cad_aluno_sobrenome" class="text ui-widget-content ui-corner-all aux" /><br/>

                    <label>E-mail</label>
                    <input type="text" size="30" name="cad_aluno_email" id="cad_aluno_email" class="text ui-widget-content ui-corner-all aux" /><br/>
                   
                    <label>Nascimento</label>
                    <input type="text" size="10" name="cad_aluno_nascimento" id="cad_aluno_nascimento" class="text ui-widget-content ui-corner-all aux" /><br/>
                   
                    <label>Ano Ingresso</label>
                    <input type="text" size="4" name="cad_aluno_entrada" id="cad_aluno_entrada" class="text ui-widget-content ui-corner-all aux" /><br/><br/>

                    <label class="aux1">Período</label>
                    <select id="cad_aluno_periodo" name="cad_aluno_periodo" class="aux1">
                        <option>Selecione</option>
                        <option>1</option>
                        <option>2</option>
                   </select><br/><br/>

                    <label class="aux1">Situação</label>
                   <select id="cad_aluno_situacao" name="cad_aluno_situacao" class="aux1">
                        <option>Selecione</option>
                        <option>Cursando</option>
                        <option>Matricula Trancada</option>
                   </select><br/><br/>

                    <input type="text" id="cad_aluno_id" name="cad_aluno_id" style="display:none;">

                    <div id="dialog-form_button"
                        <input type="button" value="OK" id="bt_ok_cad_aluno" title="OK" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">
                        <input type="button" value="Cancelar" id="bt_cancelar_cad_aluno" title="Cancelar" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only">
                    </div>
        </fieldset>
    </form>
  </div>


 <!--Exibe a Grid-->
<div id="form">
    <table id="list_cad_aluno" class="scroll" cellpadding="0" cellspacing="0"> </table>
    <div id="pager_cad_aluno" class="scroll" style="text-align:center;"></div>
</div>

