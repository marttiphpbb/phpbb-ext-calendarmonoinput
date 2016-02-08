<?php
/**
* phpBB Extension - marttiphpbb calendar
* @copyright (c) 2014 - 2016 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\calendar\event;

use phpbb\auth\auth;
use phpbb\config\config;
use phpbb\controller\helper;
use phpbb\template\template;
use phpbb\user;

use marttiphpbb\calendar\model\links;

/**
* @ignore
*/
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class main_listener implements EventSubscriberInterface
{

	/* @var auth */
	protected $auth;

	/* @var config */
	protected $config;

	/* @var helper */
	protected $helper;

	/* @var php_ext */
	protected $php_ext;

	/* @var template */
	protected $template;

	/* @var user */
	protected $user;

	/* @var links */
	protected $links;

	/**
	* @param auth		$auth
	* @param config		$config
	* @param helper		$helper
	* @param string		$php_ext
	* @param template	$template
	* @param user		$user
	* @param links		$links
	*/
	public function __construct(
		auth $auth,
		config $config,
		helper $helper,
		$php_ext,
		template $template,
		user $user,
		links $links
	)
	{
		$this->auth = $auth;
		$this->config = $config;
		$this->helper = $helper;
		$this->php_ext = $php_ext;
		$this->template = $template;
		$this->user = $user;
		$this->links = $links;
	}

	static public function getSubscribedEvents()
	{
		return array(
			'core.user_setup'						=> 'core_user_setup',
			'core.page_header'						=> 'core_page_header',
			'core.viewonline_overwrite_location'	=> 'core_viewonline_overwrite_location',
		);
	}

	public function core_user_setup($event)
	{
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = array(
			'ext_name' => 'marttiphpbb/calendar',
			'lang_set' => 'common',
		);
		$event['lang_set_ext'] = $lang_set_ext;
	}

	public function core_page_header($event)
	{
		$this->links->assign_template_vars();
		$this->template->assign_vars(array(
			'U_CALENDAR'				=> $this->helper->route('marttiphpbb_calendar_defaultview_controller'),
			'CALENDAR_EXTENSION'		=> sprintf($this->user->lang['CALENDAR_EXTENSION'], '<a href="http://github.com/marttiphpbb/phpbb-ext-calendar">', '</a>'),
			'CALENDAR_DATEPICKER_THEME'	=> $this->config['calendar_datepicker_theme'],
		));
	}

	public function core_viewonline_overwrite_location($event)
	{
		if (strrpos($event['row']['session_page'], 'app.' . $this->php_ext . '/calendar') === 0)
		{
			$event['location'] = $this->user->lang('CALENDAR_VIEWING');
			$event['location_url'] = $this->helper->route('marttiphpbb_calendar_defaultview_controller');
		}
	}

}
