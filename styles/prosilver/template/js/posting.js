;(function($, window, document) {
	$('document').ready(function(){
		var $ci = $('div.marttiphpbb-calendarmonoinput');
		var data = $ci.data('marttiphpbb-calendarmonoinput');
		var endEn = data.maxDuration > 1;

		var $start = $ci.find('input[data-marttiphpbb-calendarmonoinput="start"]');
		var altStartId = '#alt_' + $start.attr('id');
		var startStr = $(altStartId).val();

		var minStartDate = new Date((data.refTime + (data.minLimit * 86400)) * 1000);
		var maxStartDate = new Date((data.refTime + (data.maxLimit * 86400)) * 1000);
		var minEndDate = new Date((data.refTime + ((data.minLimit + data.minDuration - 1) * 86400)) * 1000);
		var maxEndDate = new Date((data.refTime + ((data.maxLimit + data.maxDuration) * 86400)) * 1000);

		var startPicker = $start.datepicker({
			altField: altStartId,
			altFormat: "yy-mm-dd",
			firstDay: data.firstDay,
			dateFormat: data.dateFormat,
			minDate: minStartDate,
			maxDate: maxStartDate,
			onSelect: function(textDate, inst){
				startSelect();
			}
		});

		if (startStr){
			$start.datepicker('setDate', new Date(startStr));
		}

		if (endEn){
			var $end = $ci.find('input[data-marttiphpbb-calendarmonoinput="end"]');
			var altEndId = '#alt_' + $end.attr('id');
			var endStr = $(altEndId).val();

			var endPicker = $end.datepicker({
				altField: altEndId,
				altFormat: "yy-mm-dd",
				firstDay: data.firstDay,
				dateFormat: data.dateFormat,
				minDate: minEndDate,
				maxDate: maxEndDate,
				onSelect: function(textDate, inst){
					endSelect();
				}
			});

			if (endStr && startStr){
				$end.datepicker('setDate', new Date(endStr));
				startSelect();
			}
		}

		function startSelect(){
			if (endEn){
				var refDate = $start.datepicker('getDate');
				var minDate = new Date(refDate.getTime() + (data.minDuration - 1) * 86400000);
				var maxDate = new Date(refDate.getTime() + data.maxDuration * 86400000);
				$end.datepicker('option', 'minDate', minDate);
				$end.datepicker('option', 'maxDate', maxDate);
			}
		}

		function endSelect(){

		}
	});
})(jQuery, window, document);
