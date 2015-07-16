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
	
	$conf['config']['quiet-footer'] = true; // If disabled, verbose information will be displayed in the page footer.
	$conf['config']['hide-stormdev'] = false; // If enabled, StormDEV branding will be hidden in the page footer.
	$conf['config']['site-copyright'] = "Copyright &copy; " . date("Y") . " Someone Who Doesn't Know How To Configure A Website, All Rights Reserved.";

	$conf['config']['blacklist-system-modules'] = false; // Disables the stock modules. Useful if you are building a site using fully customized modules.
	
	// $conf['cache']['type'] = 'apc';
	// $conf['cache']['expire'] = 10;
	$conf['cache']['type'] = 'none';
	
	$conf['auth']['key'] = "433d45b9bf7140e5b265d49d47f6aec2beea86367e2d4d35ad50c5ff88e1d36a889638be34ca4ac89564131da40934c55ccf88ab54fc4f4382973c870768b4e66f6f4a49d3094592a67b2657f061349aa4346359e44a463bbfa70e637ad2f3923f5ec788426046d684b723ab6c5703590d6767e698bc44a4bedb02f742b6d91ce6b5eef8a60e46fca9822b1efafbb5a45864dab477f04701875d076a9798a7733d85bf36c1b84c4d8d07748def0d8ccce47066b5b19848af8cba25d1b513545bc2ad4396acd645bba65a3e1143336ea6db13a9a7805440549854290ff2a3a7a09c430d0fe32f413a839d77571f802aff7fb14c7878b14415b8077c60000ebcc51bb74e76c4fb49758cdd9dc9709abb55";

	$conf['database']['type'] = 'mysql';
?>
