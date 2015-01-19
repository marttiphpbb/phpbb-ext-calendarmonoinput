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

/*
 * derives a timeformat from the user's dateformat.
<<<<<<< HEAD
<<<<<<< HEAD
 */

class timeformat
{

	/*
	 * @var string
	 */

	protected $format;

	const DEFAULT_FORMAT = 'g:i a';

	protected static $format_candidates = array(
		'H:i', 'H\hi', 'g:i a', 'g\hi a',
	);

	/*
	 * @param user $user
	 */
=======
=======
>>>>>>> 8687857353fbed65fbb9e283a9ed0ee69023d194
 */ 

class timeformat
{	
	
	/*
	 * @var string
	 */
	 
	protected $format;
	
	const DEFAULT_FORMAT = 'g:i a';
	
	protected static $format_candidates = array(
		'H:i', 'H\hi', 'g:i a', 'g\hi a',
	);
	
	/*
	 * @param user $user
	 */ 
<<<<<<< HEAD
>>>>>>> 8687857353fbed65fbb9e283a9ed0ee69023d194
=======
>>>>>>> 8687857353fbed65fbb9e283a9ed0ee69023d194
	public function __construct(
		user $user
	)
	{
		$this->user = $user;
<<<<<<< HEAD
<<<<<<< HEAD

		$dateformat = $user->data['user_dateformat'];

		$this->format = timeformat::DEFAULT_FORMAT;

=======
=======
>>>>>>> 8687857353fbed65fbb9e283a9ed0ee69023d194
		
		$dateformat = $user->data['user_dateformat'];
		
		$this->format = timeformat::DEFAULT_FORMAT;
		
<<<<<<< HEAD
>>>>>>> 8687857353fbed65fbb9e283a9ed0ee69023d194
=======
>>>>>>> 8687857353fbed65fbb9e283a9ed0ee69023d194
		foreach (timeformat::$format_candidates as $format)
		{
			if (strpos($dateformat, $format) === 0 || strpos($dateformat, ' ' . $format))
			{
				$this->format = $format;
				break;
			}
		}
	}
<<<<<<< HEAD
<<<<<<< HEAD

=======
	
>>>>>>> 8687857353fbed65fbb9e283a9ed0ee69023d194
=======
	
>>>>>>> 8687857353fbed65fbb9e283a9ed0ee69023d194
	/*
	 * @return string
	 */

	public function __toString()
	{
		return $this->format;
	}
}
