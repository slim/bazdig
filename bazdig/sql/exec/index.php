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

	$history_db = new PDO("sqlite:". $bazdig->getparam('db')->file);
	$work_db = new BDB(array('type' => $_SESSION['db_type'], 'name' => $_SESSION['db_name'], 'host' => $_SESSION['db_host']), $_SESSION['db_user'], $_SESSION['db_password']);

	SqlCode::set_db($history_db);
	$query = new SqlCode(stripslashes($_GET['q']));
	try {
		$result = $query->exec($work_db);
	} catch (Exception $e) { 
		die("ERREUR SQL:". $e->getMessage());
	}

	$query->save();
	$rows = $result->fetchAll(PDO::FETCH_ASSOC);
	$columns = columnNames($rows[0]);
?>
<html>
<head>
<title><?php echo join($columns, ' '); ?></title>
<style type="text/css">
	table tr td {border: solid 1px silver; padding: 10px}
	table tr th {border: solid 1px grey; padding: 10px}
</style>
</head>
<body>
<table>
<?php
	echo "<tr>";
	foreach ($columns as $c) {
		echo "<th>$c</th>";
	}
	echo "</tr>";
	foreach ($rows as $r) {
		echo "<tr>";
		foreach ($r as $value) {
			echo "<td>$value</td>";
		}
		echo "</tr>";
	}
?>
</table>
</body>
</html>
