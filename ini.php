<?php
	define('WARAQ_CLASSPATH', realpath(WARAQ_ROOT) . "/lib");
	set_include_path(get_include_path() . PATH_SEPARATOR . WARAQ_CLASSPATH);

	require_once "waraqservice.php";

	$url  = "http://". $_SERVER['SERVER_NAME'] ."/". $_SERVER['REQUEST_URI'];
	$file = $_SERVER['SCRIPT_FILENAME'];
	$requestedService = new LocalResource($url, $file);
	$bazdigService = $requestedService->get(WARAQ_ROOT);
	$GLOBALS['bazdig'] = new WaraqService($bazdigService->url, $bazdigService->file);
	$bazdig =& $GLOBALS['bazdig'];
	$bazdig->setparam("db", $bazdig->get('/bazdig.db'));

function columnNames($row)
{
	return array_keys($row);
}

function firstWord($string)
{
	$string = trim($string);
	if (!$pos = strpos($string, ' ')) return $string;
	return substr($string, 0, $pos);
}
