<?

/* ---------------------------------------------
*  Ini2Data
*  Plain (.ini) file management system
*  ---------------------------------------------
*  Developed by Christian Herrera for Lawndale Roleplay
*  ah, and also the SA:MP community.
*
*  All rights (and lefts) reserved. 2014 (c) Studio Wolfree
*  SW Development tools
*/

/**
 * Plain file management class
 */


class Upload {

	public function __construct($filepath)
	{
		global $config;

		if ( empty($filepath) || !isset($filepath) )
		{
			echo "<b>[Upload]<b> Filepath not set.";
		}

		$this->fp = $config['context']['path'].$filepath;
	}

	public function uploadfile($file, $restrictions = false, $randomize = true)
	{
		global $config;
		$m = new Main;

		$fILES = $file;

		if ( $restrictions )
		{
			$valid = $restrictions;

			if ( $randomize )
			{
				$token = $m->rToken();
				$extension = explode('.', $fILES["file"]["name"]);
				$name = $token. "." .$extension[1];
			}
			else
			{
				$name = $fILES["file"]["name"];
			}

			if ( !$valid )
			{
				if (file_exists($this->fp . $fILES["file"]["name"]))
				{
					return false;
				}
				else
				{
					if ( move_uploaded_file($fILES["file"]["tmp_name"], $this->fp . "\\" . $name) )
						return true;
					else
						return false;
				}
			}
		}
		else
		{
			if ( $randomize )
			{
				$token = $m->rToken();
				$extension = explode('.', $fILES["file"]["name"]);
				$name = $token. "." .$extension[1];
			}
			else
			{
				$name = $fILES["file"]["name"];
			}

			if (file_exists($this->fp . "\\" . $fILES["file"]["name"]))
				return false;
			else
			{
				if ( move_uploaded_file($fILES["file"]["tmp_name"], $this->fp . "\\" . $name) )
					return true;
				else
					return false;
			}
		}
	}

	/*
	ERROR CODES:
		1 - Invalid format
		2 - Invalid size
		3 - Invalid size and format
	 */
	public function setRestrictions($file, $size = false, $formats = false)
	{
		$temp = explode(".", $file["file"]["name"]);
		$array = count($formats);

		if ( $array > 1 && $size != false )
		{
			if ( in_array($temp[1], $formats) )
			{
				$tsize = $this->convertSize($file["file"]["size"]);

				if ( $tsize <= $size )
				{
					$error = 2;
				}
				else
				{
					$error = false;
				}
			}
			else
			{
				$tsize = $this->convertSize($file["file"]["size"]);

				if ( $tsize <= $size )
				{
					$error = 1;
				}
				else
				{
					$error = 3;
				}
			}

			return $error;
		}
		elseif ( $array < 1 && $size != false )
		{
			if ( $formats == $temp[1] )
			{
				$tsize = $this->convertSize($file["file"]["size"]);

				if ( $tsize <= $size )
				{
					$error = 2;
				}
				else
				{
					$error = false;
				}
			}
			else
			{
				$tsize = $this->convertSize($file["file"]["size"]);

				if ( $tsize <= $size )
				{
					$error = 1;
				}
				else
				{
					$error = 3;
				}
			}

			return $error;
		}
		elseif ( $array < 1 && $size == false )
		{
			if ( $formats == $temp[1] )
			{
				$error = false;				
			}
			else
			{
				$error = 1;
			}
		}
		elseif ( $formats == false && $size != false )
		{
				$tsize = $this->convertSize($file["file"]["size"]);

				if ( $tsize <= $size )
				{
					$error = 1;
				}
				else
				{
					$error = 2;
				}
		}
		else
		{
			return false;
		}
	}

	public function convertSize($bytes, $converto = 'mb') 
	{
		if ($converto == 'mb') {
			return round($bytes / KILO, 2);
		}

		if ($converto == 'gb') {
			return round($bytes / MEGA, 2);
		}

		if ($converto == 'tb') {
			return round($bytes / GIGA, 2);
		}

		if ( $converto != 'mb' && $converto != 'tb' && $converto != 'kb' )
			return false;
	}
}