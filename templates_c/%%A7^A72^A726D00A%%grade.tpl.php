<?php /* Smarty version 2.6.26, created on 2010-05-05 20:31:30
         compiled from grade.tpl */ ?>
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

var lastsel;
jQuery("#list_grade_1").jqGrid({
    url:'local',
    width: 510,
    datatype: "xml",
    colNames:['Materia', 'CH', 'Cred', 'Pre-Requisito'],
    colModel:[
        {name:'disciplina',index:'disciplina', width:70, editable:false,align:"center"},
        {name:'carhoraria',index:'carhoraria', width:20,editable:true,sorttype:"date", align:"center"},
        {name:'creditos',index:'creditos', width:20, align:"center",editable:false},
        {name:'prerequisito',index:'prerequisito', width:70,editable:true,sorttype:"date", align:"center"},
    ],
    rowNum:10,
    rowList:[10,20,30],
    pager: '#pager_grade_1',
    sortname: 'id',
    viewrecords: true,
    sortorder: "desc",
    imgpath: 'themes/steel/images',
    hiddengrid: true,
    onSelectRow: function(id){
        if(id && id!=lastsel){
            $('#list_professor').restoreRow(lastsel);
            $('#list_professor').editRow(id,true,pickdates);
            lastsel=id;
        }
    },
    editurl: "local",
    caption: "1 Semestre"

});

var mydata3 = [ {disciplina:"note",carhoraria:"20", creditos:"3", prerequisito:"5"},
                {disciplina:"note",carhoraria:"20", creditos:"3", prerequisito:"5"},
                {disciplina:"note",carhoraria:"20", creditos:"3", prerequisito:"5"},
            ];
            for(var i=0;i < mydata3.length;i++) $("#list_grade_1").addRowData(mydata3[i].id,mydata3[i]);
            function pickdates(id){
                $("#"+id+"_sdate","#list_grade_1").datepicker({dateFormat:"yy-mm-dd"}); }






jQuery("#list_grade_2").jqGrid({
    url:'local',
    width: 510,
    datatype: "xml",
    colNames:['Materia', 'CH', 'Cred', 'Pre-Requisito'],
    colModel:[
        {name:'disciplina',index:'disciplina', width:70, editable:false,align:"center"},
        {name:'carhoraria',index:'carhoraria', width:20,editable:true,sorttype:"date", align:"center"},
        {name:'creditos',index:'creditos', width:20, align:"center",editable:false},
        {name:'prerequisito',index:'prerequisito', width:70,editable:true,sorttype:"date", align:"center"},
    ],
    rowNum:10,
    rowList:[10,20,30],
    pager: '#pager_grade_2',
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
    caption: "2 Semestre",
    hiddengrid: true 

});

var mydata3 = [ {disciplina:"note",carhoraria:"20", creditos:"3", prerequisito:"5"},
                {disciplina:"note",carhoraria:"20", creditos:"3", prerequisito:"5"},
                {disciplina:"note",carhoraria:"20", creditos:"3", prerequisito:"5"},
            ];
            for(var i=0;i < mydata3.length;i++) $("#list_grade_2").addRowData(mydata3[i].id,mydata3[i]);
            function pickdates(id){
                $("#"+id+"_sdate","#list_grade_2").datepicker({dateFormat:"yy-mm-dd"}); }







jQuery("#list_grade_3").jqGrid({
    url:'local',
    width: 510,
    datatype: "xml",
    colNames:['Materia', 'CH', 'Cred', 'Pre-Requisito'],
    colModel:[
        {name:'disciplina',index:'disciplina', width:70, editable:false,align:"center"},
        {name:'carhoraria',index:'carhoraria', width:20,editable:true,sorttype:"date", align:"center"},
        {name:'creditos',index:'creditos', width:20, align:"center",editable:false},
        {name:'prerequisito',index:'prerequisito', width:70,editable:true,sorttype:"date", align:"center"},
    ],
    rowNum:10,
    rowList:[10,20,30],
    pager: '#pager_grade_3',
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
    caption: "3 Semestre",
    hiddengrid: true

});

var mydata3 = [ {disciplina:"note",carhoraria:"20", creditos:"3", prerequisito:"5"},
                {disciplina:"note",carhoraria:"20", creditos:"3", prerequisito:"5"},
                {disciplina:"note",carhoraria:"20", creditos:"3", prerequisito:"5"},
            ];
            for(var i=0;i < mydata3.length;i++) $("#list_grade_3").addRowData(mydata3[i].id,mydata3[i]);
            function pickdates(id){
                $("#"+id+"_sdate","#list_grade_3").datepicker({dateFormat:"yy-mm-dd"}); }








