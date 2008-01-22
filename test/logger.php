<?php
	ob_start();
	echo date("c");
	echo " GET ";
	print_r($_GET);
	echo " POST ";
	print_r($_POST);
	$message = ob_get_contents();
	$log = fopen("logger.txt", "a");
	fwrite($log, $message); 
