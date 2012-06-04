<?php
	/*
		Neptune Content Management System
		SQLite3 Database Driver - /system/drivers/sqlite3.php

		This file does some extremely basic database abstraction.
	*/
		
	class NeptuneSQL {
		public function __construct() {
			global $NeptuneCore;
			global $NeptuneSQL;
			global $database;
			
			$database = new SQLite3("Neptune.sqlite3");
		}
		
		static function type() {
			return "SQLite3";
		}
		
		// SQLite3 Query
		function query($query) {
			global $NeptuneCore;
			global $NeptuneSQL;
			global $database;
			
			$NeptuneCore->var_set("system","querycount",$NeptuneCore->var_get("system","querycount") + 1);
			
			$query = str_replace("`","",$query);
			$query = str_replace("\cx","`",$query);
				
			$result = $database->query($query);
			return $result;
			
		}
		
		// SQLite3 Fetch Array
		function fetch_array($result) {
			global $NeptuneCore;
			global $Neptune;
			
			if ($result) {
				$row = $result->fetchArray(SQLITE3_BOTH);
				return $row;
			} else {
				return false;
			}
		}
		
		// SQLite3 Escape String
		function escape_string($string) {
			global $NeptuneCore;
			global $Neptune;
			global $database;

			$string = str_replace("`","\cx",$string);
			return $database->escapeString($string);
		}
	}
?>