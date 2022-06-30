<?

/* ---------------------------------------------
*  MySQL(i) module
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

class SqlMgt {

	protected $bridge;
	
	public function init()
	{
		global $config;
		$link = new mysqli($config['sql']['host'], $config['sql']['user'], $config['sql']['pswd'], $config['sql']['db']);
		
		if ($link->connect_errno) 
			return $link->connect_error;
		else
			return true;

		$link->close();
	}

	public function unsafeQuery($query)
	{
		global $config;
		$access = new mysqli($config['sql']['host'], $config['sql']['user'], $config['sql']['pswd'], $config['sql']['db']);

		if ( $access->query($query) )
			return true;
		else
			return false;

		$access->close();
	}
	
	public function bind_array($stmt, &$row) 
	{
		$md = $stmt->result_metadata();
		$params = array();

		while($field = $md->fetch_field()) 
		{
			$params[] = &$row[$field->name];
		}

		call_user_func_array(array($stmt, 'bind_result'), $params);
	}

	public function query($query, $retrieve = true, $result = false)
	{
		global $config;
		$access = new mysqli($config['sql']['host'], $config['sql']['user'], $config['sql']['pswd'], $config['sql']['db']);
		
		$stmt = $access->prepare($query);

		if ($result)
		{
			$result = $access->query($query);
			return $result;
		}

		if (!$stmt->execute())
			echo "[SQL] Error.";

		if ( $retrieve )
		{
			$this->bind_array($stmt, $info);
			$result = $stmt->fetch();

			return $result;
		}

		$access->close();
	}

	public function getRow($table, $where, $wvalue)
	{
		$q = $this->query("SELECT * FROM $table WHERE $where = '".$wvalue."';");

		while ($row = $q->fetch_assoc())
		{
			return $row;
		}
	}

	public function insertData($tablename, $data)
	{
		global $config;
		$access = new mysqli($config['sql']['host'], $config['sql']['user'], $config['sql']['pswd'], $config['sql']['db']);

		$defsk = implode(array_keys($data), ", ");
		$defs = implode($data, ", ");

		ob_start();

		foreach($data as $result)
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

		if ( $this->query($insert, false, true) )
			return true;
		else
			return false;

		$access->close();
	}

	public function numRows($condition)
	{
		$num = $this->query($condition, false, true);
		$numr = $num->num_rows;

		if ($numr == 0 OR !$numr)
			return false;
		else
			return $numr;
	}

	public function delRow($table, $row, $by)
	{
		$query = "DELETE FROM $table WHERE $by = '".$row."'";

		if ($this->query($query))
			return true;
		else
			return false;
	}

	/**
	 * Edit rows
	 * @param  [type] $table       [description]
	 * @param  [type] $by          Where
	 * @param  [type] $row         Content of where
	 * @param  [type] $col         Column to edit
	 * @param  [type] $replacement Replacement
	 * @param [type] [varname] [description]
	 */
	
	public function editRow($table, $col, $replacement, $by, $row)
	{
		$query = "UPDATE $table SET $col = '".$replacement."' WHERE $by = '".$row."'";

		if ($this->query($query))
			return true;
		else
			return false;
	}

	public function tableExists($table)
	{
		$num = $this->query("SHOW TABLES LIKE $table", false, true);

		if ($num < 1)
			return false;
		else
			return true;
	}
}