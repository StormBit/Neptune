<?php
	/*
		Neptune Content Management System
		User Accounts - /system/core/commentengine.php

		Manages the comment engine.
	*/

	class NeptuneComments {
		function __construct() {
		 	global $NeptuneCore;
			global $NeptuneSQL;
			
			// Create new SQL class if it doesn't already exist. 
			if( !isset($NeptuneSQL)) {
				$NeptuneSQL = new NeptuneSQL();
			}
		}
		
		function scan($resource) {
			global $NeptuneCore;
			global $NeptuneSQL;
								
								
			$sql = $NeptuneSQL->query("SELECT * FROM `neptune_comments` WHERE `resource` = '$resource' ORDER BY `date` DESC ");
			
			$result["comments"] = $NeptuneSQL->num_rows($sql);
				
			return $result;
		}
		
		function render($resource) {
			global $NeptuneCore;
			global $NeptuneSQL;
								
			$NeptuneCore->neptune_echo("<hr><legend class='commentTitle'>Comments</legend>");
						
			$sql = $NeptuneSQL->query("SELECT * FROM `neptune_comments` WHERE `resource` = '$resource' ORDER BY `date` ASC ");
			
			
			
			if ($NeptuneSQL->num_rows($sql)) {
				$NeptuneCore->neptune_echo('<table class="table table-hover table-comments">');
				while ($result = $NeptuneSQL->fetch_array($sql)) {
					$Username = neptune_get_username_from_id($result["author"]);
					$Date = date("F j, Y", strtotime($result["date"]));
					$Time = date("g:i a",  strtotime($result["date"]));
					$Content = $result["content"];
					
					$NeptuneCore->neptune_echo("<tr><td style='width:160px;'>$Username</td><td><small>Posted on $Date at $Time</small>");
					$NeptuneCore->neptune_echo_markdown($Content);
					$NeptuneCore->neptune_echo("</td></tr>");
				}
				$NeptuneCore->neptune_echo('</table>');

			} else {
				$NeptuneCore->neptune_echo("<br><p>There are no comments.</p>");
			}
			
			
			// Comment form.
			
			if (neptune_get_permissions()) {
				$NeptuneCore->neptune_echo('
					<form class="form-horizontal" action="?comment/post/' . $resource . '" method="POST">
						<div class="control-group">
							<label class="control-label" for="commentArea">Post a comment</label>
							<div class="controls">
								<textarea style="height: 80px; width: 480px;" name="commentArea" id="commentArea"></textarea>
							</div>
							&nbsp;
							<div class="controls">
								<button type="submit" class="btn btn-primary">Submit</button>
							</div>
						</div>
					</form>');
			} else {
				$NeptuneCore->neptune_echo("<p>You must be logged in to comment.</p>");
			}
		}
		
		function post($resource) {
			global $NeptuneCore;
			global $NeptuneSQL;


			if (neptune_get_permissions()) {
				$Comment = $NeptuneSQL->escape_string($_POST["commentArea"]);
				$Username = $NeptuneSQL->escape_string(strtolower(neptune_get_username()));
				$Resource = $NeptuneSQL->escape_string(implode($resource,"/"));
				
				$Time = date("Y-m-d H:i:s",time());
				
				$NeptuneSQL->query("INSERT INTO `neptune_comments` VALUES(NULL,'$Username','$Time','$Resource','$Comment')");
				
				header("Location: ?$Resource");
			}
		}
	}
	
	function mod_comment_dispatch() {
		global $NeptuneCore;
		global $NeptuneSQL;
		global $NeptuneComments;
		
		// Create new CommentEngine class if it doesn't already exist. 
		// The CommentEngine will also create an SQL class so we don't
		// need to worry about that.
		if( !isset($NeptuneComments)) {
			$NeptuneComments = new NeptuneComments();
		}

		$query = $NeptuneCore->var_get("system","query");
		
		if (isset($query[1])) {
			if ($query[1] == "post") {
				array_shift($query);
				array_shift($query);
				
				$NeptuneComments->post($query);
			}
		}
	}
	
	$NeptuneCore->hook_function("comment","comment","dispatch");

?>
