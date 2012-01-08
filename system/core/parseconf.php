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
	
	$NeptuneCore = new NeptuneCore();
	
	if(!isset($conffile)) {
		$conffile = ('../config/core.php');
	}
	
	function config_parse($conffile) {
		global $NeptuneCore;
		
		require_once($conffile);
	
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
	}
	
?>