<?php
	/*
		Neptune Content Management System
		Index File - /index.php

		This file is basically a loader for the rest of the Neptune CMS. It
		loads all of the core functions, loads the configuration, initializes
		the Neptune CMS, loads and initializes all modules, runs any modules
		or functions required to satisfy the request, then passes the output
		to the templating engine.
	*/

	global $starttime;
	
	$time=microtime();
	$starttime=substr($time,11).substr($time,1,9);
	
	date_default_timezone_set('America/Los_Angeles');
	
	// Create the global array that will be used in many system functions to
	// store state data.
	global $Neptune, $NeptuneCore, $NeptuneSQL, $NeptuneAdmin;
	
	// Load Classes:
	// * Core
	require_once('system/core/main.php');
	if(!isset($NeptuneCore)) {
		$NeptuneCore = new NeptuneCore();
	}
	// * Database
	if (file_exists("system/drivers/" . $NeptuneCore->var_get("database","type") . ".php")) {
		require_once("system/drivers/" . $NeptuneCore->var_get("database","type") . ".php");

	} else {
		require_once("system/drivers/null.php");
	}
	if(!isset($NeptuneSQL)) {
		$NeptuneSQL = new NeptuneSQL();
	}
	// * Admin Panel
	require_once('system/core/acpcore.php');
	if (!isset($NeptuneAdmin)) {
			$NeptuneAdmin = new NeptuneAdmin();
	}
	
	// Load essential system modules...
	if ($handle = opendir('system/modules')) { 
		while (false !== ($file = readdir($handle))) { 
			if ($file != "." && $file != ".." && !is_dir("system/modules/" . $file)) { 
				include_once("system/modules/$file"); 
			} 
		} 
		closedir($handle); 
	}	
	// ...followed by loading user modules.
	if ($handle = opendir('modules')) { 
		while (false !== ($file = readdir($handle))) { 
			if ($file != "." && $file != ".." && !is_dir("modules/" . $file)) { 
				include_once("modules/$file"); 
			} 
		} 
		closedir($handle); 
	}	
	
	// Run whatever function is hooked to the current request.
	$NeptuneCore->hook_run($NeptuneCore->var_get("system","query"));
	
	$NeptuneCore->display();
?>