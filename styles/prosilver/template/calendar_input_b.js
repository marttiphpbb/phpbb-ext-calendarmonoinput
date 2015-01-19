var max_periods{SCRIPT_MAX_PERIODS};
var max_period_length{SCRIPT_MAX_PERIOD_LENGTH};
var max_gap{SCRIPT_MAX_GAP};
var min_gap{SCRIPT_MIN_GAP};
var min_start_msec{SCRIPT_MIN_START_MSEC};
var plus_start_msec{SCRIPT_PLUS_START_MSEC};

var date_ary = [{SCRIPT_DATE_ARY}];
var month_option_ary = [{SCRIPT_MONTH_OPTION_ARY}];

var current_time_msec{SCRIPT_CURRENT_TIME_MSEC};

var num_periods = 1;
var edited_date_input = 0;

onload_functions.push('on_load_calendar_input()');

function on_load_calendar_input()
{
// first end date

	var month = document.getElementById('cm1');
	var day = document.getElementById('cd1');
	var year = document.getElementById('cy1');

	month.set_options = set_month_options;
	day.set_options = set_day_options;
	year.set_options = set_year_options;

// first start date

	month = document.getElementById('cm0');
	day = document.getElementById('cd0');
	year = document.getElementById('cy0');

	month.set_options = set_month_options;
	day.set_options = set_day_options;
	year.set_options = set_year_options;

	year.min_msec = current_time_msec - min_start_msec;
	year.max_msec = current_time_msec + plus_start_msec;

	var min_date = new Date(year.min_msec);
	var max_date = new Date(year.max_msec);

	year.min = min_date.getUTCFullYear();
	year.max = max_date.getUTCFullYear();

	month.min = min_date.getUTCMonth() + 1;
	month.max = max_date.getUTCMonth() + 1;

	day.min = min_date.getUTCDate();
	day.max = max_date.getUTCDate();

	month.min_cur = 1;
	month.max_cur = 12;

	day.min_cur = 1;
	day.max_cur = 31;

	if (year.min == year.max)
	{
		month.min_cur = month.min;
		month.max_cur = month.max;

		if (month.min_cur == month.max_cur)
		{
			day.min_cur = day.min;
			day.max_cur = day.max;
		}
	}

	if (date_ary[0].length == 3)
	{
		var month_val = date_ary[0][0];
		var day_val = date_ary[0][1];
		var year_val = date_ary[0][2];

		month.value = month_val;
		day.value = day_val;
		year.value = year_val;

		year.msec = Date.UTC(year_val, month_val - 1, day_val);

		month.min_cur = (year.min == year_val) ? month.min : 1;
		month.max_cur = (year.max == year_val) ? month.max : 12;

		day.min_cur = (month.min_cur == month_val && year.min == year_val) ? day.min : 1;
		day.max_cur = (month.max_cur == month_val && year.max == year_val) ? day.max : days_in_month(month_val, year_val);
	}

	year.set_options();
	day.set_options();
	month.set_options();

	var prev_year = year;

	for (var i = 1; i < date_ary.length; i++)
	{
		var s_start = (i % 2) ? false : true;

		month_val = date_ary[i][0];
		day_val = date_ary[i][1];
		year_val = date_ary[i][2];

		if (s_start)
		{
			insert_new_period();
		}

		month = document.getElementById('cm' + i);
		day = document.getElementById('cd' + i);
		year = document.getElementById('cy' + i);

		if (s_start)
		{
			year.min_msec = prev_year.msec + ((min_gap + 1) * 86400000);
			year.max_msec = prev_year.msec + ((max_gap + 1) * 86400000);
		}
		else
		{
			year.min_msec = prev_year.msec;
			year.max_msec = prev_year.msec + ((max_period_length - 1) * 86400000);
		}

		min_date.setTime(year.min_msec);
		max_date.setTime(year.max_msec);

		year.min = min_date.getUTCFullYear();
		year.max = max_date.getUTCFullYear();

		month.min = min_date.getUTCMonth() + 1;
		month.max = max_date.getUTCMonth() + 1;

		day.min = min_date.getUTCDate();
		day.max = max_date.getUTCDate();

		month.min_cur = (year.min == year_val) ? month.min : 1;
		month.max_cur = (year.max == year_val) ? month.max : 12;

		day.min_cur = (month.min_cur == month_val && year.min == year_val) ? day.min : 1;
		day.max_cur = (month.max_cur == month_val && year.max == year_val) ? day.max : days_in_month(month_val, year_val);

		year.set_options();
		day.set_options();
		month.set_options();

		year.msec = Date.UTC(year_val, month_val - 1, day_val);

		month.value = month_val;
		day.value = day_val;
		year.value = year_val;

		prev_year = year;
	}

	edited_date_input = date_ary.length - 1;

	if (Math.floor(date_ary.length / 2) < max_periods && date_ary.length > 1)
	{
		document.getElementById('cal_add').style.display = 'inline';
	}
}

