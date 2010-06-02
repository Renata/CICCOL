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
jQuery("#list_contato").jqGrid({
    url:'local',
    width: 520,
    datatype: "xml",
    colNames:['Nome', 'Telefone','E-mail'],
    colModel:[
        {name:'nome',index:'nome', width:160,sortable:false,align:"center"},
        {name:'telefone',index:'telefone', width:50,sortable:false,align:"center"},
        {name:'email',index:'email', width:160,sortable:false,align:"center"}
    ],
    rowNum:10,
    rowList:[10,20,30],
    pager: '#pager_contato',
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
    editurl: "local"
    
});

var mydata3 = [ {nome:"Colegiado",telefone:"999999999", email:'algumacoissa@all.com.br'},
                {nome:"Colegiado",telefone:"999999999", email:'algumacoissa@all.com.br'},
                {nome:"Colegiado",telefone:"999999999", email:'algumacoissa@all.com.br'},
                {nome:"Colegiado",telefone:"999999999", email:'algumacoissa@all.com.br'},
                ];
            for(var i=0;i < mydata3.length;i++) $("#list_contato").addRowData(mydata3[i].id,mydata3[i]);




</script>




<p class="heading">Contatos</p>





<div id="form">

    <table id="list_contato" class="scroll" cellpadding="0" cellspacing="0"> </table>
    <div id="pager_contato" class="scroll" style="text-align:center;"></div>
</div>






