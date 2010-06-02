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
jQuery("#list_grade_visualizar").jqGrid({
    url:'local',
    width: 520,
    datatype: "xml",
    colNames:['Disciplina', 'Semestre', 'Professor'],
    colModel:[
        {name:'disciplina',index:'disciplina', width:160,sortable:false,align:"left"},
        {name:'semestre',index:'semestre', width:160,sortable:false,align:"center"},
        {name:'professor',index:'professor', width:160,sortable:false,align:"right"}
    ],
    rowNum:10,
    rowList:[10,20,30],
    pager: '#pager_grade_visualizar',
    sortname: 'id',
    viewrecords: true,
    sortorder: "desc",
    imgpath: 'themes/steel/images',
    onSelectRow: function(id){
        if(id && id!=lastsel){
            $('#list_grade_vizualizar').restoreRow(lastsel);
            
            lastsel=id;
        }
    }, 
    editurl: "local",
    caption: "Minha Grade"
});

var mydata3 = [ {disciplina:"Logica de programacao",semestre:'00',professor:"professor"},
                {disciplina:"Logica de programacao",semestre:'00',professor:"professor"},
                {disciplina:"Logica de programacao",semestre:'00',professor:"professor"},
                ];
            for(var i=0;i < mydata3.length;i++) $("#list_grade_visualizar").addRowData(mydata3[i].id,mydata3[i]);
            



</script>




<p class="heading">Minha Grade</p>



<div id="form">
    
    <table id="list_grade_visualizar" class="scroll" cellpadding="0" cellspacing="0"> </table>
    <div id="pager_grade_visualizar" class="scroll" style="text-align:center;"></div>
</div>





