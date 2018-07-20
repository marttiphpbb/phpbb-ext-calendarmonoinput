<?php
/**
* phpBB Extension - marttiphpbb calendarinput
* @copyright (c) 2014 - 2018 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\calendarinput\event;

use phpbb\template\template;
use phpbb\language\language;
use phpbb\config\config;
use phpbb\event\data as event;
use marttiphpbb\calendarinput\render\links;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class main_listener implements EventSubscriberInterface
{
	protected $template;
	protected $language;
	protected $config;

	public function __construct(
		template $template,
		language $language,
		config $config
	)
	{
		$this->template = $template;
		$this->language = $language;
		$this->config = $config;
	}

	static public function getSubscribedEvents()
	{
		return [
			'core.user_setup'						=> 'core_user_setup',
			'marttiphpbb.topicsuffixtags.set_tags'	=> 'set_tags',
		];
	}

	public function set_tags(event $event)
	{
		$tags = $event['tags'];
		$tags[] = '[ oufti: ' . $event['topic_id'] . ' ' . $event['origin_event_name'] . ' ]';
 		$event['tags'] = $tags;
 	}

	public function core_user_setup(event $event)
	{
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = [
			'ext_name' => 'marttiphpbb/calendarinput',
			'lang_set' => 'common',
		];
		$event['lang_set_ext'] = $lang_set_ext;
	}
}
