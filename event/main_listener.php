<?php
/**
* phpBB Extension - marttiphpbb calendar
* @copyright (c) 2014 - 2017 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\calendar\event;

use phpbb\controller\helper;
use phpbb\template\template;
use phpbb\language\language;
use phpbb\event\data as event;

use marttiphpbb\calendar\render\links;

/**
* @ignore
*/
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class main_listener implements EventSubscriberInterface
{
	/* @var helper */
	protected $helper;

	/* @var php_ext */
	protected $php_ext;

	/* @var template */
	protected $template;

	/* @var language */
	protected $language;

	/* @var links */
	protected $links;

	/**
	* @param helper		$helper
	* @param string		$php_ext
	* @param template	$template
	* @param language	$language
	* @param links		$links
	*/
	public function __construct(
		helper $helper,
		string $php_ext,
		template $template,
		language $language,
		links $links
	)
	{
		$this->helper = $helper;
		$this->php_ext = $php_ext;
		$this->template = $template;
		$this->language = $language;
		$this->links = $links;
	}

	static public function getSubscribedEvents()
	{
		return [
			'core.user_setup'						=> 'core_user_setup',
			'core.page_header'						=> 'core_page_header',
			'core.viewonline_overwrite_location'	=> 'core_viewonline_overwrite_location',
		];
	}

	public function core_user_setup(event $event)
	{
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = [
			'ext_name' => 'marttiphpbb/calendar',
			'lang_set' => 'common',
		];
		$event['lang_set_ext'] = $lang_set_ext;
	}

	public function core_page_header(event $event)
	{
		$this->links->assign_template_vars();
		$this->template->assign_vars([
			'U_CALENDAR'			=> $this->helper->route('marttiphpbb_calendar_defaultview_controller'),
			'CALENDAR_EXTENSION'	=> $this->language->lang('CALENDAR_EXTENSION', '<a href="http://github.com/marttiphpbb/phpbb-ext-calendar">', '</a>'),
		]);
	}

	public function core_viewonline_overwrite_location(event $event)
	{
		if (strrpos($event['row']['session_page'], 'app.' . $this->php_ext . '/calendar') === 0)
		{
			$event['location'] = $this->language->lang('CALENDAR_VIEWING');
			$event['location_url'] = $this->helper->route('marttiphpbb_calendar_defaultview_controller');
		}
	}

}
