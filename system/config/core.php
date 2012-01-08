<?php
	/*
		Neptune Content Management System
		Configuration File: First Stage - /system/config/core.php

		This is the first stage configuration file. It stores any configuration
		data that will be required by Neptune directly after the core functions
		have been loaded.
	*/

//	neptune_var_set("config","defaultact","page");
	$conf['config']['defaultact'] = 'page';
	$conf['config']['root'] = '/home/antoligy/neptune';
//	$conf['config']['defaultact'] = 'test';
	
	$conf['cache']['enabled'] = True;
#	$conf['cache']['expire'] = 10;
	$conf['cache']['type'] = 'apc';
	
	$conf['database']['type'] = 'mysql';
?>
