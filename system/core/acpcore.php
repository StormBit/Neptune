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
		
		if (!isset($NeptuneAdmin)) {
			$NeptuneAdmin = new NeptuneAdmin();
		}	
		
		if (neptune_get_permissions() >= 3) {
			$NeptuneAdmin->run();
		} else {
			$NeptuneCore->neptune_title("Access Denied");
			$NeptuneCore->neptune_echo("<p>You do not have permission to view this page.</p>");
			
			header("HTTP/1.1 403 Forbidden");
		}
	}
	$NeptuneCore->hook_function("acp","core","admin");
	
	class NeptuneAdmin {
		function __construct() {
			global $NeptuneCore;
			global $NeptuneSQL;
			
			$AdminHooks = array();
		}
		
		function run() {
			$this->display_index();
		}
		
		function display_index() {
			global $NeptuneCore;
			global $NeptuneSQL;
			
			
			$NeptuneCore->neptune_title("Admin Control Panel");
			$NeptuneCore->neptune_echo("");
		}
		
		function add_hook($section,$path,$title,$description) {
			//$this->$AdminHooks[$section][] = array("title" => $title, "description" => $description)
		}
	}
?>