jQuery("#list_grade_4").jqGrid({
    url:'local',
    width: 510,
    datatype: "xml",
    colNames:['Materia', 'CH', 'Cred', 'Pre-Requisito'],
    colModel:[
        {name:'disciplina',index:'disciplina', width:70, editable:false,align:"center"},
        {name:'carhoraria',index:'carhoraria', width:20,editable:true,sorttype:"date", align:"center"},
        {name:'creditos',index:'creditos', width:20, align:"center",editable:false},
        {name:'prerequisito',index:'prerequisito', width:70,editable:true,sorttype:"date", align:"center"},
    ],
    rowNum:10,
    rowList:[10,20,30],
    pager: '#pager_grade_4',
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
    caption: "4 Semestre",
    hiddengrid: true

});

var mydata3 = [ {disciplina:"note",carhoraria:"20", creditos:"3", prerequisito:"5"},
                {disciplina:"note",carhoraria:"20", creditos:"3", prerequisito:"5"},
                {disciplina:"note",carhoraria:"20", creditos:"3", prerequisito:"5"},
            ];
            for(var i=0;i < mydata3.length;i++) $("#list_grade_4").addRowData(mydata3[i].id,mydata3[i]);
            function pickdates(id){
                $("#"+id+"_sdate","#list_grade_4").datepicker({dateFormat:"yy-mm-dd"}); }








jQuery("#list_grade_5").jqGrid({
    url:'local',
    width: 510,
    datatype: "xml",
    colNames:['Materia', 'CH', 'Cred', 'Pre-Requisito'],
    colModel:[
        {name:'disciplina',index:'disciplina', width:70, editable:false,align:"center"},
        {name:'carhoraria',index:'carhoraria', width:20,editable:true,sorttype:"date", align:"center"},
        {name:'creditos',index:'creditos', width:20, align:"center",editable:false},
        {name:'prerequisito',index:'prerequisito', width:70,editable:true,sorttype:"date", align:"center"},
    ],
    rowNum:10,
    rowList:[10,20,30],
    pager: '#pager_grade_5',
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
    caption: "5 Semestre",
    hiddengrid: true

});

var mydata3 = [ {disciplina:"note",carhoraria:"20", creditos:"3", prerequisito:"5"},
                {disciplina:"note",carhoraria:"20", creditos:"3", prerequisito:"5"},
                {disciplina:"note",carhoraria:"20", creditos:"3", prerequisito:"5"},
            ];
            for(var i=0;i < mydata3.length;i++) $("#list_grade_5").addRowData(mydata3[i].id,mydata3[i]);
            function pickdates(id){
                $("#"+id+"_sdate","#list_grade_5").datepicker({dateFormat:"yy-mm-dd"}); }








jQuery("#list_grade_6").jqGrid({
    url:'local',
    width: 510,
    datatype: "xml",
    colNames:['Materia', 'CH', 'Cred', 'Pre-Requisito'],
    colModel:[
        {name:'disciplina',index:'disciplina', width:70, editable:false,align:"center"},
        {name:'carhoraria',index:'carhoraria', width:20,editable:true,sorttype:"date", align:"center"},
        {name:'creditos',index:'creditos', width:20, align:"center",editable:false},
        {name:'prerequisito',index:'prerequisito', width:70,editable:true,sorttype:"date", align:"center"},
    ],
    rowNum:10,
    rowList:[10,20,30],
    pager: '#pager_grade_1',
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
    caption: "6 Semestre",
    hiddengrid: true

});

var mydata3 = [ {disciplina:"note",carhoraria:"20", creditos:"3", prerequisito:"5"},
                {disciplina:"note",carhoraria:"20", creditos:"3", prerequisito:"5"},
                {disciplina:"note",carhoraria:"20", creditos:"3", prerequisito:"5"},
            ];
            for(var i=0;i < mydata3.length;i++) $("#list_grade_6").addRowData(mydata3[i].id,mydata3[i]);
            function pickdates(id){
                $("#"+id+"_sdate","#list_grade_6").datepicker({dateFormat:"yy-mm-dd"}); }








