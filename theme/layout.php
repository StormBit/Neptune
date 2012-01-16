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
        
        <!-- Hacks to get this to work in IE 7 and IE 8. IE 6 is hopeless, so we just make it show an Unsupported Browser page. -->
        <script src="resources/js/html5.js"></script>
        <script type="text/javascript" src="resources/js/respond.min.js"></script>
        <!--[if lte IE 6]>
            <style type="text/css">
                .topbar, .container {
                    display: none;
                }
            </style>
            <script type="text/javascript">
                document.title = "Unsupported Browser";
            </script>
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
    <body onload="$('#mobile-menu').dropdown();$('#user-menu').dropdown();">
        <!--[if lte IE 6]>
            <div style="padding: 8px;font-family:sans-serif;position: absolute;top:0;left:0;" id="message">
                <h2>Unsupported Browser</h2>
                <p>Your are using an <b>extremely outdated, unsupported browser</b>.</p>
                <p><a href="http://www.browserchoice.eu/" target="_blank">Please <b>keep it real</b> and use a browser that isn't <b>over 10 years old</b>.</a></p>
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
                        <li id="mobile-menu-dropdown">
                            <a class="menu brand" href="#" onclick="hideLoginForms();">Neptune</a>
                            <ul class="menu-dropdown">
                                <li class="active"><a href="#" onclick="hideLoginForms();">Home</a></li>
                                <li class="divider"></li>
                                <li><a href="#about">About</a></li>
                                <li><a href="#contact">Contact</a></li>
                            </ul>
                        </li>
                    </ul>
                    <div class="pull-right">
                        <ul class="nav secondary-nav" id="user-menu">
                            <?php
                                if (neptune_get_permissions() == 0) {
                                    echo '<li id="mobile-login-button"><a href="#" onclick="hideLoginForms();$(\'#content-area\').hide();$(\'#mobile-login\').show();$(\'#mobile-login-button\').addClass(\'active\');">Login</a></li>' . "\n" . '                            <li id="mobile-register-button"><a href="#" onclick="hideLoginForms();$(\'#content-area\').hide();$(\'#mobile-register\').show();$(\'#mobile-register-button\').addClass(\'active\');">Register</a></li>' . "\n";
                                } else if (neptune_get_permissions() >= 1) {
									echo '<li id="user-menu-dropdown">' . "\n                                " . '<a class="menu" href="#">' . neptune_get_username() . '</a>' . "\n                                " . '<ul class="menu-dropdown">' . "\n                                    " . '<li><a href="?logout/' . implode("/",$NeptuneCore->var_get("system","query")) . '">Logout</a></li>' . "\n                                    " . '<li class="divider"></li>' . "\n                                    " . '<li><a href="?profile">Edit Profile</a></li>' . "\n                                    " . '<li><a href="?ucp">User Control Panel</a></li>';
									if (neptune_get_permissions() >= 3) {
										echo "\n                                    " . '<li class="divider"></li>' . "\n                                    " . '<li><a href="?acp">Admin Control Panel</a></li>';
									}
									echo "\n                                " . '</ul>' . "\n                            " . '</li>' . "\n";
								}
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="content">
                <div class="row">
                    <div class="span14">
                        <div class="hide" id="mobile-login">
                            <h2>Login to Neptune</h2>
                            <form action="?login/<?php echo implode("/",$NeptuneCore->var_get("system","query")); ?>" method="POST">
                                <div class="clearfix">
                                    <input class="large" type="text" placeholder="Username" name="user">
                                </div>
                                <div class="clearfix">
                                    <input class="large" type="password" placeholder="Password" name="pass">
                                </div>
                                <div class="clearfix">
                                    <button class="btn primary" type="submit">Login</button>
                                    <button class="btn" onclick="hideLoginForms();return false;">Cancel</button>
                                </div>
                                </form>
                        </div>
                        <div class="hide" id="mobile-register">
                            <h2>Create Account</h2>
                            <form action="?register/<?php echo implode("/",$NeptuneCore->var_get("system","query")); ?>" method="POST">
                            <div class="clearfix">
                                <input class="large" type="text" placeholder="Username" name="user">
                            </div>
                            <div class="clearfix">
                                <input class="large" type="password" placeholder="Password" name="pass1">
                            </div>
                            <div class="clearfix">
                                <input class="large" type="password" placeholder="Password (confirm)" name="pass2">
                            </div>
                            <div class="clearfix">
                                <input class="large" type="text" placeholder="Email (optional)" name="email">
                            </div>
                            <div class="clearfix">
                                <button class="btn primary" type="submit">Register</button>
                                <button class="btn" onclick="hideLoginForms();return false;">Cancel</button>
                            </div>
                            </form>
                        </div>
                        <div id="content-area">
                            <!--[if IE 7]>
                                <br><br>
                                <div class="iewarning">Warning: Please upgrade your browser to something compatible with the internet.<br><a href="http://www.browserchoice.eu/" target="_blank">There are many browsers to choose from, any except the one you are using is good.</a></div>
                                <br><br>
                            <![endif]-->
                            <h2><?php echo $NeptuneCore->var_get("output","title"); ?></h2><?php echo "\n" . $NeptuneCore->var_get("output","body") . "\n"; ?>
                        </div>
                    </div>
                </div>
                <footer>
                    <p>Powered by the Neptune CMS, SaaS Edition</p><p><small>Copyright © 2012 StormDEV, All Rights Reserved<br>Page generated in <?php $time = microtime(); $endtime=substr($time,11).substr($time,1,9); echo round($endtime - $starttime,3) * 1000; ?> ms with <?php echo $NeptuneCore->var_get("system","querycount"); ?> queries and <?php $RAM["raw"] = memory_get_peak_usage(true);$unit=array('bytes','KiB','MiB','GiB','TiB','PiB');$RAM["converted"] = @round($RAM["raw"]/pow(1024,($i=floor(log($RAM["raw"],1024)))),2).' '.$unit[$i]; echo $RAM["converted"]; ?> of RAM<br>Using the <?php echo NeptuneSQL::type(); ?> database engine</small></p>
                </footer>
            </div>
        </div>
    </body>
</html>
