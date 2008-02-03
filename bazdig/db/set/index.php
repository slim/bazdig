<?php
	session_start();

    define('WARAQ_ROOT', '../../..');
    require_once WARAQ_ROOT .'/'. 'ini.php';

	$_SESSION['db_type'] = $_GET['dbt'];
	$_SESSION['db_name'] = $_GET['dbn'];
	$_SESSION['db_host'] = $_GET['dbh'];
	$_SESSION['db_user'] = $_GET['dbu'];
	$_SESSION['db_password'] = $_GET['dbp'];

	header('Location: '. $bazdig->get('/console')->url );
