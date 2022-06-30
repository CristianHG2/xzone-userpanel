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

class IniDat {

	protected $file;

	/**
	 * Class constructor
	 * @param string $var Filename
	 * @param string $line Optional line to add to file
	 */
	public function __construct($var, $spaces = " = ", $line = '0')
	{
		$this->file = $var;

		if (isset($_SESSION['createinifile']))
		{
			if (!file_exists($this->file))
				file_put_contents($this->file, $line);

			unset($_SESSION['createinifile']);
		}

		if ($line)
		{
			file_put_contents($this->file, $line."\n", FILE_APPEND);
		}

		$this->eq = $spaces;
	}
	
	public function init()
	{
		if ( !isset($this->file) || empty($this->file) )
			$er[] = '<br><b>[Ini2Data]</b> The file ($var) has not been set or is empty.';
			
		if ( file_exists($this->file) == false )
		{
			$er[] = '<br><b>[Ini2Data]</b> The file "'.$this->file.'" does not exist, once this page is refreshed, it\'ll be created.';
			$_SESSION['createinifile'] = true;
		}

		if ( isset($er) )
		{
			$return = implode("<br>", $er);
			return $return;
		}
		else
			return true;
	}

	/**
	 * Adds a line to the end of the file
	 * @param  string $key  File key
	 * @param  string $data Key definition
	 * @return boolean      Returns false if not added successfully
	 */
	public function addLine($key, $data)
	{
		$eq = $this->eq;

		$access = $this->file;

		$datainput = "".$key.$eq.$data."\n";
		file_put_contents($access, $datainput, FILE_APPEND);

		$file = file($this->file);
		$line = $file[count($file)-1];

		if (preg_match('/'.$datainput.'/', $line))
			return true;
		else
			return false;
	}

	/**
	 * Erase line based on key
	 * @param  string $key Key to find and erase
	 * @return boolean     Returns false if the line was not deleted
	 */
	public function delLine($key)
	{
		$eq = $this->eq;
		$m = new Main;

		$access = $this->file;
		$files = file($access);

		$linen1 = $m->getLineString($access, $key);
		$linen = $linen1 - 1;

		foreach ( $files as $line )
		{
			$line = preg_replace('/'.$files[$linen].'/', '', $line);
			$new_file[] = $line;
		}

$context = stream_context_create(array(

		                'ftp'=>array(

		                        'overwrite' => true

		                )

		        )

		);

		file_put_contents($access, $new_file, 0, $context);

		if ( $this->KeyExists($key) )
			return false;
		else
			return true;
	}

	/**
	 * Edits a line based on key
	 * @param  string $key     Key to modify
	 * @param  string $replace Replacement
	 */
	public function editLine($key, $replace)
	{
		$eq = $this->eq;
		$m = new Main;

		$access = $this->file;
		$this->delLine($key);

		$replacement = $key.$eq.$replace."\n";

		$exec = file_put_contents($access, $replacement, FILE_APPEND);

		if ( $exec )
			return true;
		else
			return false;
	}

	/**
	 * Obtains key list
	 * @return array Returns key list
	 */
	public function getKeyList()
	{
		$eq = $this->eq;
		$access = $this->file;
		$data   = parse_ini_file($access);

		return array_keys($data);
	}

	/**
	 * Verifies if the key is existant
	 * @param boolean $key Returns true if the key exists
	 */
	public function KeyExists($key)
	{
		$eq = $this->eq;
		$m = new Main;
		$access = $this->file;
		$file = file($access);
		$linen1 = $m->getLineString($access, $key);
		$linen2 = $linen1 - 1;
		if ( $linen2 == -1 )
		{
			$linen = 0;
		}
		else
		{
			$linen = $linen2;
		}
		$test = "$file[$linen]";

		if (!is_string($test) || !$test)
		{
			return false;
		}
		else
		{
			return true;
		}
	}

