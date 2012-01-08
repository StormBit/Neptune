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
	
	
	// Create the global array that will be used in many system functions to
	// store state data. 
	global $Neptune;
	
	
	// Load the files containing all of the functions required to bootstrap the
	// rest of the Neptune CMS. 
	require_once("system/core/main.php");
	
	
	// This will load the core configuration file. 
	require_once("system/config/core.php");
	
	
	// After this, we will take the query string and extract all of the data
	// from it. This is intentionally done in a way that makes $_GET impossible
	// to use. We want to keep all URLs clean. 
	
	// Prevent PHP from displaying a warning if there is no query string. 
	if (isset($_SERVER["QUERY_STRING"])) {
		// Take each part of the query string, and split it into an array. The
		// first value (0) is how functions hook themselves to requests.
		neptune_var_set("system","query",explode("/",$_SERVER["QUERY_STRING"]));
	} else {
		// If there is no query string, use the default function hook instead. 
		neptune_var_set("system","query",neptune_var_get("config","defaultact"));
	}
	
	// Run whatever function is hooked to the current request. 
	neptune_hook_run(neptune_var_get("system","query"));	
?>