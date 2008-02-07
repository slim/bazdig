<?php
	require_once "localresource.php";

	class PageCopy extends LocalResource
	{
		var $date;
		var $origin;
		var $endOfHTML = '';
		var $startOfHEAD = '';
		var $endOfHEAD = '';

		function __construct($name, $origin, $root)
		{
			$this->date = date("c");
			$this->origin = $origin;
			$c = $root->get($name);
			$this->file = $c->get_file();
			$this->url  = $c->get_url();
		}

		function update()
		{
			$ch = curl_init();
			$timeout = 5; // set to zero for no timeout
			curl_setopt ($ch, CURLOPT_URL, $this->origin);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			$file_contents = curl_exec($ch);
			curl_close($ch);

			if ($content = $file_contents) {

				if (eregi("<head>", $content)) {
					$content = eregi_replace("<head>", "<head>". $this->startOfHEAD , $content);
				} else {
					$content = eregi_replace("^", "<head>". $this->startOfHEAD , $content);
				}
				if (eregi("</head>", $content)) {
					$content = eregi_replace("</head>", $this->endOfHEAD . "</head>", $content);
				} else {
					$content = eregi_replace("^", $this->endOfHEAD . "</head>", $content);
				}	
				if (eregi("</html>", $content)) {
					$content = eregi_replace("</html>", $this->endOfHTML . "</html>", $content);
				} else { 
					$content = eregi_replace("$", $this->endOfHTML . "</html>", $content);
				}
				$copy = fopen($this->file, "w");
				if (! fwrite($copy, $content)) {
					throw new Exception("Err: Ecriture");
				}
			}
		}

		function add_to_html($s)
		{
			$this->endOfHTML .= $s;
		}

		function add_to_head($s)
		{
			$this->endOfHEAD .= $s;
		}

		function add_to_head_start($s)
		{
			$this->startOfHEAD .= $s;
		}
	}

	function url2fileName($url)
	{
		$fileName = $url;
		$forbidden = array('/',':','?','=','&','+','%');
		$fileName = ereg_replace('^http://', '', $fileName); //remove http://
		$fileName = ereg_replace('#.*$', '', $fileName); //remove fragment id
		$fileName = str_replace($forbidden, '_', $fileName);

		return $fileName;
	}
