<?php
	/*
		Neptune Content Management System
		Installation Script - /system/install/install.php

		Installs Neptune.
	*/
	
	function mod_install_install() {
		global $NeptuneCore;
		
		$NeptuneCore->title("Database Error");
		$NeptuneCore->neptune_echo("<p>The operation could not be completed because an unexpected database error occurred.</p><p>Possible problems include:</p><ul><li>The database server might be experiencing temporary problems</li><li>Neptune might not have been installed yet</li><li>You may have moved or renamed the configuration file</li></ul>");
	}
	$NeptuneCore->hook_function("install","install","install");
?>