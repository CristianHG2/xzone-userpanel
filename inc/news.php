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

class News {
	
	/**
	 * Retrieve news that meet criteria
	 * @param  boolean $query Return query or result?
	 * @param  boolean $pag Allow or deny pagination
	 * @param  varchar $pagin Page index
	 * @param  int $pxp Articles per page
	 * @param  int $author Author (User ID)
	 * @param  int $cat Category (Category ID)
	 * @param  int $dateb Include news published before * (UNIX timestamp)
	 * @param  int $datea Include news published after * (UNIX timestamp)
	 * @return Returns false if invalid, if else, returns the query or result
	 */
	static function retrieveNews($query = true, $pag = false, $pagin = 'no', $pxp = false, $author = false, $cat = false, $dateb = false, $datea = false)
	{
		$sql = new SqlMgt;

		if ( !$sql->numRows("SELECT * FROM news")==0 )
		{
			if ($pag AND $pagin == '-4') 
			{
				return '<b>[News]</b> In order to include pagination, you must include a page index.<br>';
			}
			
			if ($pag and !$pxp)
			{
				return '<b>[News]</b> Please assign the news that will be shown per page';
			}


			if ($author)
			{
				$atr = 'WHERE author = \''.$author.'\'';
				$ca = true;
			}
			else
				$atr = null;
				$ca = false;

			if ($cat)
			{
				$ct = 'WHERE cat = \''.$cat.'\'';
				$cc = true;
			}
			else
				$ct = null;
				$cc = false;

			if ($dateb AND !$datea)
			{
				$df = 'WHERE date <= \''.$dateb.'\'';
				$cb = true;
			}
			else
				$df = null;
				$cb = false;

			if ($datea AND !$dateb)
			{
				$da = 'WHERE date >= \''.$datea.'\'';
				$ca = true;
			}
			else
				$da = null;
				$ca = false;

			if ($datea AND $dateb)
			{
				$df = 'WHERE date BETWEEN \''.$dateb.'\' AND \''.$datea.'\'';
				$da = null;
			}

			if ($ca AND $cc)
				$and1 = ' AND ';
			else
				$and1 = null;

			if ($cc AND $cb)
			{
				$and2 = ' AND ';
			}
			else
				$and2 = null;

			if ($cb and $ca)
				$and3 = ' AND ';
			else
				$and3 = null;

			$bquery = "SELECT * FROM news $atr$and1$ct$and2$df$and3$da;";

			if ( !$pag )
			{
				if ( !$pxp )
				{
					if ( $pagin == 'no' )
					{
						$limit = null;
					}
				}
			}
			else
			{
				$in = $pagin;

					if ( $in == 0 )
					{
						$start = 0;
						$finish = $pxp;
					}
					else
					{
						$p = $pxp * $pagin;
						$start = $p - $pxp;
						$finish = $p;
					}

					$limit = "LIMIT $start, $finish";
			}

			if ( !$pag )
			{
				$nr = $sql->numRows($bquery);
				if ( $nr > 0 )
					$tquery = $bquery;
				else
					$tquery = false;
			}
			else
			{
				$pquery = "SELECT * FROM news $atr$and1$ct$and2$df$and3$da$limit;";

				$nr = $sql->numRows($bquery);
				if ( $nr > 0 )
					$tquery = $pquery;
				else
					$tquery = false;
					
			}

			if ( $query )
				return $tquery;
			else
			{
				$result = $sql->query($tquery, true, false);
				return $result;
			}
		}
		else
		{
			return false;
		}
	}

	public function returnSearch($pxp)
	{
		$s = new SqlMgt;

		$q2 = $this->retrieveNews();

		$rows = $s->numRows($q2);

		$t = $rows / $pxp;
		$t2 = round($t);

		if ( strpos($t, '.') )
			$search = $t2 + 1;
		else
			$search = $t2;

		return $search;
	}

	public function inStateFilter($index)
	{
		$m = new Main;

		if ( !isset($index) OR empty($index))
		{
			$page = 0;
		}
		else
		{
			$filter = $m->fxs($index);
			$page = $filter;
		}

		return $page;
	}

	public function insertPag($search, $index, $cssclass = 'navbutton')
	{
		for ($i = 1; $i <= $search; $i++)
		{
			echo "
			<a href=\"?".$index."=".$i."\">
			<div class=\"$cssclass\">
				$i
			</div>
			</a> ";
		}
	}
	
	static function init()
	{
		$sql = new SqlMgt;

		if($sql->numRows("SHOW TABLES LIKE 'news'") < 1) 
			return '<br><b>[News]</b> \'news\' table does not exist.';
		else
			return true;
	}
}