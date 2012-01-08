<?php
	/*
		Neptune Content Management System
		Dummy Module - /modules/dummy/module.php

		Module does nothing. 
	*/

	function mod_dummy_page() {
		echo "Dummy module ran.";
	}
	$NeptuneCore->hook_function("page","dummy","page");
?>

