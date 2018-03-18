<?php
/**
* phpBB Extension - marttiphpbb calendarinput
* @copyright (c) 2014 - 2018 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
*/

namespace marttiphpbb\calendarinput\event;

use phpbb\template\template;
use marttiphpbb\calendarinput\render\tag;
use phpbb\event\data as event;

/**
* @ignore
*/
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class tag_listener implements EventSubscriberInterface
{
	/* @var template */
	protected $template;

	/* @var tag */
	protected $tag;

	/**
	* @param template	$template
	* @param tag 		$tag
	*/
	public function __construct(template $template, tag $tag)
	{
		$this->template = $template;
		$this->tag = $tag;
	}

	static public function getSubscribedEvents()
	{
		return [
			'core.viewtopic_assign_template_vars_before'
					=> 'core_viewtopic_assign_template_vars_before',
			'core.viewforum_modify_topicrow'
					=> 'core_viewforum_modify_topicrow',
			'core.mcp_view_forum_modify_topicrow'
					=> 'core_mcp_view_forum_modify_topicrow',
			'core.search_modify_tpl_ary'
					=> 'core_search_modify_tpl_ary',
			'core.posting_modify_template_vars'
					=> 'core_posting_modify_template_vars',
		];
	}

	public function core_posting_modify_template_vars(event $event)
	{
		$event['page_data'] = array_merge($event['page_data'], $this->tag->get($event['post_data']));
	}

	public function core_search_modify_tpl_ary(event $event)
	{
		if ($event['show_results'] === 'topics')
		{
			$event['tpl_ary'] = array_merge($event['tpl_ary'], $this->tag->get($event['row']));
		}
	}

	public function core_viewforum_modify_topicrow(event $event)
	{
		$event['topic_row'] = array_merge($event['topic_row'], $this->tag->get($event['row']));		
	}

	public function core_mcp_view_forum_modify_topicrow(event $event)
	{
		$event['topic_row'] = array_merge($event['topic_row'], $this->tag->get($event['row']));		
	}

	public function core_viewtopic_assign_template_vars_before(event $event)
	{
		$this->template->assign_vars($this->tag->get($event['topic_data']));
	}
}
