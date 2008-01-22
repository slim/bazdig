<?php

	require "persistance.php";

	class SqlCode implements sql
	{
		var $id;
		var $code;
		var $date;

		function __construct($code)
		{
			$this->code = $code;
			$this->date = date('c');
			$this->id = 'md5:'. md5($this->code);
		}

		public static function get_table_name()
		{
			return "sql";
		}

		public static function sql_select($options = NULL)
		{
			$table = self::get_table_name();
			$query = "select id, code, date from $table $options";
			return $query;
		}

		function toSQLselect()
		{
			$table = $this->get_table_name();
			$id    = $this->id;
			$query = "select id, code, date from $table where id='$id'";
			return $query;
		}

		function toSQLinsert()
		{
			$table = $this->get_table_name();
			$id    = $this->id;
			$code  = $this->code;
			$date  = $this->date;
			$query = "insert into $table set id='$id', code='$code', date='$date'";
			return $query;
		}
	}
