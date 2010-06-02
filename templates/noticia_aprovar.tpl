


<script type="text/javascript" charset="ISO-8859-1">

$("#list_noticia").jqGrid({
    url:'local',
    width: 510,
    datatype: "xml",
    colNames:['Ação','Nome','E-Mail', 'Noticia', 'Status'],
    colModel:[
        {name:'act',index:'act', width:75,sortable:false},
        {name:'nome',index:'nome', width:90, editable:true},
        {name:'email',index:'email', width:100,editable:true},
        {name:'noticia',index:'noticia', width:100, align:"right",editable:true},
        {name:'status',index:'status', width:50, align:"right",editable:true}
    ],
        
    rowNum:10, rowList:[10,20,30],
    pager: '#pager_noticia',
    sortname: 'id',
    viewrecords: true,
    sortorder: "desc",
    gridComplete: function(){
        var ids = $("#list_noticia").getDataIDs();
        for(var i=0;i < ids.length;i++){
            var cl = ids[i];
            bv = "<input style='height:22px;width:32px;' type='button' value='Ver' id='teste' />";
            ba = "<input style='height:22px;width:58px;' type='button' value='Aprovar' />";
           
            $("#list_noticia").setRowData(ids[i],{act:bv+ba}); } },
    editurl: "local",
    caption:"Noticias Pendentes "
});


var mydata3 = [ {nome:"Renata",email:"note",noticia:"not-1", status:"AP"},
                {nome:"Renata",email:"note",noticia:"not-1", status:"AP"},            ];
            for(var i=0;i < mydata3.length;i++) $("#list_noticia").addRowData(mydata3[i].id,mydata3[i]);
            function pickdates(id){
                $("#"+id+"_sdate","#list_noticia").datepicker({dateFormat:"yy-mm-dd"}); }

var $dialog = $('#form_cad_administrador').dialog({
        width:350,
        height:260,
        modal: true,
        autoOpen: false

    });

$("#teste").click(function(){
     $dialog.dialog('option', 'title', 'Nome da disciplina');
       $dialog.dialog('open');
    
})

</script>



<p class="heading">Aprovar Noticias</p>


<div id="form">
    <table id="list_noticia" class="scroll" cellpadding="0" cellspacing="0"> </table>
    <div id="pager_noticia" class="scroll" style="text-align:center;"></div>
</div>

<div id="button">
    <div id="bt_salvar_professor" class="button_salvar" onclick="">
        <p class="button">Salvar</p>
    </div>
    <div id="bt_apagar_professor" class="button_apagar" onclick="">
        <p class="button">Apagar</p>
    </div>
    <div id="bt_adicionar_professor" class="button_adicionar" onclick="">
        <p class="button">Novo</p>
    </div>
</div>


<!-- Formulário de cadastro e edição-->
<div id="cad_adm" style="display:none;">
    <form class="dialog-form" id="form_cad_administrador" style="background-color:white;" >
       

                    <p>kkkkkkkkkkkkkkkoisjhiudh</p>
       
    </form>
  </div>


