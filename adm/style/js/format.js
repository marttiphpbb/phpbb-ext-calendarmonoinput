;(function($, window, document) {
	$('document').ready(function () {
		var $dateFormat = $('#date_format');
		var $formatShow = $('#date_format_show');

		$formatShow.datepicker({
			dateFormat: $($dateFormat).val(),
		});

		$formatShow.datepicker('setDate', '+0d');

		$dateFormat.keyup(function(){
			var format = $(this).val();
			$formatShow.datepicker('option', 'dateFormat', format);
		});
	});
})(jQuery, window, document);
