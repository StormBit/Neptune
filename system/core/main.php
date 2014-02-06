<?php
	/*
		Neptune Content Management System
		Core System File: Main Functions - /system/core/main.php

		This file contains all of the most basic functions used throughout the
		rest of the Neptune CMS. It contains code for printing output, setting
		and retrieving variables, and other core operations.
	*/
  
	class NeptuneCore {
		function __construct() {
			global $starttime;
			
			$time=microtime();
			$starttime=substr($time,11).substr($time,1,9);
			
			date_default_timezone_set('UTC');
			
			// Create the global arrays that will be used in many system functions to
			// store state data.
			global $Neptune, $NeptuneCore, $NeptuneSQL, $NeptuneAdmin;
			$NeptuneCore = $this;

			$this->register_module("neptune_core");
			
			$this->var_set("system","querycount",0);
			
			// Loading the rest of the core files. 
			require_once('system/core/bbcode.php');
			require_once('system/lib/truncateHtml.php');
			$this->parseconf('system/config/core.php');
			
			$this->parseconf('system/locale/' . $this->var_get("config","locale") . '.php');
			
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
				$this->var_set("system","query",explode("/",htmlspecialchars(urldecode($_SERVER["QUERY_STRING"]))));
			} else {
				// If there is no query string, use the default function hook instead.
				$this->var_set("system","query",array($this->var_get("config","defaultact")));
			}

			// Load up the database driver. If there is no driver specified, or the driver
			// cannot be found, instead load the Null database driver, which will redirect
			// the user to the Neptune Installer.
			if (file_exists("system/drivers/" . $this->var_get("database","type") . ".php")) {
				require_once("system/drivers/" . $this->var_get("database","type") . ".php");

			} else {
				require_once("system/drivers/null.php");
			}			

			// Initialize Neptune's ACP (Administrator Control Panel)
			require_once('system/core/acpcore.php');
			if (!isset($NeptuneAdmin)) {
					$NeptuneAdmin = new NeptuneAdmin();
			}

			// Load essential system modules...
			if (!$NeptuneCore->var_get("config","blacklist-system-modules")) {	
				if ($handle = opendir('system/modules')) { 
					while (false !== ($file = readdir($handle))) { 
						if ($file != "." && $file != ".." && !is_dir("system/modules/" . $file)) { 
							include_once("system/modules/$file"); 
						} 
					} 
					closedir($handle); 
				}	
			}
			// ...followed by loading user modules.
			if ($handle = opendir('modules')) { 
				while (false !== ($file = readdir($handle))) { 
					if ($file != "." && $file != ".." && !is_dir("modules/" . $file)) { 
						include_once("modules/$file"); 
					} else if ($file != "." && $file != ".." && is_dir("modules/" . $file)) {
						if ($subhandle = opendir("modules/$file")) { 
							while (false !== ($subfile = readdir($subhandle))) { 
								if ($subfile != "." && $subfile != ".." && is_dir("modules/" . $file . "/" . $subfile)) {
									include_once("modules/$file/$subfile/module.php");
								}
							}
							closedir($subhandle);
						}
					}
				} 
				closedir($handle); 
			}

			$this->run();
		}
		
		// Variable setting function: This allows functions to store variables that
		// will be required by later functions. It is prefered to use this instead
		// of custom global variables.
		function var_set($group,$variable,$data) {
			global $Neptune;
						
			$Neptune["stack"][$group][$variable] = $data;

			return 0;
		}

		function var_clear($group,$variable) {
			global $Neptune;
						
			$Neptune["stack"][$group][$variable] = '';

			return 0;
		}
		
		function var_del($group,$variable) {
			global $Neptune;
						
			unset($Neptune["stack"][$group][$variable]);

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

		function alert($text,$type) {
			$this->var_append("output","alert","<div class=\"alert alert-" . $type . "\">\n" . $text . "\n</div>");
		}

		function clear() {
			$this->var_set("output","body","");
		}

		function neptune_echo($text) {
			$this->var_append("output","body",$text);
		}
		
		function neptune_echo_bbcode($text,$trim = 0) {
			if ($trim == 0) {
				$this->var_append("output","body",neptune_bbcode($text));
			} else {
				$this->var_append("output","body",truncateHtml(neptune_bbcode($text),$trim));
			}
		}
		
		function neptune_echo_markdown($text, $smartypants = true,$trim = 0) {
			require_once('system/lib/markdown.php');
			require_once('system/lib/smartypants.php');
			
			if ($trim == 0) {
				$this->var_append("output","body",Markdown($text));
			} else {
				$this->var_append("output","body",truncateHtml(Markdown($text),$trim));
			}
		}
		
		function neptune_echo_textile($text, $restricted = true,$trim = 0) {
			require_once('system/lib/textile.php');
			
			global $Textile;
			
			// Create new Textile class if it doesn't already exist. 
			if(!isset($Textile)) {
				$Textile = new Textile();
			}
			
			if ($trim == 0) {
				if (!$restricted) {
					$this->var_append("output","body",$Textile->TextileThis($text));
				} else {
					$this->var_append("output","body",$Textile->TextileRestricted($text));
				}
			} else {
				if (!$restricted) {
					$this->var_append("output","body",truncateHtml($Textile->TextileThis($text),$trim));
				} else {
					$this->var_append("output","body",truncateHtml($Textile->TextileRestricted($text),$trim));
				}			
			}
		}
		
		function title($text) {
			$this->var_set("output","title",$text);
		}
		
		function subtitle($text) {
			$this->var_set("output","subtitle",$text);
		}
		
		function header($header) {
			$this->var_append("output","header","\n" . $header);
		}
		
		function footer($footer, $aftercontent = false) {
			if (!$aftercontent) {
				$this->var_append("output","footer","<br>" . $footer);
			} else {
				$this->var_append("output","footer2","" . $footer);
			}
		}
		
		function register_module($mod) {
			$this->var_append("footer","modules",$mod . " ");
		}
		
		function neptune_active($id) {
			$this->var_set("output","menu_active",$id);
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
				//$this->fatal_error("Invalid function");
				$this->title("404 Module Not Found");
				$this->neptune_echo("Your request could not be processed, because the specified module does not exist.");
			}
		}
		
		function parseconf($config) {
			if (file_exists($config)) {
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
			} else {
        $this->fatal_error("A core function or module attempted to load the configuration file " . $config . ", but the file does not exist.");
      }
		}
		
		function fatal_error($error) {
			$this->title("Error");
			$this->clear();
			$this->neptune_echo($error);
			
			require("system/theme/fallback.php");
			
			exit;
		}
		
		function footer_timer() {
			global $starttime;
			
			$time = microtime();
			$endtime=substr($time,11).substr($time,1,9);
			
			$RAM["raw"] = memory_get_peak_usage(true);
			$unit=array('bytes','KiB','MiB','GiB','TiB','PiB');
			$RAM["converted"] = @round($RAM["raw"]/pow(1024,($i=floor(log($RAM["raw"],1024)))),2).' '.$unit[$i]; 
			
			return "Page generated in " . round($endtime - $starttime,3) * 1000 . " ms with " . $this->var_get("system","querycount") . " queries and " . $RAM["converted"] . " of RAM";
		}
		
		function generate_menu() {
			global $NeptuneCore, $NeptuneSQL, $NeptuneAdmin;
		
			return array();
			
			// Create new SQL class if it doesn't already exist. 
			if( !isset($NeptuneSQL)) {
				$NeptuneSQL = new NeptuneSQL();
			}
			
			$sql = $NeptuneSQL->query("SELECT * FROM `neptune_menu` ORDER BY `position` ASC");
			$NeptuneMenu = array();
			while ($result = $NeptuneSQL->fetch_array($sql)) {
				if ($result["type"] == 0) {
					$result["path"] = "?" . $result["path"];
				}
				$NeptuneMenu[$result["path"]] = $result["name"];
			}
			
			return $NeptuneMenu;
		}
		
		function run() {
			global $NeptuneCore, $starttime;

			// Run whatever function is hooked to the current request.
			$NeptuneCore->hook_run($NeptuneCore->var_get("system","query"));
	
			if ($NeptuneCore->var_get("output","raw") == true) {
				echo $NeptuneCore->var_get("output","body");
				exit();
			}
			
			$this->footer("Modules loaded: " . $this->var_get("footer","modules"));
		
			if (file_exists("theme/" . $this->var_get("config","theme") . "/config.php")) {
				$this->parseconf("theme/" . $this->var_get("config","theme") . "/config.php");
			}
			if ($this->var_get("theme","altlayout") && file_exists("theme/" . $this->var_get("config","theme") . "/" . $this->var_get("theme","altlayout") . ".php")) {
				require("theme/" . $this->var_get("config","theme") . "/" . $this->var_get("theme","altlayout") . ".php");
			} else if (file_exists("theme/" . $this->var_get("config","theme") . "/layout.php")) {
				require("theme/" . $this->var_get("config","theme") . "/layout.php");
			} else {
        $this->fatal_error("The theme file specified in the system configuration is missing.");
			}

			exit;
		}
	}
	
	// Initialize a new instance of the class. The constructor function will take it from there.
	new NeptuneCore();
?>