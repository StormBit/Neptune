<?php
	/*
		Neptune Content Management System
		MySQL Database Driver - /system/drivers/mysql.php

		This file does some extremely basic database abstraction.
	*/
	
	$NeptuneCore->parseconf('system/config/mysql.php');
	
	class NeptuneSQL {
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
			
			
			
			$status = @mysql_connect($host,$user,$pass);
			
			if (!$status) {
        $NeptuneCore->fatal_error("Could not connect to the database because the following error occurred:</p><p style='font-family:monospace;'>" . mysql_error());
      }
      
			$status = mysql_select_db($database);
			
			if (!$status) {
        $NeptuneCore->fatal_error("A database error occurred:</p><p style='font-family:monospace;'>" . mysql_error());
      }
			
			return 0;
		}
		
		
		// MySQL Query
		function query($query) {
			global $NeptuneCore;
			global $Neptune;
			
			$NeptuneCore->var_set("system","querycount",$NeptuneCore->var_get("system","querycount") + 1);
			
			$sql = mysql_query($query);
			
			if (mysql_error() != "" && mysql_error() != $NeptuneCore->var_get("database","lasterror")) {
        $NeptuneCore->alert("A database error occurred: " . mysql_error(), "warning");
        $NeptuneCore->var_set("database","lasterror",mysql_error());
			}
			
			return $sql;
		}
		
		// MySQL Fetch Array
		function fetch_array($sql) {
			global $NeptuneCore;
			global $Neptune;
			
			$res = @mysql_fetch_array($sql);
			
			if (mysql_error() != "" && mysql_error() != $NeptuneCore->var_get("database","lasterror")) {
        $NeptuneCore->alert("A database error occurred: " . mysql_error(), "warning");
        $NeptuneCore->var_set("database","lasterror",mysql_error());
			}
			
			return $res;
		}
		
		// MySQL Real Escape String
		function escape_string($string) {
			global $NeptuneCore;
			global $Neptune;
			
			$result = mysql_real_escape_string($string);
			
			if (mysql_error() != "" && mysql_error() != $NeptuneCore->var_get("database","lasterror")) {
        $NeptuneCore->alert("A database error occurred: " . mysql_error(), "warning");
        $NeptuneCore->var_set("database","lasterror",mysql_error());
			}
			
			return $result;
		}
		
		// MySQL Row Count
		function num_rows($sql) {
			global $NeptuneCore;
			global $Neptune;
			
			$rows = mysql_num_rows($sql);
			
			if (mysql_error() != "" && mysql_error() != $NeptuneCore->var_get("database","lasterror")) {
        $NeptuneCore->alert("A database error occurred: " . mysql_error(), "warning");
        $NeptuneCore->var_set("database","lasterror",mysql_error());
			}
			
			return $rows;
		}
	}
?>