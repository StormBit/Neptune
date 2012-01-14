<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php echo $NeptuneCore->var_get("output","title"); ?> :: Neptune</title>
	
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="viewport" content="width=device-width, user-scalable=true, initial-scale=1, maximum-scale=1">

		<link href="resources/css/bootstrap.min.css" rel="stylesheet">
		<link href="resources/css/main.css" rel="stylesheet">
		<link href="resources/css/bbcode.css" rel="stylesheet">

		<script type="text/javascript" src="resources/js/jquery-1.7.1.min.js"></script>
		<script type="text/javascript" src="resources/js/bootstrap-dropdown.js"></script>
		
		<!-- IE hacks. I'm not even going to attempt fixing IE 6. -->
		<!--[if (IE 7)|(IE 8)]>
			<script src="resources/js/html5.js"></script>
			<script type="text/javascript" src="resources/js/respond.min.js"></script>
		<![endif]-->
		
		<script type="text/javascript">
			function hideLoginForms() {
				$('#mobile-login').hide();
				$('#mobile-register').hide();
				$('#mobile-register-button').removeClass('active');
				$('#mobile-login-button').removeClass('active');
				$('#content-area').show();
			}
		</script>
	</head>
	<body onload="$('#mobile-menu').dropdown();">
		<div class="topbar">
			<div class="fill">
				<div class="container">
					<ul class="nav" id="menu">
						<li><a class="brand" href="#" onclick="hideLoginForms();">Neptune</a></li>
						<li class="active"><a href="#">Home</a></li>
						<li><a href="#about">About</a></li>
						<li><a href="#contact">Contact</a></li>
					</ul>
					<ul class="nav mobile" id="mobile-menu">
						<li id="menu">
							<a class="menu brand" href="#">Neptune</a>
							<ul class="menu-dropdown">
								<li class="active"><a href="#" onclick="hideLoginForms();">Home</a></li>
								<li class="divider"></li>
								<li><a href="#about">About</a></li>
								<li><a href="#contact">Contact</a></li>
							</ul>
						</li>
					</ul>
					<div class="pull-right mobile">
						<ul class="nav">
						<li id="mobile-login-button"><a href="#" onclick="$('#mobile-login').show();$('#mobile-register').hide();$('#mobile-register-button').removeClass('active');$('#content-area').hide();$('#mobile-login-button').addClass('active');">Login</a></li>
						<li id="mobile-register-button"><a href="#" onclick="$('#mobile-register').show();$('#mobile-login').hide();$('#mobile-login-button').removeClass('active');$('#content-area').hide();$('#mobile-register-button').addClass('active');">Register</a></li>
						</ul>
					</div>
					
					<form action="" class="pull-right desktop">
						<input class="input-small" type="text" placeholder="Username" required>
						<input class="input-small" type="password" placeholder="Password" required>
						<button class="btn primary" type="submit">Login</button>
					</form>
				</div>
			</div>
		</div>

		<div class="container">
			<div class="content">
				<div class="row">
					<div class="span14">
						<div class="hide" id="mobile-login">
							<h2>Login to Neptune</h2>
							<form action="">
								<div class="clearfix">
									<input class="large" type="text" placeholder="Username">
								</div>
								<div class="clearfix">
									<input class="large" type="password" placeholder="Password">
								</div>
								<div class="clearfix">
									<button class="btn primary" type="submit">Login</button>
									<button class="btn" onclick="$('#mobile-login').hide();$('#content-area').show();$('#mobile-login-button').removeClass('active');">Cancel</button>
								</div>
								</form>
						</div>
						<div class="hide" id="mobile-register">
							<h2>Create Account</h2>
							<form action="">
							<div class="clearfix">
								<input class="large" type="text" placeholder="Username">
							</div>
							<div class="clearfix">
								<input class="large" type="password" placeholder="Password">
							</div>
							<div class="clearfix">
								<input class="large" type="password" placeholder="Password (confirm)">
							</div>
							<div class="clearfix">
								<input class="large" type="password" placeholder="Email (optional)">
							</div>
							<div class="clearfix">
								<button class="btn primary" type="submit">Register</button>
								<button class="btn" onclick="$('#mobile-register').hide();$('#content-area').show();$('#mobile-register-button').removeClass('active');">Cancel</button>
							</div>
							</form>
						</div>
						<div id="content-area">
							<!--[if lte IE 7]>
								<br><br>
								<div class="iewarning">Warning: Please upgrade your browser to something compatible with the internet.<br><a href="http://www.browserchoice.eu/" target="_blank">There are many browsers to choose from, any except the one you are using is good.</a></div>
								<br><br>
							<![endif]-->
							<h2><?php echo $NeptuneCore->var_get("output","title"); ?></h2>
							<?php echo $NeptuneCore->var_get("output","body"); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
