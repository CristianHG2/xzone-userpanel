<?

/* Framework initialization */

session_start();

require 'config.php';
global $config, $page;

//error_reporting('E_NONE');

/* Include modules */

require $config['context']['path'].'inc/init2data.php';
require $config['context']['path'].'inc/sql.php';
require $config['context']['path'].'inc/news.php';
require $config['context']['path'].'inc/accounts.php';
require $config['context']['path'].'inc/accsql.php';
require $config['context']['path'].'inc/uploadmgt.php';
require $config['context']['path'].'inc/keychain.php';

/* Create globals and constants */

define("KILO", 1024);
define("MEGA", KILO * 1024);
define("GIGA", MEGA * 1024);
define("TERA", GIGA * 1024);


/* Module initialization */

/*@@@@@@@@@@@@@ ONLY UNCOMMENT THE LINE BELOW IF NEED TO CHECK FOR MODULE FUNCTIONALITY
				THIS FUNCTION WILL DECREASE THE SITE EFFICIENCY SIGNIFICANTLY! @@@@@@@@@@@@@@*/

//Main::init();

/*@@@@@@@@@@@@@				Source indexation				@@@@@@@@@@@@@@
 		DO NOT DELETE THE PAGE FUNCTION. ONLY MODIFY IF YOU HAVE THE
							REQUIRED KNOWLEDGE. 
*/

if ( $config['context']['pageindex'] == 'flat' )
{
	function page($pagein, $pagenam, $ext = 'php', $head = true, $header = true, $footer = true)
	{
		global $config;

		if ( !isset($pagein) || empty($pagein) )
			$error[] = "<b>[CORE]</b> The first parameter in the page() declaration is not set (page index).<br><br>";

		if ( !isset($pagenam) || empty($pagenam) )
			$error[] = "<b>[CORE]</b> The second parameter in the page() declaration has not been set (page name).<br><br>";
		
		if ( !file_exists($config['context']['path'].'Sources/'.$pagein.'.'.$ext) )
			$error[] = "<b>[CORE]</b> The page requested does not exist, please check your Sources folder. (first parameter).<br><br>";
		
		if ( !isset($error) )
		{
			$config['context']['pagename'] = $pagenam;

			if ( $head )
				include($config['context']['path'].'Sources/headers/head.php');

			if ( $header )
				include($config['context']['path'].'Sources/headers/header.php');

			include($config['context']['path'].'Sources/'.$pagein.'.'.$ext);

			if ( $footer )
				include($config['context']['path'].'Sources/headers/footer.php');
		}
		else
		{
			foreach ( $error as $i )
			{
				echo $i."<br>";
			}
		}
	}
}
elseif ( $config['context']['pageindex'] == 'sql')
{

}

/** Main class, not actually main, but it's used for a lot of stuff **/
class Main {

	public function connectFTP($ftp)
	{
		$context = stream_context_create(array(

		                'ftp'=>array(

		                        'overwrite' => true

		                )

		        )

		);

		$con = fopen($ftp, "w", false, $context);

		return $con;
	}

	function getLineString($fileName, $str) 
	{
		$file = file($fileName);
		$key = $str;
		$found = false;

		foreach ($file as $lineNumber => $line) {
		    if (strpos($line, $key) !== false) {
		       $found = true;
		       $lineNumber++;
		       break;
		    }
		}

		if ($found) {
		   return $lineNumber;
		}
		else
		{
			return 0;
		}
	}
	
	function includeSource($source, $format = 'php')
	{
		require 'config.php';
		global $config;
		
		include("./Sources/".$source.".".$format);
	}
	
	static function alert($alert)
	{
	//	echo "<div class="overlay">";
		//echo "
	}
	
	static function init()
	{
		$sql = new SqlMgt;
		$new = new News;
		$acc = new UserMgt('sql');
		$ini = new IniDat('init.ini', time());
		
		$v = array();
		$v[] = $sql->init();
		$v[] = $new->init();
		$v[] = $acc->init();
		$v[] = $ini->init();
		
		$t = implode(".", $v);

		if ( $t == "1.1.1.1" )
			return true;
		else
			return $t;
	}

	/* Filter XSS */
	static function fxs($string)
	{
		$good = htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
		return $good;
	}

	static function formatDate($unix)
	{
		$date = date("F j, Y, g:i a", $unix);
		return $date;
	}

	static function validate($string, $type = 'alnum')
	{
		switch ($type) {
			case 'alnum':
				if ( ctype_alnum($string) )
					return true;
				else
					return false;
				break;

			case 'num':
				if ( ctype_digit($string) )
					return true;
				else
					return false;
				break;
			
			case 'alpha':
				if ( ctype_alpha($string) )
					return true;
				else
					return false;
				break;

			case 'space':
				if ( strpos($string, " ") )
					return false;
				else
					return true;
				break;

			default:
				return true;
				break;
		}
	}

	static function rToken()
	{
		$token = md5(base64_encode(uniqid(mt_rand(), true)));

		return $token;
	}

	public function _COOKIE($name, $value, $time = 'inf')
	{
		if ($time == 'inf')
			$times = (10 * 365 * 24 * 60 * 60);
		else
			$times = $time;

		setcookie($name, $value, time() + $times);
	}

	public function _DELCOOKIE($name)
	{
		setcookie($name, "", time() - 3600);
	}

	public function log($text, $type = 'GEN')
	{
		if ( file_put_contents('log.txt', $text."\n", FILE_APPEND) )
			return true;
		else
			return false;
	}

	public function deleteChar($text, $char = '_')
	{
		$explode = explode($text, $char);
		$complete = "$explode[0] $explode[1]";

		return $complete;
	}
}