function insert_new_period()
{
	var start_id = num_periods * 2;
	var end_id = start_id + 1;

	var prev_year_id = start_id - 1;
	var prev_year = document.getElementById('cy' + prev_year_id);

	var dl_element = document.getElementById('cdl0').cloneNode(false);
	dl_element.id = 'cdl' + num_periods;

	var dt_element = document.getElementById('cdt0').cloneNode(false);
	dt_element.id = 'cdt' + num_periods;

	var cfrom_element = document.getElementById('cfrom0').cloneNode(false);
	cfrom_element.id = 'cfrom' + num_periods;

	var n_period_string = num_periods + 1;
	n_period_string += (num_periods < 3) ? ((num_periods < 2) ? '{L_CALENDAR_SECOND}' : '{L_CALENDAR_THIRD}') : '{L_CALENDAR_FOURTH_PLUS}';
	n_period_string = '{L_CALENDAR_N_PERIOD_FROM}'.replace(/%s/, n_period_string);
	n_period_string += ':';

	var from_text_element = document.createTextNode(n_period_string);

	var dd_element = document.getElementById('cdd0').cloneNode(false);
	dd_element.id = 'cdd' + num_periods;

	var cm_start_element = document.getElementById('cm1').cloneNode(true);
	cm_start_element.id = 'cm' + start_id;

	var cd_start_element = document.getElementById('cd1').cloneNode(true);
	cd_start_element.id = 'cd' + start_id;

	var cy_start_element = document.getElementById('cy1').cloneNode(true);
	cy_start_element.id = 'cy' + start_id;

	var cto_element = document.getElementById('cto0').cloneNode(true);
	cto_element.id = 'cto' + num_periods;

	var cm_end_element = document.getElementById('cm1').cloneNode(true);
	cm_end_element.id = 'cm' + end_id;

	var cd_end_element = document.getElementById('cd1').cloneNode(true);
	cd_end_element.id = 'cd' + end_id;

	var cy_end_element = document.getElementById('cy1').cloneNode(true);
	cy_end_element.id = 'cy' + end_id;

	cfrom_element.appendChild(from_text_element);
	dt_element.appendChild(cfrom_element);
	dl_element.appendChild(dt_element);
	dd_element.appendChild(cm_start_element);
	dd_element.appendChild(document.createTextNode(' '));
	dd_element.appendChild(cd_start_element);
	dd_element.appendChild(document.createTextNode(' '));
	dd_element.appendChild(cy_start_element);
	dd_element.appendChild(document.createTextNode(' '));
	dd_element.appendChild(cto_element);
	dd_element.appendChild(document.createTextNode(' '));
	dd_element.appendChild(cm_end_element);
	dd_element.appendChild(document.createTextNode(' '));
	dd_element.appendChild(cd_end_element);
	dd_element.appendChild(document.createTextNode(' '));
	dd_element.appendChild(cy_end_element);
	dl_element.appendChild(dd_element);

	var month = cm_start_element;
	var day = cd_start_element;
	var year = cy_start_element;

	month.set_options = set_month_options;
	day.set_options = set_day_options;
	year.set_options = set_year_options;

	year.min_msec = prev_year.msec + ((min_gap + 1) * 86400000);
	year.max_msec = prev_year.msec + ((max_gap + 1) * 86400000);

	var min_date = new Date(year.min_msec);
	var max_date = new Date(year.max_msec);

	year.min = min_date.getUTCFullYear();
	year.max = max_date.getUTCFullYear();

	month.min = min_date.getUTCMonth() + 1;
	month.max = max_date.getUTCMonth() + 1;

	day.min = min_date.getUTCDate();
	day.max = max_date.getUTCDate();

	month.min_cur = 1;
	month.max_cur = 12;

	day.min_cur = 1;
	day.max_cur = 31;

	if (year.min == year.max)
	{
		month.max_cur = month.max;
		month.min_cur = month.min;

		if (month.min_cur == month.max_cur)
		{
			day.max_cur = day.max;
			day.min_cur = day.min;
		}
	}

	month.set_options();
	day.set_options();
	year.set_options();

	if (year.min == year.max)
	{
		year.value = year.min;

		if (month.min_cur == month.max_cur)
		{
			month.value = month.min_cur;
		}
	}

	month = cm_end_element;
	day = cd_end_element;
	year = cy_end_element;

	month.set_options = set_month_options;
	day.set_options = set_day_options;
	year.set_options = set_year_options;

	month.options.length = 1;
	day.options.length = 1;
	year.options.length = 1;

	document.getElementById('fields_post_edit').insertBefore(dl_element, document.getElementById('cal_add'));

	edited_date_input = num_periods * 2;

	num_periods++;
}

