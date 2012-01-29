<?php
	/*
		Neptune Content Management System
		Database Driver Loader - /system/core/database.php

		This file is responsible for loading the database druver specified in
		the configuration. 
	*/

	if(!defined('NepNep')) {
		die('NO U');
	}
	
	if ($NeptuneCore->var_get("database","type") == "mysql") {
		require_once("system/drivers/mysql.php");
	} else {
		$NeptuneCore->fatal_error("Unknown database type");
	}
?>
