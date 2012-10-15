<?php
	/*
		Neptune Content Management System
		Null Database Driver - /system/drivers/null.php

		Provides empty functions to prevent errors due to missing functions.
	*/
		
	class NeptuneSQL {
		public function __construct() {
		}
		
		static function type() {
			return "NeptuneNullDatabase";
		}
		
		// NullQuery
		function query($query) {
			return false;
		}
		
		// NullFetchArray
		function fetch_array($result) {
			return false;
		}
		
		// NullEscapeString
		function escape_string($string) {
			return $string;
		}
		
		//NullNumRows
		function num_rows($sql) {
			return 0;
		}
	}
	
	$NeptuneCore->var_set("config","sitename","Neptune CMS");
	$NeptuneCore->var_set("config","defaultact","install");
	$NeptuneCore->var_set("system","query",array($NeptuneCore->var_get("config","defaultact")));
	require_once("system/install/install.php");
?>