function is_leap_year(year)
{
	if (year % 1000 == 0)
	{
		return false;
	}

	if (year % 400 == 0)
	{
		return true;
	}

	if (year % 100 == 0)
	{
		return false;
	}

	if (year % 4 == 0)
	{
		return true;
	}

	return false;

}

function days_in_month(month_val, year_val)
{
	var num_month_days = new Array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

	if (month_val == 0)
	{
		return 31;
	}

	if (year_val == 0)
	{
		return num_month_days[month_val - 1];
	}
	else
	{
		if (month_val == 2)
		{
			if (is_leap_year(year_val))
			{
				return 29;
			}
			else
			{
				return 28;
			}
		}
		else
		{
			return num_month_days[month_val - 1];
		}
	}
}

function set_month_options()
{
	var selected_value = parseInt(this.value);

	this.options.length = 1;

	var i = 0;

	for (var val = this.min_cur; val <= this.max_cur; val++)
	{
		i++;
		this.options.add(new Option(month_option_ary[val], val), i);
	}

	if (selected_value != 0)
	{
		if (selected_value > this.max_cur)
		{
			this.value = this.max_cur;
		}
		else
		{
			if (selected_value < this.min_cur)
			{
				this.value = this.min_cur;
			}
			else
			{
				this.value = selected_value;
			}
		}
	}
}

function set_day_options()
{
	var selected_value = parseInt(this.value);

	this.options.length = 1;

	var i = 0;

	for (val = this.min_cur; val <= this.max_cur; val++)
	{
		i++;
		this.options.add(new Option(val, val), i);
	}

	if (selected_value != 0)
	{
		if (selected_value > this.max_cur)
		{
			this.value = this.max_cur;
		}
		else
		{
			if (selected_value < this.min_cur)
			{
				this.value = this.min_cur;
			}
			else
			{
				this.value = selected_value;
			}
		}
	}
}

