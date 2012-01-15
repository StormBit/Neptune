<?php
	/*
		Neptune Content Management System
		Dummy Module - /modules/dummy/module.php

		Module does nothing. 
	*/

	function mod_dummy_page() {
		global $NeptuneCore;
		$NeptuneCore->neptune_echo_bbcode('Module ran!
[code]Code test[/code]
[quote]Quote Test
[quote=Someone]Nested Quote Test[/quote]
[/quote]
[b]Bold[/b] and <b>Bold</b> and [b]Bold[/b] 
');

		$NeptuneCore->neptune_title("Test Module");
	}
	$NeptuneCore->hook_function("page","dummy","page");
?>