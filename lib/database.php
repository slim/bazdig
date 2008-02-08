<?php
	define('WARAQ_ROOT', '..');
	require_once WARAQ_ROOT .'/ini.php';

	require_once "code.php";

	class BDB extends PDO
	{
		var $type, $name, $host, $user, $password;

		function __construct($dsn, $username = NULL, $password = NULL)
		{
			$this->user 	= $username;
			$this->password = $password;
			if (is_array($dsn)) {
				$dbconfig = $dsn;
				$this->type 	= $dbconfig['type'];
				$this->name 	= $dbconfig['name'];
				$this->host 	= $dbconfig['host'];
				$dsn = $this->getDsn();
			}
			parent::__construct($dsn,$username, $password);
		}

		function getDsn()
		{
			$dsn = $this->type .':';
			if ("sqlite" == $this->type or "sqlite2" == $this->type) {
				$dsn .= $this->name; 
			} else {
				$dsn .= 'host='. $this->host;
				$dsn .= ';dbname='. $this->name;
			}
			return $dsn;
		}

		function httpGet($localResource)
		{
			$queryString  = "dbt=". $this->type;
			$queryString .= "&dbn=". $this->name;
			$queryString .= "&dbh=". $this->host;
			$queryString .= "&dbu=". $this->user;
			$queryString .= "&dbp=". $this->password;
			return file_get_contents($localResource->url .'?'. $queryString);
		}

		function listTables()
		{
			$dbType = $this->getAttribute(PDO::ATTR_DRIVER_NAME);
			if ('sqlite' == $dbType || 'sqlite2' == $dbType) {
				$query = "select name from sqlite_master where type='table'";
			} else {
				$query = "show tables";
			}
			$result = $this->query($query);
			$tables = array();
			foreach ($result as $r) {
				$tables []= new Table($r[0]);
			}

			return $tables;
		}
	}

	class Table
	{
		var $name;
		var $columns;

		function __construct($name, $columns = array())
		{
			$this->name = $name;
			$this->columns = $columns;
		}

		function loadColumns($db)
		{
			$this->columns = array();
			$table = $this->name;
			$dbType = $db->getAttribute(PDO::ATTR_DRIVER_NAME);
			if ('sqlite' == $dbType || 'sqlite2' == $dbType) {
				$query = "select sql from sqlite_master where type='table' and name='$table'";
				$row = $db->query($query)->fetch();
				$createQuery = new SqlCode($row['sql']);
				$this->columns = $createQuery->extractColumns();
			} else {
				$query = "show columns from $table";
				$result = $db->query($query);
				foreach ($result as $column) {
					$this->columns []= new Column($column['Field'], $column['Type']);
				}
			}

			return $this->columns;
		}
	}


	class Column
	{
		var $name;
		var $type;

		function __construct($name, $type = NULL)
		{
			$this->name = $name;
			$this->type = $type;
		}
	}

function columnNames($row)
	$names = array();
	if (is_array($row)) $names = array_keys($row);
	return $names;
}

