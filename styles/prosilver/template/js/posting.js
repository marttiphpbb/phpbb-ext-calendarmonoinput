;(function($, window, document) {
	$('document').ready(function(){
		var $ci = $('div.marttiphpbb-calendarinput');
		var $start = $ci.find('input[data-marttiphpbb-calendarinput="start"]');
		var $end = $ci.find('input[data-marttiphpbb-calendarinput="end"]');

//		$start.datepicker();
//		$end.datepicker();

		var data = $ci.data('marttiphpbb-calendarinput');

		$start.datepicker({
			dateFormat: "MM dd, yy",
			minDate: data.lower_limit,
			maxDate: data.upper_limit
		});

		$end.datepicker({
			dateFormat: "M dd yy",
			minDate: data.lower_limit,
			maxDate: data.upper_limit + data.max_duration
		});

	});
})(jQuery, window, document);
