<?php
	require "lib/localresource.php";
	$url  = "http://". $_SERVER['SERVER_NAME'] ."/". $_SERVER['REQUEST_URI'];
	$file = __FILE__;
	$here = new LocalResource($url, $file);
	$console = $here->base()->get("/bazdig/console/")->url;
	header("Location: $console", true, 307);
