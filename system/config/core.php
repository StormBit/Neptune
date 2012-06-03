<?php
	/*
		Neptune Content Management System
		Configuration File: First Stage - /system/config/core.php

		This is the first stage configuration file. It stores any configuration
		data that will be required by Neptune directly after the core functions
		have been loaded.
	*/

	$conf['config']['sitename'] = 'Neptune';
	$conf['config']['defaultact'] = 'page';
	$conf['config']['locale'] = 'en';
	$conf['config']['theme'] = 'bootstrap';
	

//	$conf['cache']['type'] = 'apc';
	$conf['cache']['type'] = 'none';
	$conf['cache']['expire'] = 10;
	
	$conf['auth']['key'] = "derp";

	$conf['database']['type'] = 'mysql';
?>
