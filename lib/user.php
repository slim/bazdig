<?php
	class User
	{
		var $id;
		var $password;

		function User($id = NULL)
		{
			if (!$id) {
			}
		}

		function session_start()
		{
		}

		function has_right($right=NULL)
		{
			
			if (!$right) {
			}
			
			$user = new User
			$rightQuery = new Query;
			$rightQuery->select("id")->from("users");
			$rightQuery->where(array('id' => $this->id, 'password' => $this->password));
		}
	}
