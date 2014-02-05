<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Critical Error</title>

		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
		<!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1"><![endif]-->
		
		<style type="text/css">
			body, h1 {
				font-family: sans-serif;
			}
		</style>
	</head>
	<body>
		<h1>Critical Error</h1>
		<p>This page could not be displayed because a critical error has occurred.</p>
		<p>The system provided the following error message:</p>
		<?php
			global $NeptuneCore;
			
			echo "<p>" . $NeptuneCore->var_get("output","body") . "</p>"; 
		?>
	</body>
</html>