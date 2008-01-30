<?php
	session_set_cookie_params(60*60*24*365);
	session_start();

    define('WARAQ_ROOT', '../../..');
    require_once WARAQ_ROOT .'/'. 'ini.php';

	$_SESSION['db'] = $_GET['db'];
	$_SESSION['db_user'] = $_GET['dbu'];
	$_SESSION['db_password'] = $_GET['dbp'];

	header('Location: '. $bazdig->get('/console')->url );
