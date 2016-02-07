;(function($, window, document) {
	$('document').ready(function(){
		var $ci = $('div.calendar-input').eq(0);

		$('#calendar_date_start').datepicker({
			dateFormat: "yy-mm-dd",
			minDate: $ci.data('min-date'),
			maxDate: $ci.data('max-date')
		});

		$('#calendar_date_end').datepicker({
			dateFormat: "yy-mm-dd",
			minDate: $ci.data('min-date'),
			maxDate: $ci.data('max-date') + $ci.data('max-length')
		});

	});
})(jQuery, window, document);
