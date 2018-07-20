<?php
/**
* phpBB Extension - marttiphpbb calendarinput
* @copyright (c) 2014 - 2018 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\calendarinput\service;

use phpbb\template\template;
use phpbb\language\language;
use marttiphpbb\calendarinput\service\store;
use phpbb\extension\manager;
use marttiphpbb\calendarinput\util\cnst;
use marttiphpbb\calendarmono\util\cnst as mono_cnst;
use Symfony\Component\DependencyInjection\ContainerInterface;


class posting
{
	protected $container;
	protected $store;
	protected $template;
	protected $language;
	protected $ext_manager;

	public function __construct(
		ContainerInterface $container,
		store $store,
		template $template,
		language $language,
		manager $ext_manager
	)
	{
		$this->container = $container;
		$this->store = $store;
		$this->template = $template;
		$this->language = $language;
		$this->ext_manager = $ext_manager;
	}

	public function get_mono_enabled()
	{
		return $this->ext_manager->is_enabled('marttiphpbb/calendarmono');
	}

	public function get_datepicker_enabled()
	{
		return $this->ext_manager->is_enabled('marttiphpbb/jqueryuidatepicker');
	}

	public function assign_template_vars(int $forum_id, array $post_data)
	{
		$enabled = $this->store->get_enabled($forum_id);

		if (!$enabled)
		{
			return;
		}

		if (!$this->get_mono_enabled())
		{
			return;
		}

		if (!$this->get_datepicker_enabled())
		{
			return;
		}

		$listener = $this->container->get('marttiphpbb.jqueryuidatepicker.listener');
		$listener->enable();

		$data = [
			'min_limit'		=> $this->store->get_lower_limit_days(),
			'max_limit'		=> $this->store->get_upper_limit_days(),
			'min_duration'	=> $this->store->get_min_duration_days(),
			'max_duration'	=> $this->store->get_max_duration_days(),
		];

		$this->template->assign_vars([
			'S_MARTTIPHPBB_CALENDARINPUT_BEFORE'	=> $this->store->get_placement_before(),
			'S_MARTTIPHPBB_CALENDARINPUT_AFTER'		=> !$this->store->get_placement_before(),
			'S_MARTTIPHPBB_CALENDARINPUT_REQUIRED'	=> $this->store->get_required($forum_id),
			'S_MARTTIPHPBB_CALENDARINPUT_END'		=> true,
			'MARTTIPHPBB_CALENDARINPUT_DATE_FORMAT'	=> 'yyyy-mm-dd',
			'MARTTIPHPBB_CALENDARINPUT_DATE_START'	=> isset($post_data['topic_calendarinput_start']) ? gmdate('Y-m-d', $post_data['topic_calendarinput_start']) : '',
			'MARTTIPHPBB_CALENDARINPUT_DATE_END'	=> isset($post_data['topic_calendarinput_end']) ? gmdate('Y-m-d', $post_data['topic_calendarinput_end']) : '',
			'MARTTIPHPBB_CALENDARINPUT_DATA'		=> htmlspecialchars(json_encode($data), ENT_QUOTES, 'UTF-8'),
		]);
	}
}
