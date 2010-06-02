 $(document).ready(function(){

var lastsel;
$("#list").jqGrid({
    url:'local',
    width: 300,
    datatype: "xml",
    loadtext: "Carregando...",
    colNames:['Actions','Inv No','Date', 'Client', 'Amount','Tax','Total'],
    colModel:[
        {name:'act',index:'act', width:75,sortable:false},
        {name:'id',index:'id', width:55},
        {name:'invdate',index:'invdate', width:90, editable:true},
        {name:'name',index:'name', width:100,editable:true},
        {name:'amount',index:'amount', width:80, align:"right",editable:true},
        {name:'tax',index:'tax', width:80, align:"right",editable:true},
        {name:'total',index:'total', width:80,align:"right",editable:true},
        
        
    ],
    rowNum:10,
    rowList:[10,20,30],
    pager: '#pager',
    sortname: 'id',
    viewrecords: true,
    sortorder: "desc",
    imgpath: 'themes/steel/images',
    onSelectRow: function(id){
        if(id && id!==lastsel){
            $('#list').restoreRow(lastsel);
            $('#list').editRow(id,true,pickdates);
            lastsel=id; 
        }
    },
    editurl: "local",
    caption: "Moderador"
}).navGrid("#pager",
{},
{height:280,reloadAfterSubmit:false},
{height:280,reloadAfterSubmit:false},
{reloadAfterSubmit:false},
{});
 });

