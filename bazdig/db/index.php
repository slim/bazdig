<?php
    session_set_cookie_params(3000000);
	session_start();

	$previous_db_type = $_SESSION['old']['db_type'];
	$previous_db_host = $_SESSION['old']['db_host'];
	$previous_db_name = $_SESSION['old']['db_name'];
?>
<html>
<head>
<title>bazdig database settings</title>
<link rel="stylesheet" type="text/css" href="../bazdig.css" />
</head>
<body>
<div id="nav"><a href="../console/" accesskey="c" title="(c)" class="button">console</a><a href="../history/" accesskey="h" title="(h)">history</a></div>
<form id="settings" method='get' action='./set/' >
<label>Type 
	<select name="dbt">
		<option value="mysql" onclick="$('dbn').show(); $('dbh').show(); $('dbf').hide();">Mysql</option>
		<option value="sqlite" onclick="$('dbn').hide(); $('dbh').hide(); $('dbf').show();">sqlite</option>
	</select>
</label>
<label>Name <input id="dbn" type='text' name='dbn' /></label>
<label>File <input id="dbf" type='text' name='dbn' /></label>
<label>Host <input id="dbh" type='text' name='dbh' /></label>
<input type='submit' value='Save' />
</form>
<p><?php echo "<a href='./set/?dbt=$previous_db_type&dbn=$previous_db_name&dbh=$previous_db_host'>$previous_db_name@$previous_db_host</a>" ?></p>
<script>
$ = function(id) {
	return document.getElementById(id);
}
HTMLElement.prototype.hide = function () {
	this.parentNode.style.display = "none";
	this.disabled = true;
}
HTMLElement.prototype.show = function () {
	this.parentNode.style.display = "block";
	this.disabled = false;
}
$('dbf').hide();
</script>
</body>
</html>
