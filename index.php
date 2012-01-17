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

	$time=microtime();
	$starttime=substr($time,11).substr($time,1,9);
	
	date_default_timezone_set('America/Los_Angeles');
	
	// Defining NEPNEP for security purposes
	define('NEPNEP', true, true);
	
	// Create the global array that will be used in many system functions to
	// store state data.
	global $Neptune;
	global $NeptuneCore;
	global $NeptuneSQL;
	global $NeptuneAdmin;
	
	// Load the core class file
	require_once('system/core/main.php');
	if(!isset($NeptuneCore)) {
		$NeptuneCore = new NeptuneCore();
	}
	// Include the code for the Admin Control Panel. 
	require_once("system/drivers/" . $NeptuneCore->var_get("database","type") . ".php");
	if(!isset($NeptuneSQL)) {
		$NeptuneSQL = new NeptuneSQL();
	}
	require_once('system/core/acpcore.php');
	if (!isset($NeptuneAdmin)) {
			$NeptuneAdmin = new NeptuneAdmin();
	}

	
	// Enumerate modules. 
	if ($handle = opendir('modules')) { 
		while (false !== ($dir = readdir($handle))) { 
			if ($dir != "." && $dir != ".." && is_dir("modules/" . $dir)) { 
				include_once("modules/$dir/module.php"); 
			} 
		} 
		closedir($handle); 
	}	

	
	// Run whatever function is hooked to the current request.
	$NeptuneCore->hook_run($NeptuneCore->var_get("system","query"));
	
	if ($NeptuneCore->var_get("output","body") != "") {
		$NeptuneCore->var_set("output","body", clean_html_code($NeptuneCore->var_get("output","body")));
	} else {
		$NeptuneCore->var_set("output","body","");
	}
	
	require("theme/layout.php");
?>