<?

/* ---------------------------------------------
*  MySQL account management
*  Simplified MySQL server management
*  ---------------------------------------------
*  Developed by Christian Herrera for Lawndale Roleplay
*  ah, and also the SA:MP community.
*
*  All rights (and lefts) reserved. 2014 (c) Studio Wolfree
*  SW Development tools
*/

/**
 * Simplified MySQL server management
 */

class UserSQL {

	public function addUser($usr, $mail, $pwd, $ver = true, $fname = 'John', $lname = 'Doe', $rnk = '1', $bio = 'I love this site!')
	{
		$m = new Main;
		$s = new SqlMgt;

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

	public function delUser($user, $reason, $author, $anon = '1', $perm = false)
	{
		$m = new Main;
		$s = new SqlMgt;

		if (empty($user) || empty($reason) || empty($author))
			$error = true;

		if ( !$m->validate($user, 'alnum') )
			$error = true;

		if ( !$m->validate($user, 'space'))
			$error = true;

		if ( !isset($error) )
		{
			$s->insertData('logs', array(
					"type"		=>	1,
					"reason"	=>	$reason,
					"author"	=> $author,
					"anon"		=> $anon
				));

			$execmain = $s->editRow('users', 'id', $user, 'rank', '-1');

			if ( $execmain )
				return true;
			else
				return false;
		}
		else
		{
			return false;
		}
	}

	public function verCode($user, $vercode)
	{
		$s = new SqlMgt;

		$row1 = $s->getRow('users', 'verification', 'id', $user);
		$row = trim($row1);

		if ( $row == $vercode )
			return true;
		else
			return false;
	}

	public function editUserInfo($user, $data, $change)
	{
		$s = new SqlMgt;

		if ( $s->editRow('users', 'id', $user, $data, $change) )
			return true;
		else
			return false;
	}

	public function userExists($user, $id = true)
	{
		$s = new SqlMgt;

		if ( $id )
			$query = "SELECT * FROM users WHERE id = $user";
		else
			$query = "SELECT * FROM users WHERE $id = '$user'";

		$num = $s->numRows($query);

		if ($num == 0)
			return false;
		else
			return true;
	}

	public function logIn($user, $cookie = true)
	{
		$m = new Main;
		$s = new SqlMgt;
		$token = $m->rToken();

		$m->_COOKIE('logID', $token, $cookie);

		$this->editUserInfo($user, 'loginID', $token);
		$_SESSION['loginid'] = $token;

		$this->editUserInfo($user, 'lastlogin', time());
	}

	public function logOut()
	{
		unset($_SESSION['loginid']);

		if ( isset($_COOKIE['logID']) )
		{
			setcookie('logID', "", time() - 3600);
		}
	}

	public function recoverSession($user, $cookieid)
	{
		$s = new SqlMgt;

		$token = $s->getRow('users', 'loginID', 'id', $user);

		if (isset($_COOKIE['logID']))
			if($_COOKIE['logID'] == $token)
				$this->logIn($user, true);
	}

	public function getUserInfo($user, $info)
	{
		$s = new SqlMgt;

		$info = $s->getRow('users', 'loginID', $info, $user);

		return $info;
	}
}