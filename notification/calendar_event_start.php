<?php

/**
* phpBB Extension - marttiphpbb calendar
* @copyright (c) 2014 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\calendar\notification;

use phpbb\notification\type\base;

use phpbb\user_loader;
use phpbb\db\driver\driver_interface as db;
use phpbb\cache\driver\driver_interface as cache;
use phpbb\user;
use phpbb\auth\auth;
use phpbb\config\config;
use phpbb\controller\helper;

class calendar_event_start extends base
{

	/**
	* Language key used to output the text
	*
	* @var string
	*/
	protected $language_key = 'NOTIFICATION_CALENDAR_EVENT_START';

	/**
	* Notification option data (for outputting to the user)
	*
	* @var bool|array False if the service should use it's default data
	* 					Array of data (including keys 'id', 'lang', and 'group')
	*/
	public static $notification_option = array(
		'lang'	=> 'NOTIFICATION_TYPE_CALENDAR_EVENT_START',
		'group'	=> 'NOTIFICATION_GROUP_MISCELLANEOUS',
	);

	/**
	* @param \phpbb\user_loader $user_loader
	* @param \phpbb\db\driver\driver_interface $db
	* @param \phpbb\cache\driver\driver_interface $cache
	* @param \phpbb\user $user
	* @param \phpbb\auth\auth $auth
	* @param \phpbb\config\config $config
	* @param \phpbb\controller\helper $helper
	* @param string $phpbb_root_path
	* @param string $php_ext
	* @param string $notification_types_table
	* @param string $notifications_table
	* @param string $user_notifications_table
	*/

	public function __construct(
		user_loader $user_loader,
		db $db,
		cache $cache,
		user $user,
		auth $auth,
		config $config,
		helper $helper,
		$phpbb_root_path,
		$php_ext,
		$notification_types_table,
		$notifications_table,
		$user_notifications_table
	)
	{
		$this->user_loader = $user_loader;
		$this->db = $db;
		$this->cache = $cache;
		$this->user = $user;
		$this->auth = $auth;
		$this->config = $config;
		$this->helper = $helper;

		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;

		$this->notification_types_table = $notification_types_table;
		$this->notifications_table = $notifications_table;
		$this->user_notifications_table = $user_notifications_table;

	}

	/**
	* @return string
	*/
	public function get_type()
	{
		return 'marttiphpbb.calendar.notification.type.calendar_event_start';
	}

	/**
	* Is this type available to the current user (defines whether or not it will be shown in the UCP Edit notification options)
	*
	* @return bool True/False whether or not this is available to the user
	*/
	public function is_available()
	{
		return true;
	}

	/**
	* Get the id of the notification
	*
	* @param array $data
	* @return int Id of the notification
	*/
	public static function get_item_id($data)
	{
		return $data['calendar_event_id'];
	}

	/**
	* Get the id of the parent
	*
	* @param array $data
	* @return int Id of the parent
	*/
	public static function get_item_parent_id($data)
	{
		return $data['calendar_topic_id'];
	}

	/**
	* Find the users who will receive notifications
	*
	* @param array $data The type specific data for the calendar event
	* @param array $options Options for finding users for notification
	* @return array
	*/
	public function find_users_for_notification($data, $options = array())
	{
		return $this->check_user_notification_options($users, $options);
	}

	/**
	* Users needed to query before this notification can be displayed
	*
	* @return array Array of user_ids
	*/
	public function users_to_query()
	{
		return array();
	}

	/**
	* Get the HTML formatted title of this notification
	*
	* @return string
	*/
	public function get_title()
	{
		return $this->user->lang('CALENDAR_EVENT_NOTIFICATION');
	}

	/**
	* Get the url to this item
	*
	* @return string URL
	*/
	public function get_url()
	{
		$rule_id = ($this->get_data('rule_id')) ? array('#' => $this->get_data('rule_id')) : array();

		return $this->helper->route('phpbb_boardrules_main_controller', $rule_id);
	}

	/**
	* Get email template
	*
	* @return string|bool
	*/
	public function get_email_template()
	{
		return false;
	}

	/**
	* Get email template variables
	*
	* @return array
	*/
	public function get_email_template_variables()
	{
		return array();
	}

	/**
	* Function for preparing the data for insertion in an SQL query
	* (The service handles insertion)
	*
	* @param array $data The data for the updated rules
	* @param array $pre_create_data Data from pre_create_insert_array()
	*
	* @return array Array of data ready to be inserted into the database
	*/
	public function create_insert_array($data, $pre_create_data = array())
	{
		$this->set_data('rule_id', $data['rule_id']);

		return parent::create_insert_array($data, $pre_create_data);
	}

}
