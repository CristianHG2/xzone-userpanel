<?

$config = array('sql', 'context');
$servquest = array('amb', 'csm', 'skt');

$config['sql'] 	   = array('host', 'user', 'pswd', 'db');
$config['context'] = array('title', 'url', 'path');

//SERVQUEST IS DEACTIVATED

/********************* SERVQUEST 0.5 - MANUAL *******************/
/*
*	Servquest is an easy way to allow the frontend interact with the backend part of your
* 	website.
*
*	In this comment, we'll give you a minified introduction to Servquest.
*
* 	PART 1 - SERVQUEST VARIABLES
*  	------------------------------------------------------------
*   Servquest communicates with the servquest class (Which communicates with the server) through
*   a series of array variables, which can easily be user defined through the modification of
*   "servquestdata.ini", located in the "inc/data/" folder.
*
* 	There are three types of variables:
* 		- Ambassador
* 		- Cosmetic
* 		- Socket
*
* 	Each variable type represents a task that can be executed through the servquest class,
* 	therefore, we'll explain the correct usage and setup of prefab or new ambassador, cosmetic,
* 	and socket types.
*
*	PART 2 - SERVQUEST PREFABS
*	------------------------------------------------------------
*
* 	Before we begin, we'll list the
 */

$servquest['amb'] = array('dtype', 'name', 'jsref', 'includejs');

$config['sql'] = array(
	"host"	=> "localhost", // SQL server
	"user"	=> "root", // SQL User
	"pswd"	=> "lol123", // User password
	"db"	=> "lawn" // Database
);

$config['context'] = array(
	"is_setup" => false, // If the site (config.php) was correctly setup
	"title"		=> "XZone Roleplay", // Site name
	"url"		=> "http://localhost:8080/userpanel/", // Site URL (EX. http://www.studiowolfree.net)
	"path"		=> "C:/wamp/www/userpanel/", // Site path (EX. /home/user/site/)
	"pageindex"	=> "flat", // Page indexation system (Flat or SQL)
	"usercdn"	=> "C:/wamp/www/userpanel/usercdn/", // User CDN (Where media is stored)
	"sitever"	=> "indev", // Site version
	"actions"	=> "actions", // Actions
	"logo"		=> "<img src=\"http://localhost:8080/userpanel/img/logo.png\">" // Logo
);

global $config, $servquest;