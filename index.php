<?php
	$url  = "http://". $_SERVER['SERVER_NAME'] ."/". $_SERVER['REQUEST_URI'];
	header("Location: $url/bazdig/console/");
