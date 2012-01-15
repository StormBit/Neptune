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
	}
?>