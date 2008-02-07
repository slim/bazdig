<?php
	define('WARAQ_CLASSPATH', realpath(WARAQ_ROOT) . "/lib");
	set_include_path(get_include_path() . PATH_SEPARATOR . WARAQ_CLASSPATH);

	require_once "waraqservice.php";

	$GLOBALS['bazdig'] = new WaraqService("http://localhost/slim/bazdig", "/home/slim/waraq/bazdig");
	$bazdig =& $GLOBALS['bazdig'];
	$bazdig->setparam("db", "mysql:host=localhost;dbname=bazdig");
	$bazdig->setparam("db/user", "root");
	$bazdig->setparam("db/password", "");
?>
