<?php
	/*
		Neptune Content Management System
		Dummy Module - /modules/dummy/module.php

		Module does nothing. 
	*/

	function mod_dummy_page() {
		global $NeptuneCore;
		$NeptuneCore->neptune_echo_bbcode("Dummy module ran.");
		$NeptuneCore->neptune_title("Dummy Module");
	}
	$NeptuneCore->hook_function("page","dummy","page");
?>

