<script type="text/javascript">
    $(document).ready(function() {
        $("#bt_salvar_professor").click(function() {
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
    <link rel="stylesheet" type="text/css" media="screen" href="themes/ui.datepicker.css" />
    <script type='text/javascript' src='js/grid.locale-pt-br.js'></script>
</head>


<script type="text/javascript" charset="ISO-8859-1">

$("#list_sms_historico").jqGrid({
    url:'local',
    width: 510,
    datatype: "xml",
    colNames:['Ação','Data','Disciplina'],
    colModel:[
        {name:'act',index:'act', width:75,sortable:false},
        {name:'data',index:'data', width:90, editable:false},
        {name:'disciplina',index:'disciplina', width:100,editable:false},
    ],
        
    rowNum:10, rowList:[10,20,30],
    pager: '#pager_sms_historico',
    sortname: 'id',
    viewrecords: true,
    sortorder: "desc",
    gridComplete: function(){
        var ids = $("#list_sms_historico").getDataIDs();
        for(var i=0;i < ids.length;i++){
            var cl = ids[i];
            bv = "<input style='height:22px;width:32px;' type='button' value='Ver'  />";
           
            $("#list_sms_historico").setRowData(ids[i],{act:bv}); } },
    editurl: "local",
    caption:"Historico de SMS"
});


var mydata3 = [ {data:"data",disciplina:"disciplina"},
               {data:"data",disciplina:"disciplina"},
           ];
            for(var i=0;i < mydata3.length;i++) $("#list_sms_historico").addRowData(mydata3[i].id,mydata3[i]);
            function pickdates(id){
                $("#"+id+"_sdate","#list_sms_historico").datepicker({dateFormat:"yy-mm-dd"}); }



</script>



<p class="heading">SMS enviados</p>


<div id="form">
    <table id="list_sms_historico" class="scroll" cellpadding="0" cellspacing="0"> </table>
    <div id="pager_sms_historico" class="scroll" style="text-align:center;"></div>
</div>






