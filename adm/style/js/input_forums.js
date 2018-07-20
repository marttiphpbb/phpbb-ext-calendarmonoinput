;(function($, window, document) {
	$('document').ready(function () {
		var $enabled = $('input[type="checkbox"][data-enabled]');
		$enabled.filter(':not(:checked)')
			.parent()
			.parent()
			.find('input[type="checkbox"][data-required]')
			.prop('checked', false)
			.prop('disabled', true);
		$enabled.change(function(){
			$(this).filter(':checked')
				.parent()
				.parent()
				.find('input[type="checkbox"][data-required]')
				.prop('disabled', false);
			$(this).filter(':not(:checked)')
				.parent()
				.parent()
				.find('input[type="checkbox"][data-required]')
				.prop('disabled', true)
				.prop('checked', false);
		});
	});
})(jQuery, window, document);
