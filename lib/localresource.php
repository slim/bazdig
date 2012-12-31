<?php
	class LocalResource
	{
		var $url;
		var $file;

		function __construct($url, $file)
		{
			$this->url = absolutize($url);
			$this->file = absolutize($file);
		}

		function get($path)
		{
			$f = $this->file .'/'. $path;
			if (strpos($this->url, '?')) {
				$u = str_replace('?', "/$path?", $this->url	);
			} else if (strpos($this->url, '#')) {
				$u = str_replace('#', "/$path#", $this->url	);
			} else {
				$u = $this->url .'/'. $path;
			}
			$r = new LocalResource($u, $f);
			return $r;
		}

		function base()
		{
			$f = preg_replace('/\/[^\/]+$/', '/', $this->file);
			$u = preg_replace('/\/[^\/]+$/', '/', $this->url);
			$r = new LocalResource($u, $f);

			return $r;
		}

		function get_file()
		{
			return $this->file;
		}

		function get_url()
		{
			return $this->url;
		}
	}

	function absolutize($path)
	{
		$path = preg_replace('/\/\.\//', '/', $path); 
		$path = preg_replace('/\/([^:])\/\/+/', '\1/', $path);
		while (preg_match('/\/[^\/]+\/+\.\.\/+/', $path)) {
			$path = preg_replace('/\/[^\/]+\/+\.\.\/*/', '/', $path);
		}

		return $path;
	}
