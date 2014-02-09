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
		} else if ($query[1] == "") {
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
			$NeptuneCore->subtitle("That's an error.");
			$NeptuneCore->neptune_echo("<p>Our server was unable to locate the page you requested.</p><p>This is usually caused by one of the following:</p><ul><li>The page was moved, renamed, or deleted</li><li>You, or someone else, mistyped the URL</li><li>You bookmarked a page, then time traveled into the past before the page existed</li></ul>");
			if (neptune_get_permissions() >= 3) {
				$NeptuneCore->neptune_echo("If you want, you can <a href='?acp/page/new/" . $query[1] . "'>create this page</a>.");
			}
				
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

		// Just in case someone types "post" instead of "article", redirect them to what they were looking for. 
		if ($query[0] == "post") {
			$query[0] = "article";
			@header("Location: ?" . implode($query,"/"));
		}

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
			$NeptuneCore->title("404 Post Not Found");
			$NeptuneCore->subtitle("That's an error.");
			$NeptuneCore->neptune_echo("<p>Our server was unable to locate the post you requested. Please check and make sure you did not make any mistakes when typing out the URL.</p>");
		}
	}
	$NeptuneCore->hook_function("article","core","article");
	$NeptuneCore->hook_function("post","core","article"); // We bind this function to two hooks just in case someone types post instead of article. 


	function mod_core_blog() {
		global $NeptuneCore;
		global $NeptuneSQL;

		// Create new SQL class if it doesn't already exist. 
		if( !isset($NeptuneSQL)) {
			$NeptuneSQL = new NeptuneSQL();
		}

		$query = $NeptuneCore->var_get("system","query");
		
		$NeptuneCore->var_set("theme","altlayout","layout_blog"); 
		
		$InitialQuery = $query;
		if (!array_key_exists(1,$query)) {
			$query[1] = 1;
		}

		$PostsPerPage = 10;
		$Offset = ( $query[1] * $PostsPerPage) - $PostsPerPage;
		
		$OffsetNext = $Offset + $PostsPerPage;
		$sql = $NeptuneSQL->query("SELECT * FROM `neptune_blog` ORDER BY `id` DESC LIMIT {$PostsPerPage} OFFSET {$OffsetNext} ");
		if ($result = $NeptuneSQL->fetch_array($sql)) {
			$MorePosts = true;
		} else {
			$MorePosts = false;
		}
		
		$sql = $NeptuneSQL->query("SELECT * FROM `neptune_blog` ORDER BY `id` DESC LIMIT {$PostsPerPage} OFFSET {$Offset}");


		$Results = 0;
		while ($result = $NeptuneSQL->fetch_array($sql)) {
			$Results++;
			$NeptuneCore->title($result["title"]);
			
			if (neptune_get_permissions() >= 3) {
				$NeptuneCore->var_set("output","title_prepend","<a href='?acp/article/edit/" . $result["id"] . "'><!--[if !IE]>--><img src='resources/img/edit.svg' class='editButton'><!--<![endif]--><!--[if IE]><img src='resources/img/edit.png' class='editButton'><![endif]--></a>");
			}
			
			if ($result["editor"]) {
				$EditedString = ", and last edited by " . neptune_get_username_from_id($result["editor"]) . " on " . date(" F jS, Y ", strtotime($result['edited'])) . "at" . date(" g:i A", strtotime($result['edited']));
			} else {
				$EditedString = "";
			}
			
			$NeptuneCore->subtitle("Page created by " . neptune_get_username_from_id($result["author"]) . " on" . date(" F jS, Y ", strtotime($result['created'])) . "at" . date(" g:i A", strtotime($result['created'])) . $EditedString);

			if ($result["bbcode"] == 1) {
				$NeptuneCore->neptune_echo_bbcode($result["content"],2000);
			} else if ($result["bbcode"] == 2) {
				$NeptuneCore->neptune_echo_markdown($result["content"],true,2000);
			} else if ($result["bbcode"] == 3) {
				$NeptuneCore->neptune_echo_textile($result["content"],false,2000);
			} else {
				$NeptuneCore->neptune_echo(truncateHtml($result["content"],2000));
			}
			$NeptuneCore->neptune_echo("<p><a href='?article/{$result["id"]}'>Read more...</a></p>");
			require('theme/bootstrap/snippet_blog_article.php');
		}
		
		if ($Results == 0) {
			$NeptuneCore->var_del("theme","altlayout");
			$NeptuneCore->title("404 No Results Found");
			$NeptuneCore->neptune_echo("No results were found for the requested query.");
		} else {
			if (!array_key_exists(1,$InitialQuery)) {
				$NeptuneCore->var_set("output","rawtitle",true);
				$NeptuneCore->title($NeptuneCore->var_get("config","sitename"));
			} else {
				$NeptuneCore->title("Page " . ($Offset + $PostsPerPage) / $PostsPerPage);
			}
		}
		
		if ($query[1] > 1 || $MorePosts) {
			$NeptuneCore->neptune_echo("<hr>");
		}
		
		if ($query[1] > 1) {
			$NeptuneCore->neptune_echo("<a href='?blog/" . ($query[1] - 1) . "' class='float-right'>Newer posts »</a>");
			$NeptuneCore->neptune_echo("&nbsp;");
		}
		if ($MorePosts) {
			$NeptuneCore->neptune_echo("<a href='?blog/" . ($query[1] + 1) . "'>« Older posts</a>");
		}
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
      $TextTransform = $NeptuneSQL->escape_string($_POST["ttmode"]);
      
			$Time = date ("Y-m-d H:i:s",time());

			$NeptuneSQL->query("INSERT INTO `neptune_blog` VALUES(NULL,'$PostTitle','$PostContent','$Username','$Time','$Username','$Time','$TextTransform','1','0')");
			header("Location: ?article/latest");
		} else {
			$NeptuneCore->title("New Article");
			$NeptuneCore->neptune_echo("<form class='acp' action='?acp/article/new' method='POST'><div class='clearfix'><input type='text' placeholder='Post Name' name='posttitle' /></div>\n<div class='clearfix'><select name='ttmode' id='admin-ttmode'><option value='0'>Raw HTML</option><option value='1'>Forum BBCode</option><option value='2'>Markdown</option><option value='3'>Textile</option></select></div>\n<div class='clearfix'><textarea name='postcontent'></textarea></div><div class='clearfix'><span><input type='submit' class='btn btn-primary' value='Create'/></span></div></form>");
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
      $TextTransform = $NeptuneSQL->escape_string($_POST["ttmode"]);
      
			$Time = date ("Y-m-d H:i:s",time());

			$NeptuneSQL->query("UPDATE `neptune_blog` SET `title` = '$PostTitle', `content` = '$PostContent', `editor` = '$Username', `edited` = '$Time', `bbcode` = '$TextTransform'  WHERE `id` = '" . $NeptuneSQL->escape_string($query[3]) . "'");

			header("Location: ?article/{$query[3]}");
		} else { 
			$sql = $NeptuneSQL->query("SELECT * FROM `neptune_blog` WHERE `id` = '" . $NeptuneSQL->escape_string($query[3]) . "'");

			$result = $NeptuneSQL->fetch_array($sql);
					
			$NeptuneCore->title("Editing " . $result["title"]);
			$NeptuneCore->neptune_echo("<form class='acp' action='?acp/article/edit/" . $query[3] . "' method='POST'><div class='clearfix'><input type='text' placeholder='Post Name' name='posttitle' value='" . $result["title"] . "' /></div>\n<div class='clearfix'><select name='ttmode' id='admin-ttmode'><option value='0'>Raw HTML</option><option value='1'>Forum BBCode</option><option value='2'>Markdown</option><option value='3'>Textile</option></select></div>\n<div class='clearfix'><textarea name='postcontent'>" . $result["content"] . "</textarea></div><div class='clearfix'><span><input type='submit' class='btn btn-primary' value='Save'/></span></div></form>");
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
      $TextTransform = $NeptuneSQL->escape_string($_POST["ttmode"]);
			$Time = date ("Y-m-d H:i:s",time());

			$NeptuneSQL->query("INSERT INTO `neptune_pages` VALUES('$PageID','$PageTitle','$PageContent','$Username','$Time','$Username','$Time','$TextTransform','1')");

			header("Location: ?page/$PageID");
		} else {
			$query = $NeptuneCore->var_get("system","query");

			if (array_key_exists(3,$query)) {
				$PageID = $query[3];
			} else {
        $PageID = "";
			}

			$NeptuneCore->title("New Page");
			$NeptuneCore->neptune_echo("<form class='acp' action='?acp/page/new' method='POST'>\n<div class='clearfix'><input type='text' placeholder='Page ID' name='pageid' value='" . $PageID . "' /></div><div class='clearfix'><input type='text' placeholder='Page Name' name='pagetitle' /></div>\n<div class='clearfix'><select name='ttmode' id='admin-ttmode'><option value='0'>Raw HTML</option><option value='1'>Forum BBCode</option><option value='2'>Markdown</option><option value='3'>Textile</option></select></div>\n<div class='clearfix'><textarea name='pagecontent'></textarea></div><div class='clearfix'><span><input type='submit' class='btn btn-primary' value='Create'/></span></div></form>");
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
			$TextTransform = $NeptuneSQL->escape_string($_POST["ttmode"]);
			

			$Time = date ("Y-m-d H:i:s",time());

			$NeptuneSQL->query("UPDATE `neptune_pages` SET `pid` = '$PageID', `name` = '$PageTitle', `content` = '$PageContent', `editor` = '$Username', `edited` = '$Time', `bbcode` = '$TextTransform' WHERE `pid` = '" . $NeptuneSQL->escape_string($query[3]) . "'");

			header("Location: ?page/$PageID");
		} else { 
			$sql = $NeptuneSQL->query("SELECT * FROM `neptune_pages` WHERE `pid` = '" . $NeptuneSQL->escape_string($query[3]) . "'");

			$result = $NeptuneSQL->fetch_array($sql);
					
			$NeptuneCore->title("Editing " . $result["name"]);
			$NeptuneCore->neptune_echo("<form class='acp' action='?acp/page/edit/" . $query[3] . "' method='POST'>\n<div class='clearfix'><input type='text' placeholder='Page ID' name='pageid' value='" . $query[3] . "' /></div><div class='clearfix'><input type='text' placeholder='Page Name' name='pagetitle' value='" . $result["name"] . "' /></div>\n<div class='clearfix'><select name='ttmode' id='admin-ttmode'><option value='0'>Raw HTML</option><option value='1'>Forum BBCode</option><option value='2'>Markdown</option><option value='3'>Textile</option></select></div>\n<div class='clearfix'><textarea name='pagecontent'>" . $result["content"] . "</textarea></div><div class='clearfix'><span><input type='submit' class='btn btn-primary' value='Save'/></span></div></form>");
			$NeptuneCore->var_set("output","notidy", true);
		}
	}
	
	function acp_menu_edit() {
		global $NeptuneCore, $NeptuneSQL, $NeptuneAdmin;
		
		// Create new SQL class if it doesn't already exist. 
		if( !isset($NeptuneSQL)) {
			$NeptuneSQL = new NeptuneSQL();
		}
		
		$query = $NeptuneCore->var_get("system","query");	

		if (array_key_exists(3,$query) && array_key_exists(4,$query)) {
			if ($query[3] == "delete") {
				$ID = $NeptuneSQL->escape_string($query[4]);

				$sql = $NeptuneSQL->query("DELETE FROM `neptune_menu` WHERE `id` = '$ID'");

				header("Location: ?acp/menu/edit");
			} else if ($query[3] == "add") {
				$Name = $NeptuneSQL->escape_string($_POST["name"]);
				$Name = $NeptuneSQL->escape_string($_POST["name"]);
			} else if ($query[3] == "edit") {
				$NeptuneCore->alert("Edited menu item with ID " . $query[4],"info");
			} else if ($query[3] == "move-forward") {
				$NeptuneCore->alert("Moved menu item with ID " . $query[4],"info");
			} else if ($query[3] == "move-backward") {
				$NeptuneCore->alert("Moved menu item with ID " . $query[4],"info");
			}  
		}

		if (@$_REQUEST["action"] == "delete" && $_GET["id"] != "") {
			$id = $NeptuneSQL->escape_string($_GET["id"]);
			$NeptuneSQL->query("DELETE FROM `neptune_menu` WHERE `id` = '" . $id . "'");
		} else if (@$_REQUEST["action"] == "add") {
			$name = $NeptuneSQL->escape_string($_POST["name"]);
			$path = $NeptuneSQL->escape_string($_POST["path"]);
			$NeptuneSQL->query("INSERT INTO `neptune_menu`(`id`, `position`, `path`, `name`, `type`) VALUES (NULL,'0','$path','$name','$type')");
		}

		$NeptuneCore->title("Edit Menu");
		$NeptuneCore->subtitle("Edit the list of links in the navigation bar.");
	
		$NeptuneCore->neptune_echo('<form action="?acp/menu/edit/add" method="POST" id="addMenuItemForm"><div class="modal hide fade" id="addMenuItem"  tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"><div class="modal-header"><h3>Add Menu Item</h3></div><div class="modal-body"><p><input type="text" name="name" placeholder="Menu Item Name"></p><p><input type="text" name="path" placeholder="Menu Item Path"></p><p><input type="text" name="parent" placeholder="Menu Item Parent"></p></div><div class="modal-footer"><button href="#" class="btn" type="reset" class="close" data-dismiss="modal" aria-hidden="true">Cancel</button><button type="submit" class="btn btn-primary">Add Item</button></div></div></form>');

		$sql = $NeptuneSQL->query("SELECT * FROM `neptune_menu` WHERE `parent` = '[root]' ORDER BY `position` ASC");
		 	
		//if ($NeptuneSQL->num_rows($sql) > 0) {
			$NeptuneCore->neptune_echo('<div class="navbar"><div class="navbar-inner"><a class="brand" href="#">' . $NeptuneCore->var_get("config","sitename") . '</a><div class="container" style="width: auto; padding: 0 20px;"><a class="brand" id="InteractiveText" href="#">Interactive Menu Editor</a><ul class="nav">');
			while ($result = $NeptuneSQL->fetch_array($sql)) {
        $NeptuneCore->neptune_echo('<li class="divider-vertical"></li><li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">' . $result["name"] . ' <b class="caret"></b></a><ul class="dropdown-menu"><li class="nav-header">Menu Management</li><li><a href="?acp/menu/edit/move-backward/' . $result["id"] . '"><i class="icon-arrow-left"></i> Move this left</a></li><li><a href="?acp/menu/edit/move-forward/' . $result["id"] . '"><i class="icon-arrow-right"></i> Move this right</a></li><li><a href="?acp/menu/edit/edit/' . $result["id"] . '"><i class="icon-edit"></i> Edit item</a></li><li><a href="?acp/menu/edit/delete/' . $result["id"] . '"><i class="icon-remove"></i> Remove item</a></li>');
        
        $sql2 = $NeptuneSQL->query("SELECT * FROM `neptune_menu` WHERE `parent` = '" . $result["id"] . "' ORDER BY `position` ASC");
        
        if ($NeptuneSQL->num_rows($sql2) > 0) {
          while ($result2 = $NeptuneSQL->fetch_array($sql2)) {
            $NeptuneCore->neptune_echo('<li class="divider"></li><li class="dropdown-submenu"><a tabindex="-1" href="#">' . $result2["name"] . '</a><ul class="dropdown-menu"><li class="nav-header">Menu Management</li><li><a href="?acp/menu/edit/move-backward/' . $result2["id"] . '"><i class="icon-arrow-up"></i> Move this up</a></li><li><a href="?acp/menu/edit/move-forward/' . $result2["id"] . '"><i class="icon-arrow-down"></i> Move this down</a></li><li><a href="?acp/menu/edit/edit/' . $result2["id"] . '"><i class="icon-edit"></i> Edit item</a></li><li><a href="?acp/menu/edit/delete/' . $result2["id"] . '"><i class="icon-remove"></i> Remove item</a></li></ul></li>');
          }
        }
        
        $NeptuneCore->neptune_echo('</ul></li>');
			}
			$NeptuneCore->neptune_echo('<li class="divider-vertical"></li><li><a class="brand" id="addMenuItemButton" href="#addMenuItem" role="button" data-toggle="modal">+</a></li></ul></div></div></div>');
		//} else {
		//	$NeptuneCore->neptune_echo("The menu is currently empty.");
		//}
	}
	$NeptuneAdmin->add_hook("Core","menu/edit","Edit Menu","Edit the list of links in the navigation bar.");
?>
