<?
//PWD: 
require '../Init.php';

$acc2 = new UserMgt('files', '=');

if ( empty($_POST['user']) || !isset($_POST['user']) || !$acc2->is_sampvalid($_POST['user']) || !$acc2->userExists($_POST['user']))
	$error[] = 'Usuario no existente o valido.';

if ( empty($_POST['pswd']) || !isset($_POST['pswd']) )
	$error[] = 'El campo de contraseña no debe estar vacio.';

if ( $acc2->isBanned($_POST['user'], 'pBaneado') )
	$error[] = 'Estas sancionado, no puedes iniciar sesión.';

if ( !isset($error) )
{
	$dpwd = strtoupper(hash('whirlpool', $_POST['pswd']));
	$pwd2 = $acc2->getUserInfo($_POST['user'], 'Clave');
	$trim = trim($pwd2);

	if ( $dpwd === $trim )
	{
		$acc2->logIn($_POST['user']);
	}
	else
	{
		echo "test";
	}

	header('Location: ../home.php');
}
else
{
	$message = '';

	foreach ( $error as $i )
	{
		$message = "<li>$i</li>";
	}

	var_dump($_POST['user']);
	var_dump($acc2->is_sampvalid($_POST['user']));
	var_dump($acc2->userExists($_POST['user']));

	$_SESSION['error'] = $message;
	header('Location: ../home.php');
}