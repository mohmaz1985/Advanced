jQuery(function(){
	$('#modalButton').click(function(){
		
		
		var url = $(this).attr('value');
		$('#modal').find('#modalContant')
			.load(url);
		$('#loadPage').show();
		setTimeout(function(){
			$('#modal').modal('show');
			$('#loadPage').hide();
		}, 500);

	});
});
