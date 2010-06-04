<script type="text/javascript">

$("#submit").click(function(){

    var email = $('#campo_email').val();

    dataString = 'email=' + email;
                    $.ajax({
                    type: "POST",
                    url: "libs/lib_recupera_senha_envia_email.php",
                    processData: false,
                    data: dataString
                });
	
})
	
</script>
<body>
	<div>
  		<form >
    	<label></label>
    	<p>Digite o seu email: 
      	<input id="campo_email" type="text"><input type="button" id="submit" value="Submit">
    	</p>
    	</form>
	</div>
</body>