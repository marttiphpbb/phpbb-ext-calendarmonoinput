;(function($, window, document) {
	$('document').ready(function(){
		var $ci = $('div.marttiphpbb-calendarinput');
		var $start = $ci.find('input[data-marttiphpbb-calendarinput="start"]');
		var $end = $ci.find('input[data-marttiphpbb-calendarinput="end"]');

		var data = $ci.data('marttiphpbb-calendarinput');

		console.log(data.minLimit);
		console.log(data.maxLimit);

		var startPicker = $start.datepicker({
			altField: "#alt_" + $start.attr('id'),
			altFormat: "yy-mm-dd",
			dateFormat: "yy-mm-dd",
			minDate: data.minLimit,
			maxDate: data.maxLimit
		});

		var endPicker = $end.datepicker({
			altField: "#alt_" + $end.attr('id'),
			altFormat: "yy-mm-dd",
			dateFormat: "yy-mm-dd",
			minDate: data.minLimit,
			maxDate: data.maxLimit + data.maxDuration
		});
	});
})(jQuery, window, document);
