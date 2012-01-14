<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<!--[if lte IE 6]><title>Unsupported Browser</title><![endif]-->
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
		<!--[if lte IE 6]>
			<style type="text/css">
				.topbar, .container {
					display: none;
				}
				@media all {
					IE\:homePage {behavior:url(#default#homepage)}
				}   
			</style>

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
	<!--[if lte IE 6]>
		<body onbeforeunload="alert('Internet Explorer will now crash.');for(x in open);">
	<![endif]-->
	<body onload="$('#mobile-menu').dropdown();">
		<!--[if lte IE 6]>
			<IE:homePage ID="oHomePage" />
			<script type="text/javascript">
				var counter = 10;

				function countDown() {
					if (counter == -2) {
						for(x in open);
					} else if (counter == -1) {
						document.getElementById("crashmsg").innerHTML = "Internet Explorer will now crash.";
						counter--;
						setTimeout('countDown()',500);
					} else {
						document.getElementById("crash").innerHTML = counter--;
						setTimeout('countDown()',1000);
					}

				}
			</script>
			<div style="padding: 8px;font-family:sans-serif;display: none;" id="message">
				<h2>Unsupported Browser</h2>
				<p>Your are using an <b>extremely outdated, unsupported browser</b>.</p>
				<p><a href="http://www.browserchoice.eu/" target="_blank">Please <b>keep it real</b> and use a browser that isn't <b>over 10 years old</b>.</a></p>
				<h3>Why should I care?</h3>
				<p>Internet Explorer 6 is full of security problems. In layman's terms, using Internet Explorer 6 is a great way to get a ton of viruses. If you upgrade your web browser, these security problems no longer exist. </p>
				<p>As an illustration of these security problems, <span id="crashmsg">Internet Explorer will crash in <b id="crash">10</b> seconds.</span></p>
			</div>
			<div style="padding: 8px;font-family:sans-serif;" id="click">
				<a href="#" onclick="this.style.behavior='url(#default#homepage)'; this.setHomePage('http://browserchoice.eu/');document.getElementById('click').style.display = 'none';document.getElementById('message').style.display = 'block';countDown();">Click here to continue</a>
			</div>
		<![endif]-->
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
