<?php
	/*
		Neptune Content Management System
		No-Database Driver - /system/drivers/none.php

		Provides empty functions to prevent errors due to missing functions when running Neptune in a database-less environment.
	*/
		
	class NeptuneSQL {
		public function __construct() {
		}
		
		static function type() {
			return "NeptuneNoDatabase";
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
		
		// NullNumRows
		function num_rows($sql) {
			return 0;
		}
	}
?>