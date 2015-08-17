$(document).ready(function() {
	$('a[data-plugin="popupedit"]').live('click', function(e) {
		if($('#edit_frame').size()==0) {
			$('body').append('<iframe id="edit_frame"></iframe>');
		}		
		$('#edit_frame').css({
			'position': 'fixed',
			'top': '25px',
			'left': '25px',
			'width': ($(window).width()-50) + 'px',
			'height': ($(window).height()-50) + 'px',
			'box-shadow': '0px 0px 15px #333',
			'z-index': 10001,
		});
		$('body').css({height: $(window).height(), overflow: 'hidden'});
		$('#edit_frame').attr('src', $(this).data('url')).
			data('callback', $(this).data('callback')).
			data('caller', $(this)).show();
		
		e.preventDefault();
	});
	
	if($('body').data('window') == 'popup') {
		$('.btn-cancel').click(function() {
			parent.onFrameCancel();
		});
	}
});

function reset_body() {
	$('body').css({'height': 'auto', 'overflow': 'auto'});
	$('#edit_frame').attr('src', '').hide();
}

function onFrameCancel() {
	reset_body();
}

function onFrameSave(data) {
	var callback = $('#edit_frame').data('callback');
	var caller = $('#edit_frame').data('caller');
	if(window[callback]) {
		window[callback].call(caller, data);
	}
	reset_body();
}