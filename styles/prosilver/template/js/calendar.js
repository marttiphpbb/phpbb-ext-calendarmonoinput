var mouseover_evt_id_ary = [{SCRIPT_MSVR_EVT_ID_ARY}];
var mouseover_color = '#330022';
var mouseout_color_ary = new Array();
var mouseover_ready = false;

function navigate_to(mid_jd)
{
	document.getElementById('mid_jd').value = mid_jd;
	document.forms['postform'].submit();
}

function mouseover_evt(el)
{
	if (!mouseover_ready)
	{
		return;
	}

	for (var i = 0; i < el.ary.length; i++)
	{
		el.ary[i].className = 'evt-hover';
		el.ary[i].style.backgroundColor = mouseover_color;
	}
}

function clear()
{
	if (this.parent.active_el !== null)
	{
		var left = this.parent.active_el.left.index;
		var right = this.parent.active_el.right.index;

		if (this.left.index > left || this.left.index < right)
		{
			this.left.className = this.left.classbackup;

		}

		if (this.right.index > left || this.right.index < right)
		{
			this.right.className = this.right.classbackup;
		}

		for (var i = 0; i < this.mid_ary.length; i++)
		{
			if (this.mid_ary[i].index > left || this.mid_ary[i].index < right)
			{
				this.mid_ary[i].className = this.mid_ary[i].classbackup;
			}
		}
	}
	else
	{
		this.left.className = this.left.classbackup;
		this.right.className = this.right.classbackup;

		for (var i = 0; i < this.mid_ary.length; i++)
		{
			this.mid_ary[i].className = this.mid_ary[i].classbackup;
		}
	}
}

function on_load_calendarinput()
{
	for (var i = 0; i < mouseover_evt_id_ary.length; i++)
	{
		var id_ary = mouseover_evt_id_ary[i];
		var el_ary = new Array();

		for (var j = 0; j < id_ary.length; j++)
		{
			var el = document.getElementById(mouseover_evt_id_ary[i][j]);
			el_ary.push(el);
			el.mouseout_color = el.style.backgroundColor;
			el.ary = el_ary;
		}
	}

	mouseover_ready = true;

	new navi('a');
	new navi('b');
}

onload_functions.push('on_load_calendarinput()');
