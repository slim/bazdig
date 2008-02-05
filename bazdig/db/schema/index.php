<?php

    define('WARAQ_ROOT', '../../..');
    require_once WARAQ_ROOT .'/'. 'ini.php';

	require_once "database.php";

	$work_db = new BDB(array('type' => $_GET['dbt'], 'name' => $_GET['dbn'], 'host' => $_GET['dbh']), $_GET['dbu'], $_GET['dbp']);

	$tables = $work_db->listTables();
	echo "<ul>";
	foreach ($tables as $table) {
		echo "<li><a onclick=\"input.editor.insertCode(' ". $table->name .",', false); input.select()\">". $table->name ."</a>"; 
		echo "<ul>";
		foreach ($table->loadColumns($work_db) as $column) {
			echo "<li><a onclick=\"input.editor.insertCode(' ". $column->name .",', false); input.focus()\">". $column->name ."</a>";
			echo "</li>";
		}
		echo "</ul>";
		echo "</li>";
	}
	echo "</ul>";
