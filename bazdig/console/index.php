<?php
	session_start();

    define('WARAQ_ROOT', '../..');
    require_once WARAQ_ROOT .'/'. 'ini.php';

	require_once "database.php";
?>
<html>
<head>
<title>bazdig</title>
<script src="../codepress/codepress.js" type="text/javascript"></script>
<script src="../prototype-1.6.0.2.js" type="text/javascript"></script>
<script src="../bazdig.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="../bazdig.css" />
</head>
<body>

<div id="nav">
	<a href="../history/" accesskey="c" title="(c)">console</a><a href="../history/" accesskey="h" title="(h)">history</a><a href="../db/">database</a><a href="../bazdig.db" accesskey="s" title="(s)">save</a>
</div>

<form method="get" action="../sql/exec/" target="_new">

<div id="console">
	<textarea id="input" name="input" class="codepress sql linenumbers-off" style="width:100%;height:350px;" wrap="off" tabindex="1">
<?php echo stripslashes($_GET['q']); ?>
	</textarea>
</div>
<div id="schema"><?php 

if ($_SESSION['db_type']) {
	$work_db = new BDB(array('type' => $_SESSION['db_type'], 'name' => $_SESSION['db_name'], 'host' => $_SESSION['db_host']), $_SESSION['db_user'], $_SESSION['db_password']);
	echo "<p><b>".$work_db->name."</b>";
	echo " @". $work_db->host ."</p>";
	echo $work_db->httpGet($bazdig->get('/db/schema/')); 
} 

?>
</div>
	<button id="ok" accesskey="k" title="(k)" onclick="q.value=input.getCode(); submit();">OK</button>
	<input type="hidden" name="q" id="q"/>

<div id="settings" style="display:none">
	<label>Type <input type='text' name='dbt' /></label>
	<label>Name <input type='text' name='dbn' /></label>
	<label>Host <input type='text' name='dbh' /></label>
	<label>User <input type='text' name='dbu' /></label>
	<label>Password <input type='password' name='dbp' /></label>
</div>

</form>

<div id="history" style="display:none">
	<form method="get" action=".">
		<input type="text" name="q" value="<?php echo $_GET['q'] ?>"/>
	</form>
	<div id="queries">
	</div>
</div>
</body>
</html>
