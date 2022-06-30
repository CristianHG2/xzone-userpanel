<?

/* ---------------------------------------------
*  News management module
*  Easy news management class
*  ---------------------------------------------
*  Developed by Christian Herrera for Lawndale Roleplay
*  ah, and also the SA:MP community.
*
*  All rights (and lefts) reserved. 2014 (c) Studio Wolfree
*  SW Development tools
*/

/**
 * Easy news management class
 */

class Servquest {

	public function __construct()
	{
		global $servquest;

		if ( !isset($servquest) && is_array($servquest) )
		{
			echo "<b>[SERVQUEST]</b> The $servquest array has not been set, make sure $servquest is global and an array.";
		}
	}
}