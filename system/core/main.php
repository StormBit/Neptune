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
		// Variable setting function: This allows functions to store variables that
		// will be required by later functions. It is prefered to use this instead
		// of custom global variables.
		function var_set($group,$variable,$data) {
			global $Neptune;
			$Neptune["stack"][$group][$variable] = $data;

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

			$Function = $this->var_get("hooks",$action);

			if ($Function != "") {
				$Function();
			} else {
				die("Invalid function");
			}
		}
		
	}
?>