;(function($, window, document) {

	$('document').ready(function(){

		$('#calendar_date_start').datepicker({
			dateFormat: "yy-mm-dd",
			minDate: calendarMinDate,
			maxDate: calendarMaxDate
		});

		$('#calendar_date_end').datepicker({
			dateFormat: "yy-mm-dd",
			minDate: calendarMinDate,
			maxDate: calendarMaxDate + calendarMaxLength
		});
		
	});

})(jQuery, window, document);
