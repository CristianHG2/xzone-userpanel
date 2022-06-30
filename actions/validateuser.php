<?

require '../Init.php';

$acc = new UserMgt;

if ( empty($_POST['user']) || !isset($_POST['user']) )
	$error = true;

if ( !$acc->is_sampvalid($_POST['user']) )
	$error = true;

if (!isset($error))
{
	if ( !$acc->userExists($_POST['user']) )
		$valid = 'false';
	else
		$valid = 'true';

	echo $valid;
}
else
{
	echo 'false';
}