<?php
	/*
		Neptune Content Management System
		PostgreSQL Database Driver - /system/drivers/pgsql.php

		This file does some extremely basic database abstraction.
	*/
	
	$NeptuneCore->parseconf('system/config/pgsql.php');
	
	class NeptuneSQL {
		public function __construct() {
			global $NeptuneCore;
			global $NeptuneSQL;
			
			$this->connect($NeptuneCore->var_get("database","host"),$NeptuneCore->var_get("database","user"),$NeptuneCore->var_get("database","pass"),$NeptuneCore->var_get("database","db"));
		}
		
		static function type() {
			return "PostgreSQL";
		}
		
		// PostgreSQL Connect
		function connect($host,$user,$pass,$database) {
			global $NeptuneCore;
			global $Neptune;
			
      pg_connect("host=$host port=5432 dbname=$database user=$user password=$pass");
			
			return 0;
		}
		
		
		// PostgreSQL Query
		function query($query) {
			global $NeptuneCore;
			global $Neptune;
			
			$query = str_replace("`","",$query);
			$query = str_replace("\cx","`",$query);
			
			$NeptuneCore->var_set("system","querycount",$NeptuneCore->var_get("system","querycount") + 1);
			
			return pg_query($query);
		}
		
		// PostgreSQL Fetch Array
		function fetch_array($sql) {
			global $NeptuneCore;
			global $Neptune;
			
			return pg_fetch_array($sql);
		}
		
		// PostgreSQL Real Escape String
		function escape_string($string) {
			global $NeptuneCore;
			global $Neptune;
			
			return pg_escape_string($string);
		}
	}
?>