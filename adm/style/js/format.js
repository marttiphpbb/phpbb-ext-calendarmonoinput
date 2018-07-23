;(function($, window, document) {
	$('document').ready(function () {
		var $formatSelect = $('#date_format');
		var $formatShow = $('#date_format_show');

		var datePicker = $formatShow.datepicker({
			dateFormat: $($dateFormat).val(),
		});

		$formatSelect.keyUp(function(){
			datepicker.option('dateFormat', $(this).val());
		});
	});
})(jQuery, window, document);
