<?php
	define('WARAQ_ROOT', '..');
	require_once WARAQ_ROOT .'/ini.php';

	require_once "DB.php";

	class Query
	{
		var $string;
		var $tables;
		var $conditions;
		var $data;

		function Query()
		{
		}

		function db($dsn)
		{
			static $db;

			$db = DB::Connect($dsn); 
			$this->data = $db->getAll($this->string);
		}

		function select()
		{
			$this->string = "select";
		}

		function from($tables)
		{
			$this->tables = $tables;
			$this->string .= " ". implode(',', $tables) ." ";
		}

		function where($conditions)
		{
			$this->conditions = $conditions;
			foreach ($conditions as $column => $value) {
			}
		}
	}

	class DataBase extends DB
	{
		var $db;

		function DataBase($dsn)
		{
			$this->db = DB::Connect($dsn);
		}

		function insert($table, $data)
		{
			$query = "insert $table set ". DataBase::serialize_data($data);
			return $this->query($query);
		}

		function select($data)
		{
			$tables = DataBase::extract_tables($data);
			$query  = "select * from ". implode(',', $tables);
			$query .= " where ". DataBase::serialize_data($data, 'and');
			return $this->query($query);
		}

		function serialize_data($data, $separator = ',')
		{
			$tuples = array();
			foreach ($data as $key => $value) {
				$tuples[] = " $key='$value' ";
			}
			return implode(" $separator ", $tuples);
		}

		function extract_tables($data)
		{
			$tables = array();
			foreach (array_keys($data) as $column) {
				list($tableName, $columnName) = split('.', $column);
				$tables[] = $tableName;
			}
			return $tables;
		}
	}
