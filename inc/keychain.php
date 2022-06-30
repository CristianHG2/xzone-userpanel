<?

/* ---------------------------------------------
*  Keychain module
*  Ambassador for KeyChain
*  ---------------------------------------------
*  Developed by Christian Herrera for Lawndale Roleplay
*  ah, and also the SA:MP community.
*
*  All rights (and lefts) reserved. 2014 (c) Studio Wolfree
*  SW Development tools
*/

/**
 * Keychain development
 */

class KeyChain {

	public function __construct($table = 'keychain')
	{
		global $sql;
		$query = $sql->query('SHOW TABLES LIKE \'keychain\';', false, true);
		$reg = $query->fetch_array(MYSQLI_NUM);
		$conn = $sql->init();

		if ( count($reg) > 0 )
		{
			$num = true;
		}
		else
		{
			$num = false;
		}

		if ( $conn )
			$txt = 'Connection to MySQL server succcessful.<br>Connecting to database...<br>SUCCESS.<br>Selecting requested table<br>ERROR: TABLE <b>\''.$table.'\'</b> NOT FOUND.';
		else
			$txt = 'Error connecting to MySQL. Aborting.';

		if ( !$num )
		{
			echo "<b>[KEYCHAIN]</b> Initialization failed. Log (NOT COMPUTER-MADE): <br>
			<pre>$txt <br><b>Please check your settings</b></pre><br>
			";
		}
	}

	public function AddKey($string, $desc)
	{
		global $sql;

		$code = base64_encode(base64_encode($string));

		$step3 = $this->Wolfcrypt($desc);

		/* Final step */ $sql->insertData('keychain', array(
				"key" => $code,
				"desckey" => $step3
		));
	}

	public function retrieveKey($desckey)
	{
		global $sql;

		$encript = $this->Wolfcrypt($desckey);
		$decrypt = $this->Wolfdecrypt($encript);

		echo $decrypt;
	}

	/** Include Pre-crypted (md5) string in $string **/
	public function Wolfcrypt($string)
	{
		$step1 = $string;
		$step2 = base64_encode($step1+'0243be80');
		$step3 = base64_encode($step2);

		return $step3;
	}

	public function Wolfdecrypt($string)
	{
		$step1 = base64_decode($string);
		$step2 = base64_decode($step1);

		return $step2;
	}

	public function lettercrypt($string)
	{
		$healthy = range('A','Z');
		$yummy	= array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26);

		$newphrase = str_replace($healthy, $yummy, $phrase);

		return $newphrase;
	}
}