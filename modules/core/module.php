<?php
	/*
		Neptune Content Management System
		Core Module - /modules/core/module.php

		Module that does all of the basic tasks. 
	*/
	
	
	function mod_core_page() {
		global $NeptuneCore;
		global $NeptuneSQL;
		
		// Create new SQL class if it doesn't already exist. 
		if( !isset($NeptuneSQL)) {
			$NeptuneSQL = new NeptuneSQL();
		}
		
		$query = $NeptuneCore->var_get("system","query");
		
		if (!array_key_exists(1,$query)) {
			$query[1] = "index";
		}
		
		$sql = $NeptuneSQL->query("SELECT * FROM `neptune_pages` WHERE `pid` = '" . $NeptuneSQL->escape_string($query[1]) . "'");
		
		if ($result = $NeptuneSQL->fetch_array($sql)) {
			$NeptuneCore->neptune_title($result["name"]);

			$NeptuneCore->neptune_echo("<p><small>Page created by " . neptune_get_username_from_id($result["author"]) . " on" . date(" F jS, Y ", strtotime($result['created'])) . "at" . date(" g:i A", strtotime($result['created'])) . "</small></p><br>");
			
			if ($result["bbcode"] == 1) {
				$NeptuneCore->neptune_echo_bbcode($result["content"]);
			} else {
				$NeptuneCore->neptune_echo($result["content"]);
			}
		} else {
			die("<h1>404 - Page Not Found</h1>");
		}
	}
	$this->hook_function("page","core","page");
?>