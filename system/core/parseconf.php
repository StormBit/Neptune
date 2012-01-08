<?php
	/*
		Neptune Content Management System
		Configuration File: Parser - /system/core/parseconf.php

		This is a "parser" that takes arrays set in config files,
		and sets them as variables in the $Neptune array.
	*/

	// Checking if NepNep is defined, for security purposes.	
	if(!defined('NepNep')) {
		die('NO U');
	}
	
	// No need to overdeclare NeptuneCore if it's already set
	if(!isset($NeptuneCore)) {
		$NeptuneCore = new NeptuneCore();
	}

	function parseconf($config) {
		global $NeptuneCore;
		
		include($config);
			foreach($conf as $group => $variable) {
			foreach($variable as $variable => $data) {
				// Calling var_set
				$NeptuneCore->var_set($group,$variable,$data);
				unset($variable);
				unset($data);
			}
			unset($group);
			unset($variable);
		}
		unset($conf);
	}
	
?>