jQuery("#list_grade_7").jqGrid({
    url:'local',
    width: 510,
    datatype: "xml",
    colNames:['Materia', 'CH', 'Cred', 'Pre-Requisito'],
    colModel:[
        {name:'disciplina',index:'disciplina', width:70, editable:false,align:"center"},
        {name:'carhoraria',index:'carhoraria', width:20,editable:true,sorttype:"date", align:"center"},
        {name:'creditos',index:'creditos', width:20, align:"center",editable:false},
        {name:'prerequisito',index:'prerequisito', width:70,editable:true,sorttype:"date", align:"center"},
    ],
    rowNum:10,
    rowList:[10,20,30],
    pager: '#pager_grade_7',
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
    caption: "7 Semestre",
    hiddengrid: true

});

var mydata3 = [ {disciplina:"note",carhoraria:"20", creditos:"3", prerequisito:"5"},
                {disciplina:"note",carhoraria:"20", creditos:"3", prerequisito:"5"},
                {disciplina:"note",carhoraria:"20", creditos:"3", prerequisito:"5"},
            ];
            for(var i=0;i < mydata3.length;i++) $("#list_grade_7").addRowData(mydata3[i].id,mydata3[i]);
            function pickdates(id){
                $("#"+id+"_sdate","#list_grade_7").datepicker({dateFormat:"yy-mm-dd"}); }





jQuery("#list_grade_8").jqGrid({
    url:'local',
    width: 510,
    datatype: "xml",
    colNames:['Materia', 'CH', 'Cred', 'Pre-Requisito'],
    colModel:[
        {name:'disciplina',index:'disciplina', width:70, editable:false,align:"center"},
        {name:'carhoraria',index:'carhoraria', width:20,editable:true,sorttype:"date", align:"center"},
        {name:'creditos',index:'creditos', width:20, align:"center",editable:false},
        {name:'prerequisito',index:'prerequisito', width:70,editable:true,sorttype:"date", align:"center"},
    ],
    rowNum:10,
    rowList:[10,20,30],
    pager: '#pager_grade_8',
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
    caption: "8 Semestre",
    hiddengrid: true

});

var mydata3 = [ {disciplina:"note",carhoraria:"20", creditos:"3", prerequisito:"5"},
                {disciplina:"note",carhoraria:"20", creditos:"3", prerequisito:"5"},
                {disciplina:"note",carhoraria:"20", creditos:"3", prerequisito:"5"},
            ];
            for(var i=0;i < mydata3.length;i++) $("#list_grade_8").addRowData(mydata3[i].id,mydata3[i]);
            function pickdates(id){
                $("#"+id+"_sdate","#list_grade_8").datepicker({dateFormat:"yy-mm-dd"}); }













</script>



<p class="heading">Grade Curricular</p>


<div id="form">
    <table id="list_grade_1" class="scroll" cellpadding="0" cellspacing="0"> </table>
    <div id="pager_grade_1" class="scroll" style="text-align:center;"></div>
</div>

<div id="form">
    <table id="list_grade_2" class="scroll" cellpadding="0" cellspacing="0"> </table>
    <div id="pager_grade_2" class="scroll" style="text-align:center;"></div>
</div>

<div id="form">
    <table id="list_grade_3" class="scroll" cellpadding="0" cellspacing="0"> </table>
    <div id="pager_grade_3" class="scroll" style="text-align:center;"></div>
</div>

<div id="form">
    <table id="list_grade_4" class="scroll" cellpadding="0" cellspacing="0"> </table>
    <div id="pager_grade_4" class="scroll" style="text-align:center;"></div>
</div>

<div id="form">
    <table id="list_grade_5" class="scroll" cellpadding="0" cellspacing="0"> </table>
    <div id="pager_grade_5" class="scroll" style="text-align:center;"></div>
</div>

<div id="form">
    <table id="list_grade_6" class="scroll" cellpadding="0" cellspacing="0"> </table>
    <div id="pager_grade_6" class="scroll" style="text-align:center;"></div>
</div>

<div id="form">
    <table id="list_grade_7" class="scroll" cellpadding="0" cellspacing="0"> </table>
    <div id="pager_grade_7" class="scroll" style="text-align:center;"></div>
</div>

<div id="form">
    <table id="list_grade_8" class="scroll" cellpadding="0" cellspacing="0"> </table>
    <div id="pager_grade_8" class="scroll" style="text-align:center;"></div>
</div>




