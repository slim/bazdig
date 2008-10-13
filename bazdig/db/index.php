<?php
    session_set_cookie_params(3000000);
	session_start();

	$previous_db_type = $_SESSION['old']['db_type'];
	$previous_db_host = $_SESSION['old']['db_host'];
	$previous_db_name = $_SESSION['old']['db_name'];
?>
<title>bazdig database settings</title>
<link rel="stylesheet" type="text/css" href="../bazdig.css" />
<div id="nav"><a href="../console/" accesskey="c" title="(c)" class="button">console</a><a href="../history/" accesskey="h" title="(h)">history</a></div>
<form id="settings" method='get' action='./set/' >
<label>Type <input type='text' name='dbt' /></label>
<label>Name <input type='text' name='dbn' /></label>
<label>Host <input type='text' name='dbh' /></label>
<input type='submit' value='Save' />
</form>
<p><?php echo "<a href='./set/?dbt=$previous_db_type&dbn=$previous_db_name&dbh=$previous_db_host'>$previous_db_name@$previous_db_host</a>" ?></p>
