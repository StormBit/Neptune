<?php
	/*
		Neptune Content Management System
		Core System File: Main Functions - /system/core/main.php

		This file contains all of the most basic functions used throughout the
		rest of the Neptune CMS. It contains code for printing output, setting
		and retrieving variables, and other core operations.
	*/

	// Checking if NepNep is defined, for security purposes.
	if(!defined('NepNep')) {
		die('NO U');
	}
	
	class NeptuneCore {
		function __construct() {
			$this->var_set("system","querycount",0);
			
			// Loading the rest of the core files. 
			require_once('system/core/bbcode.php');
			require_once("system/core/tidy.php");
			$this->parseconf('system/config/core.php');

			require_once("system/core/useraccounts.php");

			if($this->var_get('cache', 'enabled')) {
				require_once('cache.php');
			}
			
			// After this, we will take the query string and extract all of the data
			// from it. This is intentionally done in a way that makes $_GET impossible
			// to use. We want to keep all URLs clean.

			// Prevent PHP from displaying a warning if there is no query string.
			if (isset($_SERVER["QUERY_STRING"]) && !empty($_SERVER["QUERY_STRING"])) {
				// Take each part of the query string, and split it into an array. The
				// first value (0) is how functions hook themselves to requests.
				$this->var_set("system","query",explode("/",$_SERVER["QUERY_STRING"]));
			} else {
				// If there is no query string, use the default function hook instead.
				$this->var_set("system","query",array($this->var_get("config","defaultact")));
			}
				
			// Enumerate modules. 
			if ($handle = opendir('modules')) { 
				while (false !== ($dir = readdir($handle))) { 
					if ($dir != "." && $dir != ".." && is_dir("modules/" . $dir)) { 
						include("modules/$dir/module.php"); 
					} 
				} 
				closedir($handle); 
			}
		}
		
		// Variable setting function: This allows functions to store variables that
		// will be required by later functions. It is prefered to use this instead
		// of custom global variables.
		function var_set($group,$variable,$data) {
			global $Neptune;
						
			$Neptune["stack"][$group][$variable] = $data;

			return 0;
		}

		// Variable append: Does the same as the above, but appends.
		function var_append($group,$variable,$data) {
			global $Neptune;
			
			if (!isset($Neptune["stack"][$group])) {
				$Neptune["stack"][$group] = array();
			}
			if (!isset($Neptune["stack"][$group][$variable])) {
				$Neptune["stack"][$group][$variable] = "";
			}
			
			$Neptune["stack"][$group][$variable] = $Neptune["stack"][$group][$variable] . $data;

			return 0;
		}
		
		// Variable retrieval function: This allows functions to fetch variables
		// that were set by neptune_var_set().
		function var_get($group,$variable) {
			global $Neptune;
			
			if (isset($group) && isset($variable)) {
				if (isset($Neptune["stack"][$group][$variable])) {
					return $Neptune["stack"][$group][$variable];
				} else {
					return NULL;
				}
			}
			else {
				return NULL;
			}
		}

		
		function neptune_echo($text) {
			$this->var_append("output","body",$text);
		}
		
		function neptune_echo_bbcode($text) {
			$this->var_append("output","body",neptune_bbcode($text));
		}
		
		function neptune_title($text) {
			$this->var_set("output","title",$text);
		}
		

		// Function Hooker: This function is what allows other modules to bind to
		// certain query strings. For example, it allows a blog module to bind to
		// requests starting with "?post".
		function hook_function($action,$moduleid,$modulefunction) {
			global $Neptune;

			$this->var_set("hooks",$action,"mod_" . $moduleid . "_" . $modulefunction);
		}

		// Hooked function run: This function runs a hooked function.
		function hook_run($action) {
			global $Neptune;

			$Function = $this->var_get("hooks",$action[0]);

			if ($Function != "") {
				$Function();
			} else {
				die("Invalid function");
			}
		}
		
		function parseconf($config) {
			include($config);
				foreach($conf as $group => $variable) {
				foreach($variable as $variable => $data) {
					// Calling var_set
					$this->var_set($group,$variable,$data);
					unset($variable);
					unset($data);
				}
				unset($group);
				unset($variable);
			}
			unset($conf);
		}
	}
?>