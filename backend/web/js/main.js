$(function(){

	$('#modalButton').click(function(){
		$('#modal').modal('show')
		.find('#modalContant')
		.load($(this).attr('value'));
	});
});