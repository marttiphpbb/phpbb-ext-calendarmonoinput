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

/**
* @ignore
*/
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class main_listener implements EventSubscriberInterface
{
	/* @var template */
	private $template;

	/* @var language */
	private $language;

	/* @var config */
	private $config;

	/**
	* @param template	$template
	* @param language	$language
	* @param config		$config
	*/
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
			'core.page_header'						=> 'core_page_header',
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

	public function core_page_header(event $event)
	{	
		if ($this->config['marttiphpbb_calendarinput_repo_link'] === '1')
		{
			$this->template->assign_var('CALENDARINPUT_EXTENSION', $this->language->lang('CALENDARINPUT_EXTENSION', '<a href="http://github.com/marttiphpbb/phpbb-ext-calendarinput">', '</a>'));
		}
	}
}
