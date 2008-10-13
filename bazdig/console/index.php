<?php
    session_set_cookie_params(3000000);
	session_start();

	if ( "5" > phpversion()) {
		$error = "<b>ERROR</b> Bazdig works only with PHP5. sorry.";
		die("<div style='background-color: yellow; border: 2px solid red; padding: 10px; margin: 10px;'>$error</div>");
	}
	
    define('WARAQ_ROOT', '../..');
    require_once WARAQ_ROOT .'/'. 'ini.php';

	require_once "database.php";
	
	if (!$_SESSION['db_type']) {
		header('Location: '. $bazdig->get('/db')->url );
		die;
	}

	$db_user = $_SERVER['PHP_AUTH_USER'];
	$db_password = $_SERVER['PHP_AUTH_PW'];

	try {
		$work_db = new BDB(array('type' => $_SESSION['db_type'], 'name' => $_SESSION['db_name'], 'host' => $_SESSION['db_host']), $db_user, $db_password);
	} catch (Exception $e) { 
    	Header("WWW-Authenticate: Basic realm=\"$db_name@$db_host\"");
    	Header("HTTP/1.0 401 Unauthorized");
		$error = "<b>CONNECTION ERROR</b> check your server permissions then check that the PDO_SQLITE and PDO_MYSQL modules are installed <sub>(<a href='../db/'>select another database</a>)</sub>"; 
		die("<div style='background-color: yellow; border: 2px solid red; padding: 10px; margin: 10px;'>$error</div>");
	}

	$bazdig_db = $bazdig->getparam('db')->file; 
	if (!is_writable($bazdig_db)) {
		$error = "<b>WARNING</b> your history database is not writeable. <code>chmod 777 ". $bazdig->file ." && chmod 666 $bazdig_db</code></div>"; 
		echo "<div style='background-color: yellow; border: 2px solid red; padding: 10px; margin: 10px;'>$error</div>";
	}

?>
<html>
<head>
<title><?php echo $work_db->name .' @'. $work_db->host; ?> - bazdig</title>
<script src="../codepress/codepress.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="../bazdig.css" />
</head>
<body>
<div id="nav">
	<a href="../history/" accesskey="h" title="(h)">history</a><a href="../db/" accesskey="d" title="(d)">database</a>
</div>

<form method="get" action="../sql/exec/" target="_blank" >
<input type="hidden" name="dbt" value="<?php echo $_SESSION['db_type']; ?>"/>
<input type="hidden" name="dbn" value="<?php echo $_SESSION['db_name']; ?>"/>
<input type="hidden" name="dbh" value="<?php echo $_SESSION['db_host']; ?>"/>
<div id="console">
	<textarea id="input" name="input" class="codepress sql linenumbers-off" style="width:100%;height:350px;" wrap="off" tabindex="1">
<?php echo stripslashes($_GET['q']); ?>
	</textarea>
	<button id="ok" accesskey="k" title="(k)" onclick="q.value=input.getCode(); submit();">OK</button>
	<input type="hidden" name="q" id="q"/>
</div>
<div id="schema">
<?php 

	if ($work_db->name) {
		$dbName 	= $work_db->name;
		$dbLocation = $work_db->host;
		if ($work_db->type == 'sqlite' || $work_db->type == 'sqlite2') {
			$dbLocation = dirname($dbName);
			$dbName 	= basename($dbName);
		} 
		echo "<h3>".$dbName."</h3>";
		echo " @". $dbLocation; 
		try {
			echo $work_db->httpGet($bazdig->get('/db/schema/')); 
		} catch (Exception $e) { 
			$error = "<b>ERROR</b>". $e->getMessage(); 
			echo("<div style='background-color: yellow; border: 2px solid red; padding: 10px; margin: 10px;'>$error</div>");
		}
	} else {
		$error = "<b>WARNING</b> you have not selected a database";
		echo "<div style='background-color: yellow; border: 2px solid red; padding: 10px; margin: 10px;'>$error</div>";
	}
?>
</div>

</form>

</body>
</html>
