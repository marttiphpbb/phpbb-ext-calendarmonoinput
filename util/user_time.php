<?php
/**
<<<<<<< HEAD
<<<<<<< HEAD
* phpBB Extension - marttiphpbb calendar
* @copyright (c) 2014 marttiphpbb <info@martti.be>
* @license GNU General Public License, version 2 (GPL-2.0)
=======
* @package phpBB Extension - marttiphpbb calendar
* @copyright (c) 2014 marttiphpbb <info@martti.be>
* @license http://opensource.org/licenses/MIT
>>>>>>> 8687857353fbed65fbb9e283a9ed0ee69023d194
=======
* @package phpBB Extension - marttiphpbb calendar
* @copyright (c) 2014 marttiphpbb <info@martti.be>
* @license http://opensource.org/licenses/MIT
>>>>>>> 8687857353fbed65fbb9e283a9ed0ee69023d194
*/

namespace marttiphpbb\calendar\util;

use phpbb\user;

<<<<<<< HEAD
<<<<<<< HEAD
=======

>>>>>>> 8687857353fbed65fbb9e283a9ed0ee69023d194
=======

>>>>>>> 8687857353fbed65fbb9e283a9ed0ee69023d194
class user_time
{
	/* @var user */
	protected $user;
<<<<<<< HEAD
<<<<<<< HEAD

	/* @var string */
	protected $format;

	/**
	* @param user   $user
	*/

	public function __construct(
		user $user

	)
	{
		$this->user = $user;

=======
=======
>>>>>>> 8687857353fbed65fbb9e283a9ed0ee69023d194
	
	/* @var string */
	protected $format;
	

	/**
	* @param user   $user 
	*/	

	public function __construct(
		user $user
	
	)
	{
		$this->user = $user;
			
<<<<<<< HEAD
>>>>>>> 8687857353fbed65fbb9e283a9ed0ee69023d194
=======
>>>>>>> 8687857353fbed65fbb9e283a9ed0ee69023d194
		// 12h or 24h timeformat
		$dateformat = $this->user->data['user_dateformat'];
		$am_pm = '';
		$g = is_int(strpos($dateformat, 'g'));
<<<<<<< HEAD
<<<<<<< HEAD

=======
		
>>>>>>> 8687857353fbed65fbb9e283a9ed0ee69023d194
=======
		
>>>>>>> 8687857353fbed65fbb9e283a9ed0ee69023d194
		if ($g || is_int(strpos($dateformat, 'h')))
		{
			$hours = ($g) ? 'g' : 'h';
			$am_pm = (is_int(strpos($dateformat, 'A'))) ? ' A' : ' a';
		}
		else
		{
			$hours = (is_int(strpos($dateformat, 'G'))) ? 'G' : 'H';
		}
<<<<<<< HEAD
<<<<<<< HEAD

		$this->format = $hours . ':i' . $am_pm;
	}

	/*
	* @return 	string
	*/
	public function getFormat()
=======
=======
>>>>>>> 8687857353fbed65fbb9e283a9ed0ee69023d194
		
		$this->format = $hours . ':i' . $am_pm;		
	}
	
	/*
	* @return 	string
	*/
	public function getFormat()  
<<<<<<< HEAD
>>>>>>> 8687857353fbed65fbb9e283a9ed0ee69023d194
=======
>>>>>>> 8687857353fbed65fbb9e283a9ed0ee69023d194
	{
		return $this->format;
	}
}
