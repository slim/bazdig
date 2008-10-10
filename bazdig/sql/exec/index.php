<?php
	session_set_cookie_params(3000000);
	session_start();
	
    define('WARAQ_ROOT', '../../..');
    require_once WARAQ_ROOT .'/'. 'ini.php';

	require "code.php";

	$db_type = $_GET['dbt'];
	$db_name = $_GET['dbn'];
	$db_host = $_GET['dbh'];
	$db_user = $_SERVER['PHP_AUTH_USER'];
	$db_password = $_SERVER['PHP_AUTH_PW'];

	if (!$_GET['q']) {
		header('Location: '. $bazdig->get('/console')->url );
	}

	try {
		$history_db = new PDO("sqlite:". $bazdig->getparam('db')->file);
		$work_db = new BDB(array('type' => $db_type, 'name' => $db_name, 'host' => $db_host), $db_user, $db_password);
		SqlCode::set_db($history_db);
	} catch (Exception $e) {
    	Header("WWW-Authenticate: Basic realm=\"$db_name@$db_host\"");
    	Header("HTTP/1.0 401 Unauthorized");
		$error = "<b>CONNECTION ERROR</b> check your server permissions then check that the PDO_SQLITE and PDO_MYSQL modules are installed"; 
		die("<div style='background-color: yellow; border: 2px solid red; padding: 10px; margin: 10px;'>$error</div>");
	}

	$query = new SqlCode(trim(stripslashes($_GET['q'])));
?>
<html>
<head>
<style type="text/css">

table, tr, td, th {
       margin: 0px;
       border-width: 0px;
       border-spacing: 0px;
       border-collapse: collapse;
}
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
		$rows = $result->fetchAll(PDO::FETCH_NUM);
		if (count($rows) < 1) {
			die("<table><tr><th>Empty</th></tr></table>");
		}
	} catch (Exception $e) { 
		die("<table><tr><th>Empty</th></tr></table>");
	}
	$columns = array();
	for ($i=0; $i < $result->columnCount(); $i++) {
		$colMeta  = $result->getColumnMeta($i);
		$columns[$i] = $colMeta['name'];
	}
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
