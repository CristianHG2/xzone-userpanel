<?
require 'Init.php';

if ( !isset($_SESSION['loginid']))
	page("home", "Bienvenido", 'php', true, true, false);
else
{
	$m = new Main;
	$acc = new UserMgt;
	$nick = $m->deleteChar('_', $_SESSION['loginid']);

	$data = array(
		"vip"	=>	$acc->getUserInfo($_SESSION['loginid'], 'Premium'),
		"money"	=>	$acc->getUserInfo($_SESSION['loginid'], 'Dinero'),
		"bankmoney"	=> $acc->getUserInfo($_SESSION['loginid'], 'Banco')
    );

	page("userhome", $nick);
}
