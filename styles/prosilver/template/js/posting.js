;(function($, window, document) {
	$('document').ready(function(){
		var $ci = $('div.marttiphpbb-calendarinput');
		var $start = $ci.find('input[data-marttiphpbb-calendarinput="start"]');
		var $end = $ci.find('input[data-marttiphpbb-calendarinput="end"]');
		var data = $ci.data('marttiphpbb-calendarinput');

		var startPicker = $start.datepicker({
			altField: "#alt_" + $start.attr('id'),
			altFormat: "yy-mm-dd",
			firstDay: data.firstDay,
			dateFormat: data.dateFormat,
			minDate: data.minLimit,
			maxDate: data.maxLimit,
			onSelect: function(textDate, inst){
				startSelect(inst);
			}
		});

		var endPicker = $end.datepicker({
			altField: "#alt_" + $end.attr('id'),
			altFormat: "yy-mm-dd",
			firstDay: data.firstDay,
			dateFormat: data.dateFormat,
			minDate: data.minLimit + data.minDuration,
			maxDate: data.maxLimit + data.maxDuration,
			onSelect: function(textDate, inst){
				endSelect(inst);
			}
		});

		function startSelect(inst){
			console.log(inst);
			var refDate = $start.datepicker('getDate');
			var minDate = new Date(refDate.getTime() + data.minDuration * 86400000);
			var maxDate = new Date(refDate.getTime() + data.maxDuration * 86400000);
			$end.datepicker('option', 'minDate', minDate);
			$end.datepicker('option', 'maxDate', maxDate);
//			$end.datepicker('refresh');
		}

		function endSelect(inst){
//
		}
	});
})(jQuery, window, document);
