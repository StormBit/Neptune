<?php
	/*
		Neptune Content Management System
		Configuration File: First Stage - /system/config/core.php

		This is the first stage configuration file. It stores any configuration
		data that will be required by Neptune directly after the core functions
		have been loaded.
	*/

	$conf['config']['defaultact'] = 'page';
	
	$conf['cache']['enabled'] = False;
#	$conf['cache']['expire'] = 10;
	$conf['cache']['type'] = 'apc';
	
	$conf['database']['type'] = 'sqlite3';
?>
