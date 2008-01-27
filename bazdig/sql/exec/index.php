<?php
	session_start();
	
    define('WARAQ_ROOT', '../../..');
    require_once WARAQ_ROOT .'/'. 'ini.php';

	if (!$_SESSION['db']) {
		header('Location: '. $bazdig->get('/db')->url );
	}

	require "code.php";

	$history_db = new PDO("sqlite:". $bazdig->getparam('db')->file);
	$work_db = new PDO($_SESSION['db'], $_SESSION['db_user'], $_SESSION['db_password']);

	SqlCode::set_db($history_db);
	$query = new SqlCode(stripslashes($_GET['q']));
	try {
		$result = $query->exec($work_db);
	} catch (Exception $e) { 
		die("ERREUR SQL:". $e->getMessage());
	}

	$query->save();
	$columns = columnNames($result);
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
	$rows = $result->fetchAll(PDO::FETCH_NUM);
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
