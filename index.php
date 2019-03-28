<?php
	// $string = '21-01-1999';
	// $pattern = '/([0-9]{2})-([0-9]{2})-([0-9]{4})/';//это все заменит на то что в repalcement
	// $replacement = '$1'; // то что в скобках будет последовательно в маске вместо доллара
	// echo preg_replace($pattern, $replacement, $string);



	ini_set('error_reporting', E_ALL);
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);

	define('ROOT', dirname(__FILE__));
	session_start();
	require_once(ROOT.'/components/Router.php');
	require_once(ROOT.'/components/Db.php');
	require_once(ROOT.'/config/fb_config.php');
	require_once(ROOT.'/config/install.php');
	if (!Db::$connection)
		Db::getConnection();
	
	$router = new Router();
	$router->run();


?>

