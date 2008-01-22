<?php

	interface persistance
	{
		public static function set_db($db);
		public static function select($wildcards = NULL, $db = NULL);
		public function save($db = NULL);
		public function load($db = NULL);
	}

	interface sql
	{
		public function toSQLselect();
		public function toSQLinsert();
		public static function sql_select($wildcards = NULL);
		public static function get_table_name();
	}	

