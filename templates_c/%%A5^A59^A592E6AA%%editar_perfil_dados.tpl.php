<?php /* Smarty version 2.6.26, created on 2010-05-24 22:48:48
         compiled from editar_perfil_dados.tpl */ ?>
<script type="text/javascript">
    $(document).ready(function() {
        $("#bt_salvar_editar_dados").click(function() {
           alert("Ainda nao foi implementado!")
        });
    });

</script>

<head>
    <script src="js/jquery.js" type="text/javascript"></script>
    <script src="js/jquery.jqGrid.js" type="text/javascript"></script>
    <script src="js/jqModal.js" type="text/javascript"></script>
    <script src="js/jqDnR.js" type="text/javascript"></script>
    <script src="js/ui.datepicker.js" type="text/javascript"></script> 
    <script type='text/javascript' src='js/grid.locale-pt-br.js'></script>
    <link rel="stylesheet" type="text/css" media="screen" href="themes/ui.datepicker.css" />

</head>


<script type="text/javascript" charset="ISO-8859-1">

var lastsel;
jQuery("#list_editar_dados").jqGrid({
    url:'local',
    width: 510,
    datatype: "xml",
    colNames:['Matricula','Nome','E-Mail', 'Nascimento', 'Cargo'],
    colModel:[
        {name:'matricula',index:'matricula', width:70,align:"center",editable:true},
        {name:'nome',index:'nome', width:150,align:"center",editable:true},
        {name:'email',index:'email', width:150, editable:false,align:"center",editable:true},
        {name:'nasc',index:'nasc', width:80,editable:true,sorttype:"date", align:"center"},
        {name:'cargo',index:'cargo', width:80, align:"center",editable:true},

    ],
    rowNum:1,
    
    pager: '#pager_editar_dados',
    sortname: 'id',
    viewrecords: true,
    sortorder: "desc",
    imgpath: 'themes/steel/images',
    onSelectRow: function(id){
        if(id && id!=lastsel){
            $('#list_editar_dados').restoreRow(lastsel);
            $('#list_editar_dados').editRow(id,true,pickdates);
            lastsel=id;
        }
    },
    editurl: "local",
    caption: "Editar Dados"
});

var mydata3 = [ {matricula:"12345",nome:"Renata",email:"note",nasc:"2007-12-03", admissao:"2007-12-03"}];
            for(var i=0;i < mydata3.length;i++) $("#list_editar_dados").addRowData(mydata3[i].id,mydata3[i]);
            function pickdates(id){
                $("#"+id+"_sdate","#list_editar_dados").datepicker({dateFormat:"yy-mm-dd"}); }




</script>




<p class="heading">Editar Dados </p>





<div id="form">
    
    <table id="list_editar_dados" class="scroll" cellpadding="0" cellspacing="0"> </table>
    <div id="pager_editar_dados" class="scroll" style="text-align:center;"></div>
</div>

<div id="button">
    <div id="bt_salvar_editar_dados" class="button_salvar" onclick="">
        <p class="button">Salvar</p>
    </div>
    <div id="bt_apagar_editar_dados" class="button_apagar" onclick="">
        <p class="button">Apagar</p>
    </div>
    <div id="bt_adicionar_editar_dados" class="button_adicionar" onclick="">
        <p class="button">Novo</p>
    </div>
</div>



