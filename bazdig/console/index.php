<?php
	session_start();
?>
<html>
<head>
<title>bazdig</title>
<script src="../codepress/codepress.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="../bazdig.css" />
</head>
<body>
<p><a class="button" href="../history/" accesskey="h" title="(h)">history</a><a class="button" href="../db/">database</a> </p>
<form method="get" action="../sql/exec/" target="_new">
<textarea id="input" name="input" class="codepress sql linenumbers-off" style="width:70%;height:350px;" wrap="off" tabindex="1">
<?php echo stripslashes($_GET['q']); ?>
</textarea><span id="browser"><?php echo $_SESSION['db'] ?></span><button id="ok" accesskey="k" title="(k)" onclick="q.value=input.getCode(); submit();">OK</button>
<input type="hidden" name="q" id="q"/>
</form>
</body>
</html>
