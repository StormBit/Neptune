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

			$NeptuneCore->neptune_subtitle("Page created by " . neptune_get_username_from_id($result["author"]) . " on" . date(" F jS, Y ", strtotime($result['created'])) . "at" . date(" g:i A", strtotime($result['created'])));

			if ($result["bbcode"] == 1) {
				$NeptuneCore->neptune_echo_bbcode($result["content"]);
			} else {
				$NeptuneCore->neptune_echo($result["content"]);
			}
		} else {
			die("<h1>404 - Page Not Found</h1>");
		}
	}
	$NeptuneCore->hook_function("page","core","page");


	function acp_page_new() {
		global $NeptuneCore, $NeptuneSQL, $NeptuneAdmin;

		if ($_SERVER['REQUEST_METHOD'] == "POST") {
			if(!isset($NeptuneSQL)) {
				$NeptuneSQL = new NeptuneSQL();
			}

			$PageID = $NeptuneSQL->escape_string($_POST["pageid"]);
			$PageTitle = $NeptuneSQL->escape_string($_POST["pagetitle"]);
			$PageContent = $NeptuneSQL->escape_string($_POST["pagecontent"]);
			$Username = $NeptuneSQL->escape_string(strtolower(neptune_get_username()));

			$Time = date ("Y-m-d H:i:s",time());

			$NeptuneSQL->query("INSERT INTO `neptune_pages` VALUES('$PageID','$PageTitle','$PageContent','$Username','$Time','$Username','$Time','1','1')");

			header("Location: ?page/$PageID");
		} else {
			$NeptuneCore->neptune_title("New Page");
			$NeptuneCore->neptune_echo("<form class='acp' action='?acp/page/new' method='POST'>\n<div class='clearfix'><input type='text' placeholder='Page ID' name='pageid' /></div><div class='clearfix'><input type='text' placeholder='Page Name' name='pagetitle' /></div>\n<div class='clearfix'><textarea name='pagecontent'></textarea></div><div class='clearfix'><span><input type='submit' class='btn primary' value='Create'/></span></div></form>");
		}
	}
	$NeptuneAdmin->add_hook("Core","page/new","New Page","Create a new page");

	function acp_page_list() {

	}
	$NeptuneAdmin->add_hook("Core","page/list","Page List","View and edit a list of pages");
?>
