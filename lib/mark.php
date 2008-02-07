<?php
	require_once 'persistance.php';

	class Mark implements sql, persistance
	{
		static $db;

		var $id;
		var $creationDate;
		var $pageUrl;
		var $text;
		var $startNodePath, $startOffset;
		var $endNodePath, $endOffset;
		var $owner;

		function __construct($id = NULL)
		{
			$this->creationDate = time();
			if ($id) {
				$this->id = $id;
			}
		}

		static function get_table_name()
		{
			return "marks";
		}

		static function sql_select($wildcards = NULL)
		{
			$tableName = self::get_table_name();

			$order = ' order by creationDate desc, pageUrl ';
			if (array_key_exists('order', $wildcards)) {
				$order = " order by ". $wildcards['order'];
				unset($wildcards['order']);
			}

			$limit = '';
			if (array_key_exists('limit', $wildcards)) {
				$limit = " limit ". $wildcards['limit'];
				unset($wildcards['limit']);
			}
				
			if (empty($wildcards)) {
				return "SELECT id, creationDate, pageUrl, text, owner, startNodePath, startOffset, endNodePath, endOffset from $tableName $order $limit;";
			}

			$tuples = array();
			if (array_key_exists('pageUrl', $wildcards)) {
				$tuples []= " pageUrl LIKE '". $wildcards['pageUrl'] ."%'";
				unset($wildcards['pageUrl']);
			}

			foreach ($wildcards as $key => $value) {
				$tuples []= "$key='$value'";
			}

			$conditions = implode(' and ', $tuples);
			
			$query = "SELECT id, creationDate, pageUrl, text, owner, startNodePath, startOffset, endNodePath, endOffset from $tableName where $conditions $order $limit;";

			return $query;
		}

		function toHTMLanchor()
		{
			$anchor = "<a href='". $this->pageUrl ."' >". $this->text ."</a>";
			return $anchor;
		}

		function toSQLinsert()
		{
			$id            = $this->id;
			$creationDate  = date('c', $this->creationDate);
			$pageUrl       = $this->pageUrl;
			$text          = $this->text;
			$owner         = $this->owner;
			$startNodePath = $this->startNodePath;
			$startOffset   = $this->startOffset;
			$endNodePath   = $this->endNodePath;
			$endOffset     = $this->endOffset;

			$tableName = Mark::get_table_name();
			return "INSERT into $tableName (id, creationDate, pageUrl, text, owner, startNodePath, startOffset, endNodePath, endOffset) values ('$id', '$creationDate', '$pageUrl', '$text', '$owner', '$startNodePath', '$startOffset', '$endNodePath', '$endOffset');";
		}

		function toSQLselect()
		{
			return "SELECT creationDate, pageUrl, text, owner, startNodePath, startOffset, endNodePath, endOffset from marks where id='". $this->id ."';";
		}

		static function set_db($db, $user = "", $password = "")
		{
			if ($db instanceof PDO) {
				self::$db =& $db;
			} else {
				if (empty($user)) {
					self::$db =& new PDO($db);
				} else {
					self::$db =& new PDO($db, $user, $password);
				}
			}

			return self::$db;
		}

		static function count($wildcards = NULL, $db = NULL)
		{
			if ($db == NULL) {
				$db = self::$db;
			}
			if (! $db instanceof PDO) {
				throw new Exception("Not a Data Base");
			}

			$tableName = Mark::get_table_name();
			$query = "select count(*) from $tableName";	
			if ($result = $db->query($query)) {
				$result = $result->fetchAll();
			} else {
				echo "Query error";
				print_r($db->errorInfo());
				throw new Exception("Query error");
			}

			return $result[0][0];
		}

		static function select($wildcards = NULL, $db = NULL)
		{
			if ($db == NULL) {
				$db = self::$db;
			}
			if (! $db instanceof PDO) {
				throw new Exception("Not a Data Base");
			}
			$marks = array();
			$query = self::sql_select($wildcards);
			if ($result = $db->query($query)) {
				$result = $result->fetchAll();
			} else {
				echo "Query error";
				print_r($db->errorInfo());
				throw new Exception("Query error");
			}
			foreach ($result as $r) {
				$mark = new Mark($r['id']);
				$mark->creationDate = strtotime($r['creationDate']);
				$mark->pageUrl = strip_http_get_params($r['pageUrl']);
				$mark->text = $r['text'];
				$mark->owner = $r['owner'];
				$mark->startNodePath = json_decode($r['startNodePath']);
				$mark->startOffset = $r['startOffset'];
				$mark->endNodePath = json_decode($r['endNodePath']);
				$mark->endOffset = $r['endOffset'];
				array_push( $marks, $mark);
			}

			return $marks;
		}

		function save($db = NULL)
		{
			if ($db == NULL) {
				$db = self::$db;
			}
			$query = $this->toSQLinsert();

			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$statement = $db->query($query);

			return $statement;
		}

		function load($db = NULL)
		{
			if ($db == NULL) {
				$db = self::$db;
			}
			if (is_string($db)) {
				$db = new PDO($db, $user, $pass);
			}
			$query  = $this->toSQLselect();
			if ($result = $db->query($query)) {
				$result = $result->fetch();
			} else {
				throw new Exception("Query error");
			}

			$this->creationDate = strtotime($result['creationDate']);
			$this->pageUrl = $result['pageUrl'];
			$this->text = $result['text'];
			$this->owner = $result['owner'];
			$this->startNodePath = $result['startNodePath'];
			$this->startOffset = $result['startOffset'];
			$this->endNodePath = $result['endNodePath'];
			$this->endOffset = $result['endOffset'];

			return $this;
		}

		function __toString()
		{
			return json_encode($this);
		}
	}

function strip_http_get_params($url)
{
	if (!$urlBaseLength = strpos($url, '?')) return $url;
	return substr($url, 0, $urlBaseLength);
}
