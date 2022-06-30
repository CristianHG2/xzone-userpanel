<?

/* ---------------------------------------------
*  User management module
*  Quick remote FTP user management
*  ---------------------------------------------
*  Developed by Christian Herrera for Lawndale Roleplay
*  ah, and also the SA:MP community.
*
*  All rights (and lefts) reserved. 2014 (c) Studio Wolfree
*  SW Development tools
*/

/**
 * Quick remote FTP user management
 */

class UserMgt {

	public function __construct($files = true, $space = " = ", $db = null)
	{
		$m = new Main;
		global $config;

		$this->userpath = $config['context']['usercdn'];
		$this->m = $files;
		$this->sps = $space;

		if ( !isset($_COOKIE['usrValidtrue.bool']) )
		{
			$this->init();

			$m->_COOKIE('usrValidtrue.bool', uniqid());
		}
	}

	public function init()
	{
		global $config;

		if ( $this->m == true )
		{
			if ( $config['context']['sitever'] == 'indev')
			{
				return true;
			}
			else
			{
				$ch = curl_init($this->userpath);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER,  true);
				curl_exec($ch);
				
				if (curl_error($ch))
					echo '<br><b>[UserMgt]</b> The FTP server returned the following error: "'.curl_error($ch).'"';
				else
					return true;
			}
		}
		elseif ( $this->m == "sql")
		{
			$s = new SqlMgt;
			$test1 = $s->init();
			
			if (!$test1)
				echo '<br><b>[UserMgt]</b> Could not create the bridge, SQLMGT returned the following error: "'.$error.'"';
			else
			{
				if (!$s->tableExists('users'))
					echo '<br><b>[UserMgt]</b> The \'USERS\' table does not exist.';
			}
		}
	}

	public function necessarykeys($user, $create = true)
	{
		$userpath = $this->userpath;
		$dat = new IniDat($userpath.$user.".ini", $this->sps);

		if ( !$dat->KeyExists('lastloginweb') )
			$llw = false;
		else
			$llw =  true;

		if ( !$dat->KeyExists('loginID') )
			$lid = false;
		else
			$lid = true;

		if ( !$dat->KeyExists('usrForum') )
			$usf = false;
		else
			$usf = true;

		if ( !$dat->KeyExists('isNew') )
			$ver = false;
		else
			$ver = true;

		if ( !$dat->KeyExists('active') )
			$acv = false;
		else
			$acv = true;

		if ( !$dat->KeyExists('username') )
			$acv2 = false;
		else
			$acv2 = true;

		if ( $llw && $lid && $usf && $ver && $acv && $acv2 )
			return true;
		elseif ( !$llw OR !$lid OR !$usf OR !$ver OR !$acv OR !$acv2 AND $create )
		{
			if ( !$llw )
				$dat->addLine('loginID', 'null');

			if ( !$lid )
				$dat->addLine('lastloginweb', 'null');

			if ( !$usf )
				$dat->addLine('usrForum', 'null');

			if ( !$ver )
				$dat->addLine('isNew', '1');

			if ( !$acv )
				$dat->addLine('active', '1');

			if ( !$acv2 )
				$dat->username('username', $user);
		}
		else
			return false;
	}

	/* 
		SQL: $USER = USERID
		FILES: $USER = USERNAME
	*/
	public function editUser($user, $row, $replace)
	{
		$userpath = $this->userpath;
		$u = new UserSQL;
		$useracs = new IniDat($userpath.$user.".ini", $this->sps);

		if ( $this->m == true )
		{
			$userpath = $this->userpath;
			
			$useracs->editLine($row, $replace);
		}
		elseif ( $this->m == "sql" )
		{
			$u->editUserInfo($user, $row, $replace);
		}
		else
		{
			return "[UserMgt] Method not valid, please choose the FILES or SQL method.";
		}
	}
	
	/* 
		SQL: $USER = USERID
		FILES: $USER = USERNAME
	*/	
	public function userExists($user)
	{
		$userpath = $this->userpath;
		$u = new UserSQL;

		if ( $this->m == true )
		{
			$userpath = $this->userpath;

			if ( file_exists($userpath.$user.".ini") )
				return true;
			else
				return false;
		}
		elseif ( $this->m == "sql" )
		{
			$a = $u->userExists($user);
			return $a;
		}
		else
		{
			return "[UserMgt] Method not valid, please choose the FILES or SQL method.";
		}
	}

	/* 
		SQL: $USER = USERID
		FILES: $USER = USERNAME
	*/
	public function logIn($user, $cookie = true)
	{
		$userpath = $this->userpath;
		$m = new Main;
		$s = new SqlMgt;
		$useracs = new IniDat($userpath.$user.".ini", $this->sps);

		if ($this->m == true)
		{
			$token = $m->rToken();

			if ($useracs->KeyExists('loginID'))
			{
				$m->_COOKIE('logID', $token, $cookie);
				$this->editUser($user, 'loginID', $token);
				$_SESSION['loginid'] = $user;
				$this->editUser($user, 'lastloginweb', time());
			}
			else
			{
				$this->necessarykeys($user);

				$m->_COOKIE('logID', $token, $cookie);
				$this->editUser($user, 'loginID', $token);
				$_SESSION['loginid'] = $user;
				$this->editUser($user, 'lastloginweb', time());
			}
		}
		else
		{
			$s->logIn($user, $cookie);
		}
	}

	public function logOut()
	{
		unset($_SESSION['loginid']);

		if ( isset($_COOKIE['logID']) )
		{
			setcookie('logID', "", time() - 3600);
		}
	}

	/* 
		SQL: $USER = USERID
		FILES: $USER = USERNAME
	*/
	public function recoverSession($user, $cookieid)
	{
		$userpath = $this->userpath;
		$useracs = new IniDat($userpath.$user.".ini", $this->sps);
		$s = new UserSQL;

		if ( $this->method == true )
		{
			if ( $m->KeyExists('loginID') )
			{
				$token = $useracs->getKeyDef('loginID');

				if (isset($_COOKIE['logID']))
					if($_COOKIE['logID'] == $token)
						$this->logIn($user, true);
			}
			else
			{
				$this->necessarykeys($user);

				$token = $useracs->getKeyDef('loginID');

				if (isset($_COOKIE['logID']))
					if($_COOKIE['logID'] == $token)
						$this->logIn($user, true);
			}
		}
		else
		{
			$s->recoversession($user);
		}
	}

	public function getUserInfo($user, $info)
	{
		$userpath = $this->userpath;
		$useracs = new IniDat($userpath.$user.".ini", $this->sps);
		$s = new UserSQL;

		if ( $this->m == true )
		{
			$info = $useracs->getKeyDef($info);
			return $info;
		}
		else
		{
			$info = $s->getUserInfo($user, $info);
			return $info;
		}
	}

	public function verCode($user, $vercode)
	{
		$userpath = $this->userpath;
		$s = new UserSQL;
		$m = new IniDat($userpath.$user.".ini", $this->sps);

		if ($this->m == true)
		{
			$code1 = $this->getUserInfo($user, 'verCode');
			$code = trim($code1);

			if ( $code == $vercode )
				return true;
			else
				return false;
		}
		else
		{
			$ver = $s->verCode($user, $vercode);

			return $ver;
		}
	}

	public function delUser($user, $reason, $author)
	{
		$userpath = $this->userpath;
		$s = new UserSQL;
		$m = new IniDat($userpath.$user.".ini", $this->sps);

		if ( $this->m == true )
		{
			$edit = $this->editUserInfo($user, 'active', '0');
			return $edit;
		}
		else
		{
			$edit = $s->delUser($user);
			return $edit;
		}
	}

	public function addUser($usr, $mail, $pwd, $ver = true, $fname = 'John', $lname = 'Doe', $rnk = '1', $bio = 'I love this site!')
	{
		$userpath = $this->path;
		$m = new Main;
		$s = new UserSQL;
		$i = new IniDat($userpath.$user.".ini", $this->sps);

		if ( $this->m )
		{
			if ( empty($usr) || empty($mail) || empty($pwd) )
				$error = true;

			if ( filter_var($mail, FILTER_VALIDATE_EMAIL) )
				$error = true;

			if (!isset($error))
			{
				if ($ver)
				{
					$verf = $m->rToken();
				}
				else
				{
					$verf = '1';
				}

				$s->insertData('users', array(
					"user" 			=> $usr,
					"email" 		=> $mail,
					"password"		=> md5($pwd),
					"firstname"		=> $fname,
					"lastname"		=> $lname,
					"rank"			=> $rnk,
					"bio"			=> $bio,
					"verification"	=> $verf,
					"regdate"		=> time(),
					"lastlogin"		=> '0',
					"bandate"		=> '0'
				));

				$num = $this->userExists($usr, 'user');

				if ( $num )
					return true;
				else
					return false;
			}
			else
			{
				return false;
			}
		}
		else
		{
			$edit = $s->addUser($usr, $mail, $pwd, $ver, $fname, $lname, $rnk, $bio);
		}
	}

	public function is_sampvalid($user)
	{
		$m = new Main;
		$f = explode("_", $user);

		if ( count($f) < 1 || empty($f) || !$f )
		{
			return false;
		}
		else
		{
			$users = explode("_", $user);

			if ( !$users OR !is_array($users) OR empty($users) OR count($users) < 2 )
			{
				return false;
			}
			else
			{
				if ( $m->validate($users[0], 'alpha') && $m->validate($users[1], 'alpha') )
				{
					return true;
				}
				else
				{
					return false;
				}
			}
		}
	}

	public function validate($user, $password, $passwordfield = 'password')
	{
		$userpath = $this->userpath;

		if ( $this->m == 'files' )
		{
			$i = new IniDat($userpath.$user.".ini", $this->sps);

			if ( $i->findMatch($passwordfield, $password) )
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			//SQL VER HERE
		}
	}

	public function isBanned($user, $bField = 'banned')
	{
		$userpath = $this->userpath;
		$i = new IniDat($userpath.$user.".ini", $this->sps);

		if ( $i->findMatch($bField, '1') )
		{
			return true;
		}
		else
		{
			return false;
		}	
	}

	public function isNewPanel($user)
	{
		$userpath = $this->userpath;
		$i = new IniDat($userpath.$user.".ini", $this->sps);

		if ( $i->findMatch('isNew', '1') )
		{
			return true;
		}
		else
		{
			return false;
		}	
	}

	public function getLoggedUser($session)
	{
		$s = new SqlMgt;

		$test = $s->getRow('userindex', 'userid', $session);
		$decode = base64_decode($session);

		var_dump($test);

		echo $decode;
		echo $test['userid'];

		if ( $test['userid'] == $session )
		{
			return true;
			print_r($test);
		}
		else
		{
			return false;
		}
	}

}