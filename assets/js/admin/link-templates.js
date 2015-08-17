$(document).ready(function() {

	$('[data-delete]').click(function(e) {
		if(confirm('Are you sure you want to delete this deal?')) {
			$.post('/admin/link_templates/delete/' + $(this).data('delete'), {}, function(ajax_result) {
				if(ajax_result.success) {
					$('#template-' + ajax_result.id).fadeOut();
				}
			});
		}
		e.preventDefault();
	});
	
	$('tr').hover(function(){$(this).css('background', '#FFF');}, function(){$(this).css('background', '');});

});