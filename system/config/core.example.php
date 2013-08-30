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
	
	// $conf['config']['favicon'] = 'resources/icon/favicon.ico';
	// $conf['config']['apple-touch-icon'] = 'resources/icon/apple-touch-icon.png';
	
	$conf['config']['quiet-footer'] = false;
	$conf['config']['hide-stormdev'] = false;
	$conf['config']['site-copyright'] = "&copy; 2013 My Amazing Website";

	// $conf['cache']['type'] = 'apc';
	// $conf['cache']['expire'] = 10;
	$conf['cache']['type'] = 'none';
	
	$conf['auth']['key'] = "JKASGDKFGASJHGRIOUQ43YH59782Y37RGHWJKGHJGJKHGJHKGHKJGJHGJHKGjkgjhkfgahdskgfkjhwgsjkhrgHGJKHGSKJHGSFSBHKFjinyuiyniyniyniyqwnrlkjwteayrfi78236y4578926y547r8oyry43rteqiytooytfqi54ytwou9i8f4e4eoeiugfeshiugherhtljkrhteswruiohtiogsjhdftkgjweg4rot52y435oy7hjkghjkGHJKGDKJHSGRHFJSGHDLFKJAWQGERUYGQ3O4T5726378949872364TR93G2Q8G82974923H87G3H58GH3875H84H93H08YOUIGOISECUREKEYISSECUREhJKHKLJHASE8FQ349762098UQ4IJKMNJKNRKFVDU7EIT5H4IO9FEASUJ5TK3IRO8GT4JKLUIWARGERKTGWQKEGRTHJGRJKHGJHGJHGHGjsjdnsjdhgkhdsja";

	$conf['database']['type'] = 'mysql';
?>
