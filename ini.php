<?php
	define('WARAQ_CLASSPATH', realpath(WARAQ_ROOT) . "/lib");
	set_include_path(get_include_path() . PATH_SEPARATOR . WARAQ_CLASSPATH);
	ini_set("session.save_path", realpath(WARAQ_ROOT));

	require_once "waraqservice.php";

	$url  = "http://". $_SERVER['SERVER_NAME'] ."/". str_trunkate($_SERVER['REQUEST_URI'], '?');
	$file = dirname($_SERVER['SCRIPT_FILENAME']);

	$requestedService = new LocalResource($url, $file);
	$bazdigService = $requestedService->get(WARAQ_ROOT . '/bazdig');

	$GLOBALS['bazdig'] = new WaraqService($bazdigService->url, $bazdigService->file);
	$bazdig =& $GLOBALS['bazdig'];
	$bazdig->setparam("db", $bazdig->get('bazdig.db'));

function firstWord($string)
{
	$string = trim($string);
	return str_trunkate($string, ' ');
}

function str_trunkate($haystack, $needle)
{
	if (!$pos = strpos($haystack, $needle)) return $haystack;
	return substr($haystack, 0, $pos);
}
