<?php
	$testIni = parse_ini_file('test.ini');
	print_r($testIni);
	set_include_path(get_include_path(), PATH_SEPARATOR . $testIni['lib']);
?>
