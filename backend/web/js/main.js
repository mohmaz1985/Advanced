jQuery(function(){
	$('#modalButton').click(function(){
		
		var url = $(this).attr('value');
		$('#modal-form').find('#modalContant')
			.load(url);
		$('#loadPage').show();
		setTimeout(function(){
			$('#modal-form').modal('show');
			$('#loadPage').hide();
		}, 500);

	});
	//Scroll main model after close sub
	$(document).on("hidden.bs.modal","#kvFileinputModal.modal", function () {
  		$("body").addClass("modal-open");
  	});
});
