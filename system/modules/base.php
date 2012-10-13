<?php
	/*
		Neptune Content Management System
		Base Module - /modules/base.php

		Module that does all of the basic tasks. 
	*/
	
	$NeptuneCore->register_module("neptune_base");
	
	function mod_core_page() {
		global $NeptuneCore;
		global $NeptuneSQL;

		// Create new SQL class if it doesn't already exist. 
		if( !isset($NeptuneSQL)) {
			$NeptuneSQL = new NeptuneSQL();
		}

		$query = $NeptuneCore->var_get("system","query");

		if (!array_key_exists(1,$query)) {
			$query[1] = "index";
		}

		$sql = $NeptuneSQL->query("SELECT * FROM `neptune_pages` WHERE `pid` = '" . $NeptuneSQL->escape_string($query[1]) . "'");

		if ($result = $NeptuneSQL->fetch_array($sql)) {
			$NeptuneCore->title($result["name"]);
			
			if (neptune_get_permissions() >= 3) {
				$NeptuneCore->var_set("output","title_prepend","<a href='?acp/page/edit/" . $query[1] . "'><!--[if !IE]>--><img src='resources/img/edit.svg' class='editButton'><!--<![endif]--><!--[if IE]><img src='resources/img/edit.png' class='editButton'><![endif]--></a>");
			}
			
			if ($result["editor"]) {
				$EditedString = ", and last edited by " . neptune_get_username_from_id($result["editor"]) . " on " . date(" F jS, Y ", strtotime($result['edited'])) . "at" . date(" g:i A", strtotime($result['edited']));
			} else {
				$EditedString = "";
			}
			
			$NeptuneCore->subtitle("Page created by " . neptune_get_username_from_id($result["author"]) . " on" . date(" F jS, Y ", strtotime($result['created'])) . "at" . date(" g:i A", strtotime($result['created'])) . $EditedString);

			if ($result["bbcode"] == 1) {
				$NeptuneCore->neptune_echo_bbcode($result["content"]);
			} else if ($result["bbcode"] == 2) {
				$NeptuneCore->neptune_echo_markdown($result["content"]);
			} else if ($result["bbcode"] == 3) {
				$NeptuneCore->neptune_echo_textile($result["content"],false);
			} else {
				$NeptuneCore->neptune_echo($result["content"]);
			}
		} else {
			$NeptuneCore->title("404 Page Not Found");
			$NeptuneCore->neptune_echo("Your request could not be processed, because the specified page does not exist.");
		}
	}
	$NeptuneCore->hook_function("page","core","page");

	function mod_core_article() {
		global $NeptuneCore;
		global $NeptuneSQL;

		// Create new SQL class if it doesn't already exist. 
		if( !isset($NeptuneSQL)) {
			$NeptuneSQL = new NeptuneSQL();
		}

		$query = $NeptuneCore->var_get("system","query");

		if (!array_key_exists(1,$query)) {
			$query[1] = "index";
		}
		
		if ($NeptuneSQL->escape_string($query[1]) == "latest") { 							// This chunk of code checks if the query is for the latest article.
			$sql = $NeptuneSQL->query("SELECT * FROM `neptune_blog` ORDER BY `id` DESC");	// If it is, it redirects to it. However, if no articles exist, it sends you to the index.
			if ($result = $NeptuneSQL->fetch_array($sql)) {
				header("Location: ?article/" . $result['id']);
			} else {
				header("Location: ?blog");
			}
		} else {
			$sql = $NeptuneSQL->query("SELECT * FROM `neptune_blog` WHERE `id` = '" . $NeptuneSQL->escape_string($query[1]) . "'");
		}
		
		if ($result = $NeptuneSQL->fetch_array($sql)) {
			$NeptuneCore->title($result["title"]);
			
			if (neptune_get_permissions() >= 3) {
				$NeptuneCore->var_set("output","title_prepend","<a href='?acp/article/edit/" . $query[1] . "'><!--[if !IE]>--><img src='resources/img/edit.svg' class='editButton'><!--<![endif]--><!--[if IE]><img src='resources/img/edit.png' class='editButton'><![endif]--></a>");
			}
			
			if ($result["editor"]) {
				$EditedString = ", and last edited by " . neptune_get_username_from_id($result["editor"]) . " on " . date(" F jS, Y ", strtotime($result['edited'])) . "at" . date(" g:i A", strtotime($result['edited']));
			} else {
				$EditedString = "";
			}
			
			$NeptuneCore->subtitle("Page created by " . neptune_get_username_from_id($result["author"]) . " on" . date(" F jS, Y ", strtotime($result['created'])) . "at" . date(" g:i A", strtotime($result['created'])) . $EditedString);

			if ($result["bbcode"] == 1) {
				$NeptuneCore->neptune_echo_bbcode($result["content"]);
			} else if ($result["bbcode"] == 2) {
				$NeptuneCore->neptune_echo_markdown($result["content"]);
			} else if ($result["bbcode"] == 3) {
				$NeptuneCore->neptune_echo_textile($result["content"],false);
			} else {
				$NeptuneCore->neptune_echo($result["content"]);
			}
		} else {
			$NeptuneCore->title("404 Page Not Found");
			$NeptuneCore->neptune_echo("Your request could not be processed, because the specified page does not exist.");
		}
	}
	$NeptuneCore->hook_function("article","core","article");

	function mod_core_blog() {
		global $NeptuneCore;
		global $NeptuneSQL;

		// Create new SQL class if it doesn't already exist. 
		if( !isset($NeptuneSQL)) {
			$NeptuneSQL = new NeptuneSQL();
		}

		$query = $NeptuneCore->var_get("system","query");
		
		$NeptuneCore->var_set("theme","altlayout","layout_blog"); 
		
		if (!array_key_exists(1,$query)) {
			$query[1] = "index";
		}

		$sql = $NeptuneSQL->query("SELECT * FROM `neptune_blog` ORDER BY `id` DESC");

		while ($result = $NeptuneSQL->fetch_array($sql)) {
			$NeptuneCore->title($result["title"]);
			
			if (neptune_get_permissions() >= 3) {
				$NeptuneCore->var_set("output","title_prepend","<a href='?acp/article/edit/" . $query[1] . "'><!--[if !IE]>--><img src='resources/img/edit.svg' class='editButton'><!--<![endif]--><!--[if IE]><img src='resources/img/edit.png' class='editButton'><![endif]--></a>");
			}
			
			if ($result["editor"]) {
				$EditedString = ", and last edited by " . neptune_get_username_from_id($result["editor"]) . " on " . date(" F jS, Y ", strtotime($result['edited'])) . "at" . date(" g:i A", strtotime($result['edited']));
			} else {
				$EditedString = "";
			}
			
			$NeptuneCore->subtitle("Page created by " . neptune_get_username_from_id($result["author"]) . " on" . date(" F jS, Y ", strtotime($result['created'])) . "at" . date(" g:i A", strtotime($result['created'])) . $EditedString);

			if ($result["bbcode"] == 1) {
				$NeptuneCore->neptune_echo_bbcode($result["content"]);
			} else if ($result["bbcode"] == 2) {
				$NeptuneCore->neptune_echo_markdown($result["content"]);
			} else if ($result["bbcode"] == 3) {
				$NeptuneCore->neptune_echo_textile($result["content"],false);
			} else {
				$NeptuneCore->neptune_echo($result["content"]);
			}
			
			require('theme/bootstrap/snippet_blog_article.php');
		}
		
		$NeptuneCore->var_set("output","rawtitle",true);
		$NeptuneCore->title($NeptuneCore->var_get("config","sitename"));
	}
	$NeptuneCore->hook_function("blog","core","blog");
	
	function acp_article_new() {
		global $NeptuneCore, $NeptuneSQL, $NeptuneAdmin;

		if ($_SERVER['REQUEST_METHOD'] == "POST") {
			if(!isset($NeptuneSQL)) {
				$NeptuneSQL = new NeptuneSQL();
			}

			$PostTitle = $NeptuneSQL->escape_string($_POST["posttitle"]);
			$PostContent = $NeptuneSQL->escape_string($_POST["postcontent"]);
			$Username = $NeptuneSQL->escape_string(strtolower(neptune_get_username()));

			$Time = date ("Y-m-d H:i:s",time());

			$NeptuneSQL->query("INSERT INTO `neptune_blog` VALUES(NULL,'$PostTitle','$PostContent','$Username','$Time','$Username','$Time','1','1','0')");
			header("Location: ?article/latest");
		} else {
			$NeptuneCore->title("New Article");
			$NeptuneCore->neptune_echo("<form class='acp' action='?acp/article/new' method='POST'><div class='clearfix'><input type='text' placeholder='Post Name' name='posttitle' /></div>\n<div class='clearfix'><textarea name='postcontent'></textarea></div><div class='clearfix'><span><input type='submit' class='btn btn-primary' value='Create'/></span></div></form>");
		}
	}
	$NeptuneAdmin->add_hook("Blog","article/new","New Article","Create a new blog post");
	
	function acp_article_delete() {
		global $NeptuneCore, $NeptuneSQL, $NeptuneAdmin;
		
		// Create new SQL class if it doesn't already exist. 
		if( !isset($NeptuneSQL)) {
			$NeptuneSQL = new NeptuneSQL();
		}

		$query = $NeptuneCore->var_get("system","query");	

		$sql = $NeptuneSQL->query("DELETE FROM `neptune_blog` WHERE `id` = '" . $NeptuneSQL->escape_string($query[3]) . "'");
		
		header("Location: ?acp/article/list");
	}	
	
	function acp_article_list() {
		global $NeptuneCore, $NeptuneSQL, $NeptuneAdmin;
		
		// Create new SQL class if it doesn't already exist. 
		if( !isset($NeptuneSQL)) {
			$NeptuneSQL = new NeptuneSQL();
		}
		
		$NeptuneCore->title("Article List");
		$NeptuneCore->subtitle("This is a listing of all of the articles in the database.");
		
		$sql = $NeptuneSQL->query("SELECT * FROM `neptune_blog`");
		$Articles = 0;
		while ($result = $NeptuneSQL->fetch_array($sql)) {
			if (!$Articles) {
				$NeptuneCore->neptune_echo('<table class="table table-striped small-table"><thead><tr><th></th><th>Article ID</th><th>Article Name</th></tr></thead><tbody>');
			}
			
			$Articles++;
			$NeptuneCore->neptune_echo('<tr><td style="width: 64px;"><div class="btn-group"><a class="btn btn-primary btn-mini dropdown-toggle" data-toggle="dropdown" href="javascript:;">Actions <span class="caret ie6-hide"></span></a><ul class="dropdown-menu"><li><a href="?acp/article/edit/' . $result["id"] . '"><i class="icon-edit"></i> Edit</a></li><li><a href="?acp/article/delete/' . $result["id"] . '" onclick="return confirm(\'Are you sure you want to delete the article ' . $result["title"] . ' (' . $result["id"] . ')? This operation cannot be undone.\');"><i class="icon-remove"></i> Delete</a></li></ul></div></td><td style="width: 160px;">' . $result["id"] . "</td><td>" . $result["title"] . "</td></tr>");
		}
		
		if ($Articles) {
			$NeptuneCore->neptune_echo('</tbody></table>');
		} else {
			$NeptuneCore->neptune_echo("There are no articles. <a href='?acp/article/new'>Create one now</a>.");
		}
	}
	$NeptuneAdmin->add_hook("Blog","article/list","Article List","View and edit a list of articles");
	
	function acp_article_edit() {
		global $NeptuneCore, $NeptuneSQL, $NeptuneAdmin;
		
		// Create new SQL class if it doesn't already exist. 
		if( !isset($NeptuneSQL)) {
			$NeptuneSQL = new NeptuneSQL();
		}

		$query = $NeptuneCore->var_get("system","query");	

		if (!array_key_exists(3,$query)) {
			$query[3] = "index";
		}

		if ($_SERVER['REQUEST_METHOD'] == "POST") {
			if(!isset($NeptuneSQL)) {
				$NeptuneSQL = new NeptuneSQL();
			}

			$PostTitle = $NeptuneSQL->escape_string($_POST["posttitle"]);
			$PostContent = $NeptuneSQL->escape_string($_POST["postcontent"]);
			$Username = $NeptuneSQL->escape_string(strtolower(neptune_get_username()));

			$Time = date ("Y-m-d H:i:s",time());

			$NeptuneSQL->query("UPDATE `neptune_blog` SET `title` = '$PostTitle', `content` = '$PostContent', `editor` = '$Username', `edited` = '$Time' WHERE `id` = '" . $NeptuneSQL->escape_string($query[3]) . "'");

			header("Location: ?article/{$query[3]}");
		} else { 
			$sql = $NeptuneSQL->query("SELECT * FROM `neptune_blog` WHERE `id` = '" . $NeptuneSQL->escape_string($query[3]) . "'");

			$result = $NeptuneSQL->fetch_array($sql);
					
			$NeptuneCore->title("Editing " . $result["title"]);
			$NeptuneCore->neptune_echo("<form class='acp' action='?acp/article/edit/" . $query[3] . "' method='POST'><div class='clearfix'><input type='text' placeholder='Post Name' name='posttitle' value='" . $result["title"] . "' /></div>\n<div class='clearfix'><textarea name='postcontent'>" . $result["content"] . "</textarea></div><div class='clearfix'><span><input type='submit' class='btn btn-primary' value='Save'/></span></div></form>");
			$NeptuneCore->var_set("output","notidy", true);
		}
	}
	
	function acp_page_new() {
		global $NeptuneCore, $NeptuneSQL, $NeptuneAdmin;

		if ($_SERVER['REQUEST_METHOD'] == "POST") {
			if(!isset($NeptuneSQL)) {
				$NeptuneSQL = new NeptuneSQL();
			}

			$PageID = $NeptuneSQL->escape_string($_POST["pageid"]);
			$PageTitle = $NeptuneSQL->escape_string($_POST["pagetitle"]);
			$PageContent = $NeptuneSQL->escape_string($_POST["pagecontent"]);
			$Username = $NeptuneSQL->escape_string(strtolower(neptune_get_username()));

			$Time = date ("Y-m-d H:i:s",time());

			$NeptuneSQL->query("INSERT INTO `neptune_pages` VALUES('$PageID','$PageTitle','$PageContent','$Username','$Time','$Username','$Time','1','1')");

			header("Location: ?page/$PageID");
		} else {
			$NeptuneCore->title("New Page");
			$NeptuneCore->neptune_echo("<form class='acp' action='?acp/page/new' method='POST'>\n<div class='clearfix'><input type='text' placeholder='Page ID' name='pageid' /></div><div class='clearfix'><input type='text' placeholder='Page Name' name='pagetitle' /></div>\n<div class='clearfix'><textarea name='pagecontent'></textarea></div><div class='clearfix'><span><input type='submit' class='btn btn-primary' value='Create'/></span></div></form>");
		}
	}
	$NeptuneAdmin->add_hook("Pages","page/new","New Page","Create a new page");

	function acp_page_delete() {
		global $NeptuneCore, $NeptuneSQL, $NeptuneAdmin;
		
		// Create new SQL class if it doesn't already exist. 
		if( !isset($NeptuneSQL)) {
			$NeptuneSQL = new NeptuneSQL();
		}

		$query = $NeptuneCore->var_get("system","query");	

		$sql = $NeptuneSQL->query("DELETE FROM `neptune_pages` WHERE `pid` = '" . $NeptuneSQL->escape_string($query[3]) . "'");
		
		header("Location: ?acp/page/list");
	}	
	
	function acp_page_list() {
		global $NeptuneCore, $NeptuneSQL, $NeptuneAdmin;
		
		// Create new SQL class if it doesn't already exist. 
		if( !isset($NeptuneSQL)) {
			$NeptuneSQL = new NeptuneSQL();
		}
		
		$NeptuneCore->title("Page List");
		$NeptuneCore->subtitle("This is a listing of all of the pages in the database.");
		
		$sql = $NeptuneSQL->query("SELECT * FROM `neptune_pages`");
		$Pages = 0;
		while ($result = $NeptuneSQL->fetch_array($sql)) {
			if (!$Pages) {
				$NeptuneCore->neptune_echo('<table class="table table-striped small-table"><thead><tr><th></th><th>Page ID</th><th>Page Name</th></tr></thead><tbody>');
			}
			$Pages++;
			
			$NeptuneCore->neptune_echo('<tr><td style="width: 64px;"><div class="btn-group"><a class="btn btn-primary btn-mini dropdown-toggle" data-toggle="dropdown" href="javascript:;">Actions <span class="caret ie6-hide"></span></a><ul class="dropdown-menu"><li><a href="?acp/page/edit/' . $result["pid"] . '"><i class="icon-edit"></i> Edit</a></li><li><a href="?acp/page/delete/' . $result["pid"] . '" onclick="return confirm(\'Are you sure you want to delete the page ' . $result["name"] . ' (' . $result["pid"] . ')? This operation cannot be undone.\');"><i class="icon-remove"></i> Delete</a></li></ul></div></td><td style="width: 160px;">' . $result["pid"] . "</td><td>" . $result["name"] . "</td></tr>");
		}
		if ($Pages) {
			$NeptuneCore->neptune_echo('</tbody></table>');
		} else {
			$NeptuneCore->neptune_echo("There are no pages. <a href='?acp/page/new'>Create one now</a>.");
		}
	}
	$NeptuneAdmin->add_hook("Pages","page/list","Page List","View and edit a list of pages");
	
	function acp_page_edit() {
		global $NeptuneCore, $NeptuneSQL, $NeptuneAdmin;
		
		// Create new SQL class if it doesn't already exist. 
		if( !isset($NeptuneSQL)) {
			$NeptuneSQL = new NeptuneSQL();
		}

		$query = $NeptuneCore->var_get("system","query");	

		if (!array_key_exists(3,$query)) {
			$query[3] = "index";
		}

		if ($_SERVER['REQUEST_METHOD'] == "POST") {
			if(!isset($NeptuneSQL)) {
				$NeptuneSQL = new NeptuneSQL();
			}

			$PageID = $NeptuneSQL->escape_string($_POST["pageid"]);
			$PageTitle = $NeptuneSQL->escape_string($_POST["pagetitle"]);
			$PageContent = $NeptuneSQL->escape_string($_POST["pagecontent"]);
			$Username = $NeptuneSQL->escape_string(strtolower(neptune_get_username()));

			$Time = date ("Y-m-d H:i:s",time());

			$NeptuneSQL->query("UPDATE `neptune_pages` SET `pid` = '$PageID', `name` = '$PageTitle', `content` = '$PageContent', `editor` = '$Username', `edited` = '$Time' WHERE `pid` = '" . $NeptuneSQL->escape_string($query[3]) . "'");

			header("Location: ?page/$PageID");
		} else { 
			$sql = $NeptuneSQL->query("SELECT * FROM `neptune_pages` WHERE `pid` = '" . $NeptuneSQL->escape_string($query[3]) . "'");

			$result = $NeptuneSQL->fetch_array($sql);
					
			$NeptuneCore->title("Editing " . $result["name"]);
			$NeptuneCore->neptune_echo("<form class='acp' action='?acp/page/edit/" . $query[3] . "' method='POST'>\n<div class='clearfix'><input type='text' placeholder='Page ID' name='pageid' value='" . $query[3] . "' /></div><div class='clearfix'><input type='text' placeholder='Page Name' name='pagetitle' value='" . $result["name"] . "' /></div>\n<div class='clearfix'><textarea name='pagecontent'>" . $result["content"] . "</textarea></div><div class='clearfix'><span><input type='submit' class='btn btn-primary' value='Save'/></span></div></form>");
			$NeptuneCore->var_set("output","notidy", true);
		}
	}
	
	function acp_menu_edit() {
		global $NeptuneCore, $NeptuneSQL, $NeptuneAdmin;
		
		// Create new SQL class if it doesn't already exist. 
		if( !isset($NeptuneSQL)) {
			$NeptuneSQL = new NeptuneSQL();
		}
		
		$NeptuneCore->title("Edit Menu");
		$NeptuneCore->subtitle("Edit the list of links in the navigation bar.");
				
		$NeptuneCore->neptune_echo('<table class="table table-striped small-table" id="menuedit"><thead><tr><th></th><th>Location</th><th>Label</th><th>Type</th></tr></thead><tbody>');
		
		$sql = $NeptuneSQL->query("SELECT * FROM `neptune_menu`");
		
		$ItemNumber = 0;
		
		while ($result = $NeptuneSQL->fetch_array($sql)) {
			$location = "";
			$location =  $result["path"];
			if ($result["type"] == 0) {
				$type = "Internal";
			} else {
				$type = "External";
			}
			$ItemNumber++;
			$NeptuneCore->neptune_echo('<tr id="menuedit-num-' . $ItemNumber . '"><td style="width: 64px;"><div class="btn-group"><a class="btn btn-primary btn-mini dropdown-toggle" data-toggle="dropdown" href="javascript:;">Actions <span class="caret ie6-hide"></span></a><ul class="dropdown-menu"><li><a href="javascript:;"><i class="icon-edit"></i> Rename</a></li><li><a href="javascript:;" onclick="$(\'#menuedit-num-' . $ItemNumber . '\').hide(500, function(){$(\'#menuedit-num-' . $ItemNumber . '\').remove();});"><i class="icon-remove"></i> Delete</a></li></ul></div></td><td style="width: 160px;" class="item">' . $location . "</td><td class='item'>" . $result["name"] . "</td><td>" . $type . "</td></tr>");
		}
		
		$NeptuneCore->neptune_echo('</tbody></table><hr><input type="text" placeholder="Location" id="add_location"><br><input type="text" placeholder="Link Title" id="add_title"><div class="btn-group"><button class="btn btn-primary" onclick="addInternal();">Add Internal</button><button class="btn">Add External</button></div><script type="text/javascript">var fixHelper = function(e, ui){ui.children().each(function() {$(this).width($(this).width());});return ui;}; $("#menuedit tbody").sortable({helper: fixHelper}); $("#menuedit tbody tr td.item").disableSelection();var ItemNumber = ' . $ItemNumber . ';</script>');
	}
	$NeptuneAdmin->add_hook("Core","menu/edit","Edit Menu","Edit the list of links in the navigation bar.");
?>
