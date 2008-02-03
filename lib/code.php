<?php

	require_once "persistance.php";
	require_once "database.php";

	class SqlCode implements sql, persistance
	{
		static $db;
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
			$code  = str_replace("'", "''",$this->code);
			$date  = $this->date;
			$query = "insert into $table (id, code, date) values ('$id', '$code', '$date')";
			return $query;
		}

		static function set_db($db)
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
			self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			return self::$db;
		}
		
		static function select($options = NULL, $db = NULL)
		{
			if (!$db) $db =& self::$db;
			$query = self::sql_select($options);
			$result = $db->query($query);
			$scripts = array();
			foreach ($result as $r) {
				$s       = new SqlCode($r['code']);
				$s->id   = $r['id'];
				$s->date = $r['date'];
				$scripts []= $s;
			}
			return $scripts;
		}

		function save($db = NULL)
		{
			if (!$db) $db =& self::$db;
			$query = $this->toSQLinsert();
			$id = $this->id;
			$table = self::get_table_name();
			try {
				$db->exec($query);
			} catch (PDOException $e) {
				if ($db->errorCode() == "23000") { // l'id existe dans la table
					$db->exec("delete from $table where id='$id'");
					$db->exec($query);
				} else {
					die($e->getMessage());
				}
			}
			return $this;
		}

		static function search($string, $options = NULL)
		{
			$options = str_replace("where", "and", $options);
			$scripts = self::select("where code like '%$string%' $options");
			return $scripts;
		}

		function exec($db)
		{
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$result = $db->query($this->code);
			return $result;
		}

		function extractColumns()
		{
			eregi('create +table +[^(]+\(([^)]+)', $this->code, $strings);
			$columns = split(',', $strings[1]);
			for ($i=0; $i < count($columns); $i++) {
				$c = new Column(firstWord($columns[$i]));
				$columns[$i] = $c; 
			}
			return $columns;
		}
	}
