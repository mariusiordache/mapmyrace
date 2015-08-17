function confirmDeleteItem(e) {
	e.preventDefault();
	$('#delete-confirmation').data('id', e.target.getAttribute('data-delete'));
	$('#delete-confirmation').modal();	
}

function deleteItem(e) {
	var data = {id: $('#delete-confirmation').data('id')};
	$.post(CRUD_MANAGER_URL + 'delete', data, onDeleteItem, 'json');
	e.preventDefault();
}

function cancelDeleteItem() {
	$('#delete-confirmation').modal('hide');
}

function onDeleteItem(ajax_result) {
	if(ajax_result.success == true) {		
		$('#delete-confirmation').modal('hide');
		$('tr[data-item="' + ajax_result.id + '"]').find('*').css('text-decoration', 'line-through').attr('disabled','disabled');
	} else {		
		$('#delete-confirmation').modal('hide');
		alert('Delete error');
	}	
}

$(document).ready(function() {
	$('a[data-delete]').on('click', confirmDeleteItem);
	$('#delete-confirmation').find('.confirm').click(deleteItem);
	$('#delete-confirmation').find('.cancel').click(cancelDeleteItem);
});