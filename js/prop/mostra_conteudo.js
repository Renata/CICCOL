$(document).ready(function() {
    $("#menu_right li, .mostra").click(function() {
	var opcao = $(this).attr('id');
        var op ="manage_"+opcao+".php";
	$("#content").load(op);
    });
});