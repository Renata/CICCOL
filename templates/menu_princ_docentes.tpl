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
jQuery("#list_professor").jqGrid({
    url:'local',
    width: 520,
    datatype: "xml",
    loadtext: "Carregando...",
    colNames:['Nome','E-Mail'],
    colModel:[
        {name:'nome',index:'nome', width:150,align:"center"},
        {name:'email',index:'email', width:200, editable:false,align:"center"},
    ],
    rowNum:10,
    rowList:[10,20,30],
    pager: '#pager_professor',
    sortname: 'id',
    viewrecords: true,
    sortorder: "desc",
    imgpath: 'themes/steel/images',
    onSelectRow: function(id){
        if(id && id!=lastsel){
            $('#list_professor').restoreRow(lastsel);
            $('#list_professor').editRow(id,true,pickdates);
            lastsel=id;
        }
    },
    editurl: "local",
    caption: "Professor"
});

var mydata3 = [ {nome:"Renata",email:"remarins15@gmail.com"},
                {nome:"Michele",email:"ciriaco.leo@gmail.com"},
                {nome:"Leonardo",email:"micheleerafick@gmail.com"},
            ];
            for(var i=0;i < mydata3.length;i++) $("#list_professor").addRowData(mydata3[i].id,mydata3[i]);
            function pickdates(id){
                $("#"+id+"_sdate","#list_professor").datepicker({dateFormat:"yy-mm-dd"}); }




</script>


<p class="heading">Docentes</p>


<div id="form">
    <table id="list_professor" class="scroll" cellpadding="0" cellspacing="0"> </table>
    <div id="pager_professor" class="scroll" style="text-align:center;"></div>
</div>









