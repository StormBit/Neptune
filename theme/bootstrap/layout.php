<!DOCTYPE html>
<html lang="<?php echo $NeptuneCore->var_get("config","locale"); ?>">
    <head>
        <meta charset="utf-8">
        <title><?php echo $NeptuneCore->var_get("output","title"); ?> :: <?php echo $NeptuneCore->var_get("config","sitename"); ?></title>

		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
		<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">	      
		
		<link href="resources/css/bbcode.css" rel="stylesheet">
		<link href="resources/css/bootstrap.css" rel="stylesheet">
		<link href="resources/css/bootstrap-responsive.css" rel="stylesheet">
		<link href="resources/css/main.css" rel="stylesheet">
        
        <?php
			$query = "";
			
			if (isset($_SERVER["QUERY_STRING"])) {
				$query = $_SERVER["QUERY_STRING"];
			}
			if ($query == "") { $query = "page/index"; }
			
			$cssid = "menu_" . preg_replace('/[^a-zA-Z0-9\s]/', "_", $query);
			
			$query2 = $NeptuneCore->var_get("system","query");
			
            echo '<style type="text/css">#' . $cssid . ', #menu_' . $query2[0] . '{background-color:rgba(0, 0, 0, 0.5);background-color:#222;}</style>' . "\n";
        ?>

		<script type="text/javascript" src="resources/js/jquery.js"></script>
		<script type="text/javascript" src="resources/js/jquery-ui-1.8.20.custom.min.js"></script>
		<script type="text/javascript" src="resources/js/bootstrap.min.js"></script>          
        <script type="text/javascript" src="resources/js/html5.js"></script>
		<!--[if gte IE 8]><!--><script type="text/javascript" src="resources/js/respond.min.js"><!--<![endif]--></script><?php if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)) { echo '
        <!--[if IE]>
            <script type="text/javascript">
				$(function() {
					var input = document.createElement("input");
					if(("placeholder" in input)==false) { 
						$("[placeholder]").focus(function() {
							var i = $(this);
							if(i.val() == i.attr("placeholder")) {
								i.val("").removeClass("placeholder");
								if(i.hasClass("password")) {
									i.removeClass("password");
								}
							}
						}).blur(function() {
							var i = $(this);	
							if(i.val() == "" || i.val() == i.attr("placeholder")) {
								i.addClass("placeholder").val(i.attr("placeholder"));
								if(this.type=="password") {
									i.addClass("password");
								}
							}
						}).blur().parents("form").submit(function() {
							$(this).find("[placeholder]").each(function() {
								var i = $(this);
								if(i.val() == i.attr("placeholder"))
									i.val("");
							})
						});
					}
				});
            </script>
			<script type="text/javascript" src="resources/js/selectivizr-min.js"></script>
        <![endif]-->
        <!--[if lte IE 7]>
        	<link href="resources/css/ie7.css" rel="stylesheet">
        <![endif]-->
		<!--[if lte IE 6]>
			<link href="resources/css/ie6.css" rel="stylesheet">
			<script type="text/javascript" src="resources/js/ie6.js"></script>
		<![endif]-->';}?>
	</head>
    <body>        
        <div class="navbar navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
					<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</a>
					<a class="brand" href="?"><?php echo $NeptuneCore->var_get("config","sitename"); ?></a>
					<div class="nav-collapse">
						<ul class="nav">
							<?php echo "\n";
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
                        <ul class="nav"><?php
							if (neptune_get_permissions() == 0) {
								echo '</ul><ul class="nav pull-right"><li id="menu_login"><a href="?login/' . implode("/",$NeptuneCore->var_get("system","query")) . '">' . $NeptuneCore->var_get("locale","login") . '</a></li>' . "\n" . '                            <li id="menu_register"><a href="?register/' . implode("/",$NeptuneCore->var_get("system","query")) . '">' . $NeptuneCore->var_get("locale","register") . '</a></li>' . "\n";
							} else if (neptune_get_permissions() >= 1) {
								echo '</ul><ul class="pull-right nav"><li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">' . neptune_get_username() . ' <b class="caret"></b></a><ul class="dropdown-menu"><li><a href="?logout/' . implode("/",$NeptuneCore->var_get("system","query")) . '"><div class="symbol">X</div> ' . $NeptuneCore->var_get("locale","logout") . '</a></li><li class="divider"></li><li><a href="?profile"><div class="symbol">p</div> ' . $NeptuneCore->var_get("locale","editprofile") . '</a></li><li><a href="?ucp"><div class="symbol">U</div> ' . $NeptuneCore->var_get("locale","ucp") . '</a></li>';
								if (neptune_get_permissions() >= 3) {
									echo "\n                                    " . '<li class="divider"></li>' . "\n                                    " . '<li><a href="?acp"><div class="symbol">S</div> ' . $NeptuneCore->var_get("locale","acp") . '</a></li>';
								}
								echo '</ul></li>' . "\n";
							} ?>
                        </ul>
					</div>
				</div>
            </div>
        </div>

        <div class="container">
            <div class="content">
                <!--<ul class="breadcrumb"></ul>-->
                <div class="content-area">
                    <h2><?php echo $NeptuneCore->var_get("output","title_prepend") . $NeptuneCore->var_get("output","title") . $NeptuneCore->var_get("output","title_append"); ?></h2>
                    <?php
                        if ($NeptuneCore->var_get("output","subtitle") != "") {
                            echo "<p><small>" . $NeptuneCore->var_get("output","subtitle") . "</small></p>\n";
                        }
                    ?>

                    <hr>
                    <?php echo "\n" . $NeptuneCore->var_get("output","body") . "\n"; ?>
                </div>
	            <footer>
	            	<hr>
	                <div id="StormDEVLogo"></div><p><small>Copyright © 2012 StormDEV, All Rights Reserved<br>Page generated in <?php $time = microtime(); $endtime=substr($time,11).substr($time,1,9); echo round($endtime - $starttime,3) * 1000; ?> ms with <?php echo $NeptuneCore->var_get("system","querycount"); ?> queries and <?php $RAM["raw"] = memory_get_peak_usage(true);$unit=array('bytes','KiB','MiB','GiB','TiB','PiB');$RAM["converted"] = @round($RAM["raw"]/pow(1024,($i=floor(log($RAM["raw"],1024)))),2).' '.$unit[$i]; echo $RAM["converted"]; ?> of RAM<br>Using the <?php echo NeptuneSQL::type(); ?> database engine</small></p>
	            </footer>
        	</div>
		</div>
	</body>
</html>