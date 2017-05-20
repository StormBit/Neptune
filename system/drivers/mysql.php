<?php
	/*
		Neptune Content Management System
		MySQL Database Driver - /system/drivers/mysql.php

		This file does some extremely basic database abstraction.
	*/

	$NeptuneCore->parseconf('system/config/mysql.php');

	class NeptuneSQL {
		public $Connection;

		public function __construct() {
			global $NeptuneCore;
			global $NeptuneSQL;

			$this->connect($NeptuneCore->var_get("database","host"),$NeptuneCore->var_get("database","user"),$NeptuneCore->var_get("database","pass"),$NeptuneCore->var_get("database","db"));

			$NeptuneCore->var_set("database","lasterror","");
		}

		static function type() {
			return "MySQL";
		}

		// MySQL Connect
		function connect($host,$user,$pass,$database) {
			global $NeptuneCore;
			global $Neptune;



			$this->Connection = @mysqli_connect($host,$user,$pass);

			if (!$this->Connection) {
				$NeptuneCore->fatal_error("Could not connect to the database because the following error occurred:</p><p style='font-family:monospace;'>" . mysqli_connect_error());
			}

			$status = mysqli_select_db($this->Connection, $database);

			if (!$status) {
				$NeptuneCore->fatal_error("A database error occurred:</p><p style='font-family:monospace;'>" . mysqli_error($this->Connection));
			}

			return 0;
		}


		// MySQL Query
		function query($query) {
			global $NeptuneCore;
			global $Neptune;

			$NeptuneCore->var_set("system","querycount",$NeptuneCore->var_get("system","querycount") + 1);

			$sql = mysqli_query($this->Connection, $query);

			if (mysqli_error($this->Connection) != "" && mysqli_error($this->Connection) != $NeptuneCore->var_get("database","lasterror")) {
				$NeptuneCore->alert("A database error occurred: " . mysqli_error($this->Connection), "warning");
				$NeptuneCore->var_set("database","lasterror",mysqli_error($this->Connection));
			}

			return $sql;
		}

		// MySQL Fetch Array
		function fetch_array($sql) {
			global $NeptuneCore;
			global $Neptune;

			$res = @mysqli_fetch_array($sql);

			if (mysqli_error($this->Connection) != "" && mysqli_error($this->Connection) != $NeptuneCore->var_get("database","lasterror")) {
				$NeptuneCore->alert("A database error occurred: " . mysqli_error($this->Connection), "warning");
				$NeptuneCore->var_set("database","lasterror",mysqli_error($this->Connection));
			}

			return $res;
		}

		// MySQL Real Escape String
		function escape_string($string) {
			global $NeptuneCore;
			global $Neptune;

			$result = mysqli_real_escape_string($this->Connection, $string);

			if (mysqli_error($this->Connection) != "" && mysqli_error($this->Connection) != $NeptuneCore->var_get("database","lasterror")) {
				$NeptuneCore->alert("A database error occurred: " . mysqli_error($this->Connection), "warning");
				$NeptuneCore->var_set("database","lasterror",mysqli_error($this->Connection));
			}

			return $result;
		}

		// MySQL Row Count
		function num_rows($sql) {
			global $NeptuneCore;
			global $Neptune;

			$rows = mysqli_num_rows($this->Connection, $sql);

			if (mysqli_error($this->Connection) != "" && mysqli_error($this->Connection) != $NeptuneCore->var_get("database","lasterror")) {
				$NeptuneCore->alert("A database error occurred: " . mysqli_error($this->Connection), "warning");
				$NeptuneCore->var_set("database","lasterror",mysqli_error($this->Connection));
			}

			return $rows;
		}
	}
?>
