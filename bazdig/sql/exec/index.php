<?php
	session_start();
	
	if (!$_SESSION['db']) {
		header('Location: '. $bazdig->get('/db')->url );
	}

    define('WARAQ_ROOT', '../../..');
    require_once WARAQ_ROOT .'/'. 'ini.php';

	require "code.php";

	$history_db = new PDO("sqlite:". $bazdig->getparam('db')->file);
	$work_db = new PDO($_SESSION['db'], $_SESSION['db_user'], $_SESSION['db_password']);

	SqlCode::set_db($history_db);
	$query = new SqlCode($_GET['q']);
	try {
		$result = $query->exec($work_db);
	} catch (Exception $e) { 
		die($e->getMessage());
	}

	$query->save();
	foreach ($result as $r) {
		print_r($r);
	}
