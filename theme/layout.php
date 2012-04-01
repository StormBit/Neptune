<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title><?php echo $NeptuneCore->var_get("output","title"); ?> :: <?php echo $NeptuneCore->var_get("config","sitename"); ?></title>

        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="viewport" content="width=device-width, user-scalable=true, initial-scale=1, maximum-scale=1">
        <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">        
        
        <link href="resources/css/bootstrap.min.css" rel="stylesheet">
        <link href="resources/css/main.css" rel="stylesheet">
        <link href="resources/css/bbcode.css" rel="stylesheet">

        <script type="text/javascript" src="resources/js/jquery-1.7.1.min.js"></script>
        <script type="text/javascript" src="resources/js/bootstrap-dropdown.js"></script>
        
        <?php
            if ($NeptuneCore->var_get("output","menu_active") != "") {
                echo '<style type="text/css">#' . $NeptuneCore->var_get("output","menu_active") . '{background-color:#222;background-color:rgba(0, 0, 0, 0.5);}</style>' . "\n";
            }
        ?>
                    
        <!-- Hacks to get this to work in IE 7 and IE 8
             Not placed in a conditional comment because these are also useful for other non-IE browsers (such as Firefox 2.x) 
        -->
        <!--[if gte IE 7]><!-->
        <script type="text/javascript" src="resources/js/html5.js"></script>
        <?php if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)) { echo '
        <!--[if gte IE 7]>
            <script type="text/javascript">
                $(function() {
                    if(!$.support.placeholder) { 
                        var active = document.activeElement;
                        $(":text").focus(function () {
                            if ($(this).attr("placeholder") != "" && $(this).val() == $(this).attr("placeholder")) {
                                $(this).val("").removeClass("hasPlaceholder");
                            }
                        }).blur(function () {
                            if ($(this).attr("placeholder") != "" && ($(this).val() == "" || $(this).val() == $(this).attr("placeholder"))) {
                                $(this).val($(this).attr("placeholder")).addClass("hasPlaceholder");
                            }
                        });
                        $(":text").blur();
                        $(active).focus();
                        $("form").submit(function () {
                            $(this).find(".hasPlaceHolder").each(function() { $(this).val(""); });
                        });
                    }
                });
            </script>
        <![endif]-->';}?><script type="text/javascript" src="resources/js/respond.min.js"></script>
        <!--<![endif]-->
    </head>
    <body onload="$('#mobile-menu').dropdown();$('#user-menu').dropdown();">        
        <div class="topbar">
            <div class="fill">
                <div class="container">
                    <ul class="nav" id="menu">
                        <li><a class="brand" href="?"><?php echo $NeptuneCore->var_get("config","sitename"); ?></a></li>
                        <li class="active"><a href="#">Home</a></li>
                        <li><a href="#about">About</a></li>
                        <li><a href="#contact">Contact</a></li>
                    </ul>
                    <ul class="nav mobile" id="mobile-menu">
                        <li id="mobile-menu-dropdown">
                            <a class="menu brand" href="#"><?php echo $NeptuneCore->var_get("config","sitename"); ?></a>
                            <ul class="menu-dropdown">
                                <li class="active"><a href="?" onclick="hideLoginForms();">Home</a></li>
                                <li class="divider"></li>
                                <li><a href="#about">About</a></li>
                                <li><a href="#contact">Contact</a></li>
                            </ul>
                        </li>
                    </ul>
                        <ul class="nav secondary-nav" id="user-menu">
                            <?php
                                if (neptune_get_permissions() == 0) {
                                    echo '<li id="login-button"><a href="?login/' . implode("/",$NeptuneCore->var_get("system","query")) . '" >Login</a></li>' . "\n" . '                            <li id="register-button"><a href="?register/' . implode("/",$NeptuneCore->var_get("system","query")) . '">Register</a></li>' . "\n";
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

        <div class="container">
            <div class="content">
                <div class="fill">        
                    <!--<ul class="breadcrumb"></ul>-->

                    <div id="content-area">
                        <?php if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)) { echo '<!--[if IE 7]>
                            <br><br>
                            <div class="iewarning">Warning: Please upgrade your browser to something compatible with the internet.<br><a href="http://www.browserchoice.eu/" target="_blank">There are many browsers to choose from, any except the one you are using is good.</a></div>
                            <br><br>
                        <![endif]-->
                        <!--[if lte IE 6]>
                            <div class="iewarning">
                                <h2>Unsupported Browser</h2>
                                <p>You are using an <b>extremely outdated, unsupported browser</b>.</p>
                                <p><a href="http://www.browserchoice.eu/" target="_blank">Please <b>keep it real</b> and use a browser that isn\'t <b>over 10 years old</b>.</a></p>
                            </div>
                        <![endif]-->';}?><h2><?php echo $NeptuneCore->var_get("output","title_prepend") . $NeptuneCore->var_get("output","title") . $NeptuneCore->var_get("output","title_append"); ?></h2>
                        <?php
                            if ($NeptuneCore->var_get("output","subtitle") != "") {
                                echo "<p><small>" . $NeptuneCore->var_get("output","subtitle") . "</small></p>\n";
                            }
                        ?>

                        <hr>
                        <?php echo "\n" . $NeptuneCore->var_get("output","body") . "\n"; ?>
                    </div>
                </div>
                <footer>
                    <div id="StormDEVLogo"></div><p><small>Copyright © 2012 StormDEV, All Rights Reserved<br>Page generated in <?php $time = microtime(); $endtime=substr($time,11).substr($time,1,9); echo round($endtime - $starttime,3) * 1000; ?> ms with <?php echo $NeptuneCore->var_get("system","querycount"); ?> queries and <?php $RAM["raw"] = memory_get_peak_usage(true);$unit=array('bytes','KiB','MiB','GiB','TiB','PiB');$RAM["converted"] = @round($RAM["raw"]/pow(1024,($i=floor(log($RAM["raw"],1024)))),2).' '.$unit[$i]; echo $RAM["converted"]; ?> of RAM<br>Using the <?php echo NeptuneSQL::type(); ?> database engine</small></p>
                </footer>
            </div>
        </div>
    </body>
</html>
