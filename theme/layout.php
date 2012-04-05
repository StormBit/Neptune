<!DOCTYPE html>
<html lang="<?php echo $NeptuneCore->var_get("config","locale"); ?>">
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
			$query = "";
			
			if (isset($_SERVER["QUERY_STRING"])) {
				$query = $_SERVER["QUERY_STRING"];
			}
			if ($query == "") { $query = "page/index"; }
			
			$cssid = "menu_" . preg_replace('/[^a-zA-Z0-9\s]/', "_", $query);

            echo '<style type="text/css">#' . $cssid . '{background-color:#222;background-color:rgba(0, 0, 0, 0.5);}</style>' . "\n";
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
                        <li><a class="brand" href="?"><?php echo $NeptuneCore->var_get("config","sitename"); ?></a></li><?php echo "\n";
							$Menu = $NeptuneCore->generate_menu();
							
							foreach ($Menu as $key => $value) {
								$path = $key;
								
								$key = ltrim($key,"?");
								
								$key = preg_replace('/[^a-zA-Z0-9\s]/', "_", $key );
								$key = "menu_" . $key;
								
								echo "                        <li id=\"$key\"><a href=\"$path\">$value</a></li>\n";
							}
						?>
                    </ul>
                    <ul class="nav mobile" id="mobile-menu">
                        <li id="mobile-menu-dropdown">
                            <a class="menu brand" href="#"><?php echo $NeptuneCore->var_get("config","sitename"); ?></a>
                            <ul class="menu-dropdown"><?php echo "\n";
									$Menu = $NeptuneCore->generate_menu();
									
									foreach ($Menu as $key => $value) {
										$path = $key;
										
										$key = ltrim($key,"?");
										
										$key = preg_replace('/[^a-zA-Z0-9\s]/', "_", $key );
										$key = "menu_" . $key;
										
										echo "                        <li id=\"$key\"><a href=\"$path\">$value</a></li>\n";
									}
								?>
                            </ul>
                        </li>
                    </ul>
                        <ul class="nav secondary-nav" id="user-menu">
                            <?php
                                if (neptune_get_permissions() == 0) {
                                    echo '<li id="login-button"><a href="?login/' . implode("/",$NeptuneCore->var_get("system","query")) . '" >' . $NeptuneCore->var_get("locale","login") . '</a></li>' . "\n" . '                            <li id="register-button"><a href="?register/' . implode("/",$NeptuneCore->var_get("system","query")) . '">' . $NeptuneCore->var_get("locale","register") . '</a></li>' . "\n";
                                } else if (neptune_get_permissions() >= 1) {
                                    echo '<li id="user-menu-dropdown">' . "\n                                " . '<a class="menu" href="#">' . neptune_get_username() . '</a>' . "\n                                " . '<ul class="menu-dropdown">' . "\n                                    " . '<li><a href="?logout/' . implode("/",$NeptuneCore->var_get("system","query")) . '">' . $NeptuneCore->var_get("locale","logout") . '</a></li>' . "\n                                    " . '<li class="divider"></li>' . "\n                                    " . '<li><a href="?profile">' . $NeptuneCore->var_get("locale","editprofile") . '</a></li>' . "\n                                    " . '<li><a href="?ucp">' . $NeptuneCore->var_get("locale","ucp") . '</a></li>';
                                    if (neptune_get_permissions() >= 3) {
                                        echo "\n                                    " . '<li class="divider"></li>' . "\n                                    " . '<li><a href="?acp">' . $NeptuneCore->var_get("locale","acp") . '</a></li>';
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
                            <div class="iewarning">' . $NeptuneCore->var_get("locale","ie7warning1") . '<br><a href="http://www.browserchoice.eu/" target="_blank">' . $NeptuneCore->var_get("locale","ie7warning2") . '</a></div>
                            <br><br>
                        <![endif]-->
                        <!--[if lte IE 6]>
                            <div class="iewarning">
                                <h2>' . $NeptuneCore->var_get("locale","ie6warning1") . '</h2>
                                <p>' . $NeptuneCore->var_get("locale","ie6warning2") . '</p>
                                <p><a href="http://www.browserchoice.eu/" target="_blank">' . $NeptuneCore->var_get("locale","ie6warning3") . '</a></p>
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
