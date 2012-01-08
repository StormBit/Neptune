<?php
	/*
		Neptune Content Management System
		MySQL Database Driver - /system/drivers/mysql.php

		This file does some extremely basic database abstraction.
	*/
	
	// Checking if NepNep is defined, for security purposes.	
	if(!defined('NepNep')) {
		die('NO U');
	}
	
	// MySQL Connect
	function neptune_connect($host,$user,$pass,$database) {
		global $NeptuneCore;
		global $Neptune;
		
		mysql_connect($host,$user,$pass);
		
		mysql_select_db($database);
		
		return 0;
	}
	
	
	// MySQL Query
	function neptune_query($query) {
		global $NeptuneCore;
		global $Neptune;
		
		$NeptuneCore->var_set("system","querycount",$NeptuneCore->var_get("system","querycount") + 1);
		
		return mysql_query($query);
	}
	
	// MySQL Fetch Array
	function neptune_fetch_array($sql) {
		global $NeptuneCore;
		global $Neptune;
		
		return mysql_fetch_array($sql);
	}
	
	// MySQL Real Escape String
	function neptune_escape_string($string) {
		global $NeptuneCore;
		global $Neptune;
		
		return mysql_real_escape_string($string);
	}
?>