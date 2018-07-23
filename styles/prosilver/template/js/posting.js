;(function($, window, document) {
	$('document').ready(function(){
		var $ci = $('div.marttiphpbb-calendarinput');
		var $start = $ci.find('input[data-marttiphpbb-calendarinput="start"]');
		var $end = $ci.find('input[data-marttiphpbb-calendarinput="end"]');
		var data = $ci.data('marttiphpbb-calendarinput');

		var startPicker = $start.datepicker({
			altField: "#alt_" + $start.attr('id'),
			altFormat: "yy-mm-dd",
			dateFormat: data.dateFormat,
			minDate: data.minLimit,
			maxDate: data.maxLimit,
			onSelect: function(textDate, inst){
				startSelect(textDate, inst);
			}
		});

		var endPicker = $end.datepicker({
			altField: "#alt_" + $end.attr('id'),
			altFormat: "yy-mm-dd",
			dateFormat: data.dateFormat,
			minDate: data.minLimit,
			maxDate: data.maxLimit + data.maxDuration,
			onSelect: function(textDate, inst){
				endSelect(textDate, inst);
			}
		});

		function startSelect(dateText, inst){
			console.log('start');
			console.log(dateText);
			console.log(inst);
		}

		function endSelect(dateText, inst){
			console.log('end');
			console.log(dateText);
		}
	});
})(jQuery, window, document);
