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

class Quiz {

	static function __construct()
	{
		$s = new SqlMgt;

		if ( !$s->tableExists('questions') )
			$error[] = '<b>[Quiz]</b> Table \'questions\' does not exist.';

		if ( !$s->tableExists('testresults') )
			$error[] = '<B>[Quiz]</b> Table \'testresults\' does not exist.';

		if ( isset($error) )
		{
			echo "<h1 style=\"color: purple;\">FATAL ERROR</h1>";
			foreach ( $error as $i )
			{
				echo $i.'<br>';
			}
		}
	}

	static function createTest($name, $author)
	{
		$s = new SqlMgt;
		$data = array(
			"name"	 => $name,
			"author" => $author
		);

		if ( $s->InsertData('quizes', $data) )
			return true;
		else
			return false;
	}

	static function addQuestion($quiz, $question, $answers)
	{
		$s = new SqlMgt;

		if (!is_array($answers))
			$error = true;

		if ( isset($error) )
		{
			$answersim = implode("|||", $answers);
			$data = array(
				"question" => $question,
				"answers"  => $answersim,
				"quizid"   => $quiz
			);

			if ( $s->InsertData('questions', $data) )
				return true;
			else
				return false;
		}
		else
		{
			return false;
		}
	}

	static function removeQuestion($questionid, $quiz)
	{
		$s = new SqlMgt;

		if ( $s->delRow('questions', $questionid, 'id') )
			return true;
		else
			return false;
	}

	static function calculatePoints($answers)
	{
		//if ( is_array($answers)
	}

	static function submitTest($quiz, $points)
	{

	}

	static function retrieveTest($quiz)
	{
		
	}
}