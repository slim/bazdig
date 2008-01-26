<?php

    define('WARAQ_ROOT', '../..');
    require_once WARAQ_ROOT .'/'. 'ini.php';

	require "code.php";

	$dbFile = $bazdig->get("/bazdig.db");
	$console = $bazdig->get("/console");
	SqlCode::set_db("sqlite:". $dbFile->get_file());

	$queries = SqlCode::select('order by date desc limit 10');	
	foreach ($queries as $q) {
		echo '<a style="display:block" href="'. $console->get_url() .'?q='. $q->code .'" >'. $q->code .'</a>';
	}
