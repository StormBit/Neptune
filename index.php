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
	
	// Load the core class file
	require_once('system/core/main.php');
	
	// Making the core objects accessable
	global $NeptuneCore;
	global $NeptuneSQL;
	if(!isset($NeptuneCore)) {
		$NeptuneCore = new NeptuneCore();
	}

	include("system/drivers/" . $NeptuneCore->var_get("database","type") . ".php");
	
	// Include the code for the Admin Control Panel. 
	require_once('system/core/acpcore.php');
	
	if (!isset($NeptuneAdmin)) {
			$NeptuneAdmin = new NeptuneAdmin();
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