<?php
	/*
		Neptune Content Management System
		Admin Panel: Core - /system/core/acpcore.php

		Core of the Admin Control Panel
	*/
	
	function mod_core_admin() {
		global $NeptuneCore;
		global $NeptuneSQL;
		global $NeptuneAdmin;
		
		if (!isset($NeptuneCore)) {
			$NeptuneCore = new NeptuneCore();
		}	
		if (!isset($NeptuneSQL)) {
			$NeptuneSQL = new NeptuneSQL();
		}	
		if (!isset($NeptuneAdmin)) {
			$NeptuneAdmin = new NeptuneAdmin();
		}	
		
		
		if (neptune_get_permissions() >= 3) {
			$query = $NeptuneCore->var_get("system","query");
			if (@isset($query[1]) && @isset($query[2])) {
				$AdminFunction = "acp_" . $query[1] . "_" . $query[2];
				
				$AdminFunction();
			} else {
				$NeptuneAdmin->run();
			}
		} else {
			$NeptuneCore->title("Access Denied");
			$NeptuneCore->neptune_echo("<p>You do not have permission to view this page.</p>");
			
			header("HTTP/1.1 403 Forbidden");
		}
	}
	$NeptuneCore->hook_function("acp","core","admin");
	
	class NeptuneAdmin {
		function __construct() {
			global $NeptuneCore;
			global $NeptuneSQL;
			
			global $AdminHooks;
			$AdminHooks = array();
			
			if (!$NeptuneCore->var_get("config","blacklist-system-modules")) $AdminHooks["Core"] = array(); // Force the Core section to be at the top of the Admin Control Panel, but only if blacklist-system-modules is false.
		}
		
		function run() {
			$this->display_index();
		}
		
		function display_index() {
			global $NeptuneCore;
			global $NeptuneSQL;
			global $AdminHooks;
			
			$NeptuneCore->title("Admin Control Panel");
			
			$NeptuneCore->neptune_echo("<div class='container'>");

			$count = 0;
			foreach ($AdminHooks as $Section) {
				$NeptuneCore->neptune_echo("<h3>" . $this->KeyName($AdminHooks,$count) . "</h3>");
				
				$count++;
				
				$count2 = 1;
				$NeptuneCore->neptune_echo("<div class='row'>");
				foreach ($Section as $Item) {
					$NeptuneCore->neptune_echo("<div class='span4 acpitem'><b><a href='?acp/" . $Item["path"] . "'>" . $Item["title"] . "</a></b><br>" . $Item["description"] . "\n</div>");
					
					$count2++;
				}
				$NeptuneCore->neptune_echo("</div>");
			}
			$NeptuneCore->neptune_echo("</div>");
		}
		
		function add_hook($section,$path,$title,$description) {
			global $AdminHooks;
			global $NeptuneCore;
			global $NeptuneSQL;
			global $NeptuneAdmin;

			if (!isset($NeptuneCore)) {
				$NeptuneCore = new NeptuneCore();
			}	
			if (!isset($NeptuneSQL)) {
				$NeptuneSQL = new NeptuneSQL();
			}	
			if (!isset($NeptuneAdmin)) {
				$NeptuneAdmin = new NeptuneAdmin();
			}	
			
			if (!isset($AdminHooks[$section])) {
				$AdminHooks[$section] = array();
			}
			
			array_push($AdminHooks[$section],array("title" => $title, "description" => $description, "path" => $path));
		}
		
		function KeyName(array $a, $pos) {
			$temp = array_slice($a, $pos, 1, true);
			return key($temp);
		}
	}
?>
