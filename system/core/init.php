<?php
		/*
		Neptune Content Management System
		Initialization File - /system/init.php

		Stuff that could go in main.php, but won't
		for obvious reasons.
	*/

//	foreach(glob(*.php) as $module) {

	if($NeptuneCore->var_get('cache', 'enabled')) {
		require_once('cache.php');
	}
?>