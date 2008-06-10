<?php
	session_start();
	
    define('WARAQ_ROOT', '../../..');
    require_once WARAQ_ROOT .'/'. 'ini.php';

	require "code.php";

	if ($_GET['dbt']) {
		$_SESSION['db_type'] = $_GET['dbt'];
		$_SESSION['db_name'] = $_GET['dbn'];
		$_SESSION['db_host'] = $_GET['dbh'];
		$_SESSION['db_user'] = $_GET['dbu'];
		$_SESSION['db_password'] = $_GET['dbp'];
	}

	if (!$_SESSION['db_type'] or !$_GET['q']) {
		header('Location: '. $bazdig->get('/console')->url );
	}

	try {
		$history_db = new PDO("sqlite:". $bazdig->getparam('db')->file);
		$work_db = new BDB(array('type' => $_SESSION['db_type'], 'name' => $_SESSION['db_name'], 'host' => $_SESSION['db_host']), $_SESSION['db_user'], $_SESSION['db_password']);
		SqlCode::set_db($history_db);
	} catch (Exception $e) {
		$error = "<b>DATABASE ERROR</b> check you have PDO_SQLITE and PDO_MYSQL <sub>(". $e->getMessage() .")</sub>";
		die("<div style='background-color: yellow; border: 2px solid red; padding: 10px; margin: 10px;'>$error</div>");
	}

	$query = new SqlCode(trim(stripslashes($_GET['q'])));
?>
<html>
<head>
<style type="text/css">
	table tr td {border: solid 1px silver; padding: 10px}
	table tr th {border: solid 1px grey; padding: 10px}
	#error {
		background-color: yellow;
		border: 2px solid red;
		padding: 10px;
		margin: 10px;
	}
</style>
<?php
	try {
		$result = $query->exec($work_db);
	} catch (Exception $e) { 
		$error = "<b>SQL ERROR</b> ". $e->getMessage() ;
		die("<div style='background-color: yellow; border: 2px solid red; padding: 10px; margin: 10px;'>$error</div>");
	}

	$query->save();

	try {
		$rows = $result->fetchAll(PDO::FETCH_ASSOC);
		if (count($rows) < 1) {
			die("<table><tr><th>Empty</th></tr></table>");
		}
	} catch (Exception $e) { 
		die("<table><tr><th>Empty</th></tr></table>");
	}
	$columns = columnNames($rows[0]);
	$title = $query->get_title() ? $query->get_title() : join($columns, ' ');

?>
<title><?php echo $title; ?></title>
</head>
<body>
<?php
	echo "<table><tr>";
	foreach ($columns as $c) {
		echo "<th>$c</th>";
	}
	echo "</tr>";
	foreach ($rows as $r) {
		echo "<tr>";
		foreach ($r as $value) {
			echo "<td><pre>$value</pre></td>";
		}
		echo "</tr>";
	}
	echo "</table>";
?>
</body>
</html>
