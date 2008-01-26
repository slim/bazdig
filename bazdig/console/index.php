<html>
<head>
<title>bazdig</title>
<script src="../codepress/codepress.js" type="text/javascript"></script>
</head>
<body>
<form method="get" action="../sql/exec/" target="_new">
<textarea id="input" name="input" class="codepress sql linenumbers-off" style="width:700px;height:350px;" wrap="off" tabindex="1">
<?php echo $_GET['q']; ?>
</textarea>
<input type="hidden" name="q" id="q"/>
<button accesskey="g" title="SHIFT-ALT-g" onclick="q.value=input.getCode(); submit();">OK</button>
</form>
<p><a href="../history/">history</a> | <a href="../db/">database</a> | <a href="../bazdig.db">save</a></p>
</body>
</html>
