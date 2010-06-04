<script type="text/javascript">

// ------------------------------------Grid Projeto---------------------------
var lastsel;
jQuery("#list_cad_projeto").jqGrid({
    url:'libs/lib_projeto.php?reference=projeto&action=grid_buscar_projeto',
    width: 400,
    height:150,
    datatype: "xml",
    colNames:['Descrição'],
    colModel:[
        {name:'cad_projeto_descricao',index:'cad_projeto_descricao', width:100,sortable:false,align:"left"}
    ],
    rowNum:10,
    pager: '#pager_cad_projeto',
    sortname: 'id',
    viewrecords: true,
    sortorder: "desc",
    shrinkToFit:false,
    scrollOffset: true,
    hiddengrid: true,
    imgpath: 'themes/steel/images',
    onSelectRow: function(id){
        if(id && id!=lastsel){
            $('#list_cad_projeto').restoreRow(lastsel);

            lastsel=id;
        }
    },
    //editurl: "local",
    caption: "Projetos de Pesquisa"
});
</script>

<p class="heading">Meu Currículo</p>

<!-- Formulário de cadastro e edição -->
<div id="ver_curriculo">
    <form class="dialog-form" id="form_cad_curriculo">
        <fieldset class="ui-widget ui-widget-content ui-corner-all">
		<legend class="ui-widget ui-widget-header ui-corner-all"><!--{$docente}--></legend>
                    <br/>

                    <label>Perfil Profissional</label>
                    <textarea readonly="readonly" class="text ui-widget-content ui-corner-all" cols="35" rows="6"><!--{$perfil}--></textarea><br/><br/>

                    <label>Último Emprego</label>
                    <textarea readonly="readonly" class="text ui-widget-content ui-corner-all" cols="35" rows="3"><!--{$ultEmprego}--></textarea><br/><br/>

                    <label>Cargo Atual</label>
                    <input readonly="readonly" class="text ui-widget-content ui-corner-all" value="<!--{$cargo}-->"/><br/><br/>

                    <label>Áreas de Interesse</label>
                    <textarea readonly="readonly" class="text ui-widget-content ui-corner-all" cols="35" rows="2"><!--{$interesse}--></textarea>
                    <br/><br/><br/>
                    
                    <div id="form_grid_projeto">
                        <table id="list_cad_projeto" style="float: right" class="scroll" cellpadding="0" cellspacing="0"> </table>
                        <div id="pager_cad_projeto" class="scroll" style="text-align:center;"></div>
                    </div>
                    <br/>
         </fieldset>
    </form>
  </div>





