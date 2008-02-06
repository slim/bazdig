<?php
	session_start();
	
    define('WARAQ_ROOT', '../..');
    require_once WARAQ_ROOT .'/'. 'ini.php';

	require_once "database.php";
	
	if (!$_SESSION['db_type']) {
		header('Location: '. $bazdig->get('/db')->url );
	}

?>
<html>
<head>
<title>bazdig</title>
<script src="../codepress/codepress.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="../bazdig.css" />
</head>
<body>
<?php
	$bazdig_db = $bazdig->getparam('db')->file; 
	if (!is_writable($bazdig_db)) {
		echo '<div id="error"><b>WARNING</b> your history database is not writeable. <code>chmod 777 '. $bazdig->file .' && chmod 666 '. $bazdig_db .'</code></div>'; 
	}
?>
<div id="nav">
	<a href="../history/" accesskey="h" title="(h)">history</a><a href="../db/" accesskey="d" title="(d)">database</a>
</div>

<form method="get" action="../sql/exec/" target="_new">

<div id="console">
	<textarea id="input" name="input" class="codepress sql linenumbers-off" style="width:100%;height:350px;" wrap="off" tabindex="1">
<?php echo stripslashes($_GET['q']); ?>
	</textarea>
	<button id="ok" accesskey="k" title="(k)" onclick="q.value=input.getCode(); submit();">OK</button>
	<input type="hidden" name="q" id="q"/>
</div>
<div id="schema"><?php 

if ($_SESSION['db_type']) {
	$work_db = new BDB(array('type' => $_SESSION['db_type'], 'name' => $_SESSION['db_name'], 'host' => $_SESSION['db_host']), $_SESSION['db_user'], $_SESSION['db_password']);
	$dbName 	= $work_db->name;
	$dbLocation = $work_db->host;
	if ($work_db->type == 'sqlite' || $work_db->type == 'sqlite2') {
		$dbLocation = dirname($dbName);
		$dbName 	= basename($dbName);
	} 
	echo "<h3>".$dbName."</h3>";
	echo " @". $dbLocation; 
	echo $work_db->httpGet($bazdig->get('/db/schema/')); 
} 

?>
</div>

</form>

</body>
</html>