function set_year_options()
{
	var selected_value = parseInt(this.value);

	this.options.length = 1;

	var i = 0;

	for (val = this.min; val <= this.max; val++)
	{
		i++;
		this.options.add(new Option(val, val), i);
	}

	if (selected_value != 0)
	{
		if (selected_value > this.max)
		{
			this.value = this.max;
		}
		else
		{
			if (selected_value < this.min)
			{
				this.value = this.min;
			}
			else
			{
				this.value = selected_value;
			}
		}
	}
}

function change_cal(select_element)
{
	var mdy_id = select_element.id.charAt(1);
	var num_id = parseInt(select_element.id.substr(2));

	var s_start = (num_id % 2 == 0) ? true : false;

	if (num_id < edited_date_input)
	{
		if (s_start)
		{
			var end_id = num_id + 1;

			var month = document.getElementById('cm' + end_id);
			var day = document.getElementById('cd' + end_id);
			var year = document.getElementById('cy' + end_id);

			month.options.length = 1;
			day.options.length = 1;
			year.options.length = 1;
		}

		var start_remove_period = Math.floor(num_id / 2) + 1;

		for (var i = start_remove_period; i < num_periods; i++)
		{
			var cdl_element = document.getElementById('cdl' + i);
			document.getElementById('fields_post_edit').removeChild(cdl_element);
		}

		num_periods = start_remove_period;
	}

	edited_date_input = num_id;

	month = document.getElementById('cm' + num_id);
	day = document.getElementById('cd' + num_id);
	year = document.getElementById('cy' + num_id);

	if (year.min == year.max)
	{
		month.min_cur = month.min;
		month.max_cur = month.max;
	}
	else
	{
		month.min_cur = (year.min == year.value) ? month.min : 1;
		month.max_cur = (year.max == year.value) ? month.max : 12;
	}

	month.set_options();

	if (year.min == year.max)
	{
		day.min_cur = (month.min_cur == month.value) ? day.min : 1;
		day.max_cur = (month.max_cur == month.value) ? day.max : days_in_month(month.value, year.value);
	}
	else
	{
		day.min_cur = (month.min_cur == month.value && year.min == year.value) ? day.min : 1;
		day.max_cur = (month.max_cur == month.value && year.max == year.value) ? day.max : days_in_month(month.value, year.value);
	}

	day.set_options();

	var date_complete = (month.value != 0 && day.value != 0 && year.value != 0) ? true : false;

	if (date_complete)
	{
		year.msec = Date.UTC(year.value, month.value - 1, day.value);

		if (s_start)
		{
			end_id = num_id + 1;

			var prev_month = month;
			var prev_day = day;
			var prev_year = year;

			month = document.getElementById('cm' + end_id);
			day = document.getElementById('cd' + end_id);
			year = document.getElementById('cy' + end_id);

			year.min_msec = prev_year.msec;
			year.max_msec = prev_year.msec + ((max_period_length - 1) * 86400000);

			var max_date = new Date(year.max_msec);

			year.min = prev_year.value;
			year.max = max_date.getUTCFullYear();

			month.min = prev_month.value;
			month.max = max_date.getUTCMonth() + 1;

			day.min = prev_day.value;
			day.max = max_date.getUTCDate();

			month.min_cur = month.min;
			month.max_cur = 12;

			day.min_cur = day.min;
			day.max_cur = 31;

			if (year.min == year.max)
			{
				month.max_cur = month.max;

				if (month.min_cur == month.max_cur)
				{
					day.max_cur = day.max;
				}
			}

			month.set_options();
			day.set_options();
			year.set_options();

			month.value = prev_month.value;
			day.value = prev_day.value;
			year.value = prev_year.value;

			year.msec = Date.UTC(year.value, month.value - 1, day.value);

			edited_date_input = num_id + 1;
		}

		if (Math.floor(num_id / 2) < (max_periods - 1))
		{
			document.getElementById('cal_add').style.display = "inline";
		}

	}
	else
	{
		year.msec = 0;
		document.getElementById('cal_add').style.display = 'none';
	}
}

function add_calendar_period()
{
	insert_new_period();
	document.getElementById('cal_add').style.display = 'none';
}
