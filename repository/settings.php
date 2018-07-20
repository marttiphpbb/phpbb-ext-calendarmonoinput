<?php
/**
* phpBB Extension - marttiphpbb calendarinput
* @copyright (c) 2014 - 2018 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\calendarinput\repository;

use phpbb\config\db_text as config_text;

class settings
{
	protected $config_text;
	protected $local_cache;

	public function __construct(
		config_text $config_text
	)
	{
		$this->config_text = $config_text;
	}

	private function get_all():array
	{
		if (isset($this->local_cache) && is_array($this->local_cache))
		{
			return $this->local_cache;
		}

		$this->local_cache = unserialize($this->config_text->get('marttiphpbb_calendarinput_settings'));

		return $this->local_cache;
	}

	private function set(array $ary)
	{
		if ($ary === $this->local_cache)
		{
			return;
		}
		$this->local_cache = $ary;
		$this->config_text->set('marttiphpbb_calendarinput_settings', serialize($ary));
	}

	private function get_asset_enabled(string $name):bool
	{
		return $this->get_all()['include_assets'][$name] ?? false;
	}

	private function set_asset_enabled(string $name, bool $enabled)
	{
		$ary = $this->get_all();
		$ary['include_assets'][$name] = $enabled;
		$this->set($ary);
	}

	private function set_string(string $name, string $value)
	{
		$ary = $this->get_all();
		$ary[$name] = $value;
		$this->set($ary);
	}

	private function get_string(string $name):string
	{
		return $this->get_all()[$name];
	}

	private function set_int(string $name, int $value)
	{
		$ary = $this->get_all();
		$ary[$name] = $value;
		$this->set($ary);
	}

	private function get_int(string $name):int
	{
		return $this->get_all()[$name];
	}

	private function set_forum_boolean(int $forum_id, string $name, bool $bool)
	{
		$ary = $this->get_all();
		if ($bool)
		{
			$ary['forums'][$forum_id][$name] = true;
		}
		else
		{
			unset($ary['forums'][$forum_id][$name]);
		}
		$this->set($ary);
	}

	private function get_forum_boolean(int $forum_id, string $name):bool
	{
		return $this->get_all()['forums'][$forum_id][$name] ?? false;
	}

	public function set_include_jquery_ui_datepicker(bool $enable)
	{
		$this->set_asset_enabled('jquery_ui_datepicker', $enable);
	}

	public function get_include_jquery_ui_datepicker():bool
	{
		return $this->get_asset_enabled('jquery_ui_datepicker');
	}

	public function set_include_jquery_ui_datepicker_i18n(bool $enable)
	{
		$this->set_asset_enabled('jquery_ui_datepicker_i18n', $enable);
	}

	public function get_include_jquery_ui_datepicker_i18n():bool
	{
		return $this->get_asset_enabled('jquery_ui_datepicker_i18n');
	}

	public function set_datepicker_theme(string $value)
	{
		$this->set_string('datepicker_theme', $value);
	}

	public function get_datepicker_theme():string
	{
		return $this->get_string('datepicker_theme');
	}

	public function get_lower_limit_days():int
	{
		return $this->get_int('lower_limit_days');
	}

	public function set_lower_limit_days(int $days)
	{
		$this->set_int('lower_limit_days', $days);
	}

	public function get_upper_limit_days():int
	{
		return $this->get_int('upper_limit_days');
	}

	public function set_upper_limit_days(int $days)
	{
		$this->set_int('upper_limit_days', $days);
	}

	public function get_min_duration_days():int
	{
		return $this->get_int('min_duration_days');
	}

	public function set_min_duration_days(int $days)
	{
		$this->set_int('min_duration_days', $days);
	}

	public function get_max_duration_days():int
	{
		return $this->get_int('max_duration_days');
	}

	public function set_max_duration_days(int $days)
	{
		$this->set_int('max_duration_days', $days);
	}

	public function get_required(int $forum_id):bool
	{
		return $this->get_forum_boolean($forum_id, 'required');
	}

	public function set_required(int $forum_id, bool $required)
	{
		$this->set_forum_boolean($forum_id, 'required', $required);
	}

	public function get_enabled(int $forum_id):bool
	{
		return $this->get_forum_boolean($forum_id, 'enabled');
	}

	public function set_enabled(int $forum_id, bool $enabled)
	{
		$this->set_forum_boolean($forum_id, 'enabled', $enabled);
	}
}
