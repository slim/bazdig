<?php
    session_set_cookie_params(3000000);
	session_start();

    define('WARAQ_ROOT', '../../..');
    require_once WARAQ_ROOT .'/'. 'ini.php';

	$_SESSION['db_type'] 	 = $_GET['dbt'];
	$_SESSION['db_name'] 	 = $_GET['dbn'];
	$_SESSION['db_host'] 	 = $_GET['dbh'];

	header('Location: '. $bazdig->get('/console')->url );
