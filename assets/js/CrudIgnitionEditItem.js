$(document).ready(function() {
	$('input[data-plugin]').each(function() {
		switch(this.getAttribute('data-plugin')) {
			case 'datepicker':
				$(this).datepicker({format: "yyyy-mm-dd"});
				break;
			case 'datetimepicker':
				$(this).datetimepicker();
				break;
		}
	});
	
	$('.toggler').click(function() {
		var related_field = $('#' + this.getAttribute('data-field'));
		var plugin = related_field.data('datetimepicker');
		if(this.checked) {
			related_field.attr('disabled','disabled');
			if(plugin) {
				plugin.disable();
			}
		} else {
			related_field.removeAttr('disabled');
			if(plugin) {
				plugin.enable();
			}
		}
	});	
});