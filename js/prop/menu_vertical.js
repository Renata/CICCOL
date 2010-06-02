       $(document).ready(function(){
		$('#menu_right').css('height', 'auto');
		$('#menu_right ul').hide();
		$('#menu_right h3').click(function(){
			$(this).next().slideToggle('fast')
			.siblings('ul:visible').slideUp('fast');
			$(this).toggleClass('corrente');
			$(this).siblings('#menu_right h3').removeClass('corrente');
		});
	});