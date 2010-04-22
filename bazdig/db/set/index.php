<?php
    session_set_cookie_params(3000000);
	session_start();

    define('WARAQ_ROOT', '../../..');
    require_once WARAQ_ROOT .'/'. 'ini.php';

	if ($_SESSION['db_name'] != $_GET['dbn'] || $_SESSION['db_host'] != $_GET['dbh']) {
		$_SESSION['old']['db_type'] = $_SESSION['db_type'];
		$_SESSION['old']['db_name'] = $_SESSION['db_name'];
		$_SESSION['old']['db_host'] = $_SESSION['db_host'];
	}
	$_SESSION['db_type'] 	 = $_GET['dbt'];
	$_SESSION['db_name'] 	 = $_GET['dbn'];
	$_SESSION['db_host'] 	 = $_GET['dbh'];

	header('Location: '. $bazdig->get('/console')->url .'?'. $_SERVER['QUERY_STRING'] );
