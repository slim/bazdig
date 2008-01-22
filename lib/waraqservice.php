<?php
	require_once "localresource.php";

	class WaraqService extends LocalResource
	{
		private $params;

		function __construct($url, $file = NULL)
		{
			if (! $file) {
				$file = realpath(WARAQ_ROOT);
			}
			parent::__construct($url, $file);
		}

		function getparam($n)
		{
			if (isset($this->params[$n])) {
				return $this->params[$n];
			} else {
				return parent::get($n);
			}
		}

		function setparam($n, $v)
		{
			$this->params[$n] = $v;
			return $this;
		}
	}
?>
