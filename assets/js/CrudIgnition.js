function confirmDeleteModel(e) {
	e.preventDefault();
	$('#delete-confirmation').data('model', e.target.getAttribute('data-delete'));
	$('#delete-confirmation').modal();	
}

function deleteModel() {
	var data = {model: $('#delete-confirmation').data('model')};
	if($('#drop_table').prop('checked')==true) {
		data.drop_table = 1;
	}
	$.post(CRUD_IGNITION_URL + 'delete_model', data, onDeleteModel, 'json');
}

function cancelDeleteModel() {
	$('#delete-confirmation').modal('hide');
}

function onDeleteModel(ajax_result) {
	if(ajax_result.success == true) {		
		$('#delete-confirmation').modal('hide');
		$('tr[data-model="' + ajax_result.model + '"]').find('*').css('text-decoration', 'line-through').attr('disabled','disabled');
	} else {		
		$('#delete-confirmation').modal('hide');
		alert('Delete error (check file permissions');
	}
}

function check_sync(e) {
	e.preventDefault();
	var model = e.target.getAttribute('data-sync');
	$.post(CRUD_IGNITION_URL + 'check_sync', {model: model}, onCheckSync, 'json');
}

function onCheckSync(ajax_result) {	
	if(ajax_result.sync == true) {
		$('#sync-results ul li').remove();
		$('#sync-results ul').append('<li>All good! Table and model match.</li>');
	} else {		
		$('#sync-results ul li').remove();
		for(var i=0; i<ajax_result.sync.length; i++) {
			$('#sync-results ul').append('<li>' + ajax_result.sync[i] + '</li>');
		}
	}
	$('#sync-results').modal();
}

$(document).ready(function() {
	$('a[data-sync]').on('click', check_sync);
	$('a[data-delete]').on('click', confirmDeleteModel);
	
	
	$('#delete-confirmation').find('.confirm').click(deleteModel);
	$('#delete-confirmation').find('.cancel').click(cancelDeleteModel);
});