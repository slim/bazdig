<?php
	define(MAX_SELECTABLE_PAGES, 10);

	class PageSelector 
	{
		function __construct($baseUrl, $itemsPerPage, $itemsCount)
		{
			settype($this->pageCount , "integer");
			settype($itemsCount, "integer");
			settype($itemsPerPage, "integer");

			$this->baseUrl = $baseUrl;
			$this->pageCount = $itemsCount / $itemsPerPage;
		}

		function getLinks($page_number)
		{
			settype($page_number, "integer");
			settype($p, "integer");
			settype($first, "integer");
			settype($last, "integer");

			$links = array();
			$first = $page_number - (MAX_SELECTABLE_PAGES / 2);
			$first = $first  > 0 ? $first : 1 ;
			$last  = $first + MAX_SELECTABLE_PAGES;
			$last  = $last < $this->pageCount ? $last : $this->pageCount;

			if ($page_number > 1) {
				$links['previous'] = $this->baseUrl . ($page_number - 1);
			}
			for ($p=$first; $p <= $last; $p++) {
				if ($p == $page_number) {
					$links[$p] = '#';
				} else {
					$links[$p] = $this->baseUrl . $p;
				}
			}
			if ($page_number < $this->pageCount) {
				$links['next'] = $this->baseUrl . ($page_number + 1);
			}

			return $links;
		}
}