	/**
	 * Matches a key and result
	 * @param  string $key    Key to look for
	 * @param  string $result Definition to look for
	 * @return boolean        Returns true if the match was found
	 */
	public function findMatch($key, $result)
	{
		$eq = $this->eq;
		$m = new Main;
		$access = $this->file;
		$file = file($access);
		$linen1 = $m->getLineString($access, $key);
		$linen = $linen1 - 1;
		$test = "$file[$linen]";
		$val = explode($eq, $test);
		
		$pwd = trim($val[1]);

		if ($val[0] == $key && $pwd == $result)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Obtain key definition
	 * @param  string $key Key to look for
	 * @return string 	   Returns key definition
	 */
	public function getKeyDef($key)
	{
		$eq = $this->eq;
		$m = new Main;
		$access = $this->file;
		$file = file($access);
		$linen1 = $m->getLineString($access, $key);
		$linen = $linen1 - 1;
		$string = "$file[$linen]";

		$explode = explode($eq, $string);
		$def = $explode[1];

		return $def;
	}

	/**
	 * Find the key based on a definition
	 * @param  string $def Definition to look for
	 * @return string      Returns the key
	 */
	
	public function getDefKey($def)
	{
		$eq = $this->eq;
		$access = $this->file;
		$file = parse_ini_file($access);
		$records = array();

		foreach ( $file as $i )
		{
			$records[] = $i;
		}

		$key = array_search($def, $records);

		if ( $key == false )
		{
			echo 'false';
		}

		$file = file($access);
		$linen = "$file[$key]";
		$keyr = explode($eq, $linen);
		return $keyr[0];
	}


	/**
	 * Converts and inserts the .ini data into a mysql table
	 * @param  string $tablename The name of the table
	 * @return string Returns query
	 */
	public function insertSQL($tablename)
	{
		$eq = $this->eq;
		$access = $this->file;
		$file = parse_ini_file($access);

		$li = new SqlMgt;

		$defsk = implode(array_keys($file), ", ");
		$defs = implode($file, ", ");

		ob_start();
		foreach($file as $result)
		{
		   echo "'".$result."' ,";
		}

		$output = ob_get_clean();

		$touse = rtrim($output, ',');

		$insert =
		"
			INSERT INTO $tablename 
			(
				$defsk
			) 
			VALUES 
			(
				$touse
			);
		";

		$li->query($insert);

		$li->close();	
		
		return $insert;
	}

	/**
	 * Creates a table and inserts the data into a MySQL Database
	 * @param  database  $tablename Desired table name
	 * @param  boolean $insert    If false, it'll only create the table
	 * @param  string $config['sql']['db'] Database name
	 * @return string Returns the query
	 */
	public function convertSql($tablename, $insert = true)
	{
		$eq = $this->eq;
		global $config;
		$access = $this->file;
		$file = parse_ini_file($access);
		$li = new mysqli($config['sql']['host'], $config['sql']['user'], $config['sql']['pwd'], $config['sql']['db']);

		$keys = implode(array_keys($file), " VARCHAR (128), ");

		$query = 
		"
			CREATE TABLE $tablename (
			$keys VARCHAR (128), 
			ID int(11) NOT NULL auto_increment,
			primary KEY (ID));
		";

		$li->query($query);

		if ( $insert )
		{
			$defsk = implode(array_keys($file), ", ");
			$defs = implode($file, ", ");

			ob_start();

			foreach($file as $result)
			{
			   echo "'".$result."' ,";
			}

			$output = ob_get_clean();

			$touse = rtrim($output, ',');

			$insert =
			"
				INSERT INTO $tablename 
				(
					$defsk
				) 
				VALUES 
				(
					$touse
				);
			";

			$li->query($insert);
		}

		$li->close();
		
		return $query;
	}

	/**
	 * Returns various values
	 * @param  string $values Values to return
	 * @return array         Returns selected values in an array
	 */
	public function fetchIni($values = '1')
	{
		$eq = $this->eq;
		$access = $this->file;
		if ( is_array($values) )
		{
			foreach ( $values as $i )
			{
				$return[] = $this->getKeyDef($i);
			}
		}
		else
		{
			$return = parse_ini_file($access);
		}

		return $return;
	}
}