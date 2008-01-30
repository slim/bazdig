<?php
	define('WARAQ_ROOT', '..');
	require_once WARAQ_ROOT .'/ini.php';

	class BDB extends PDO
	{
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
				$tables []= new Table($r['name']);
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
			$table = $this->name;
			$dbType = $db->getAttribute(PDO::ATTR_DRIVER_NAME);
			if ('sqlite' == $dbType || 'sqlite2' == $dbType) {
				$result = $db->fetchColumnTypes($table);
				foreach ($result as $column => $type) {
					$this->columns []= new Column($column, $type);
				}
			} else {
				$columns = $db->query("show columns from $table");
			}
			
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
