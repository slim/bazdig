<?php
	define('WARAQ_CLASSPATH', realpath(WARAQ_ROOT) . "/lib");
	set_include_path(get_include_path() . PATH_SEPARATOR . WARAQ_CLASSPATH);

	require_once "waraqservice.php";

	$GLOBALS['bazdig'] = new WaraqService("http://localhost/slim/bazdig", "/home/slim/waraq/bazdig");
	$bazdig =& $GLOBALS['bazdig'];
	$bazdig->setparam("db", $bazdig->get('/bazdig.db'));

function columnNames($row)
{
	$names = array();
	if (is_array($row)) $names = array_keys($row);
	return $names;
}

function firstWord($string)
{
	$string = trim($string);
	if (!$pos = strpos($string, ' ')) return $string;
	return substr($string, 0, $pos);
}
