<?php
	/*
		Neptune Content Management System
		User Accounts - /system/core/useraccounts.php

		Manages user accouts
	*/

	if(!defined('NepNep')) {
		die('NO U');
	}


	function mod_core_register() {
		global $NeptuneCore;
		global $NeptuneSQL;
			
		if (isset($_POST["submit"])) {
			// Create new SQL class if it doesn't already exist. 
			if(!isset($NeptuneSQL)) {
				$NeptuneSQL = new NeptuneSQL();
			}
			
			$Username = strtolower($NeptuneSQL->escape_string($_POST["user"]));
			$Displayname = $NeptuneSQL->escape_string($_POST["user"]);
			$Password = $NeptuneSQL->escape_string($_POST["pass1"]);
			$Email = $NeptuneSQL->escape_string($_POST["email"]);
			
			$Password = hash("sha512",$Username . $Password);
			
			if($_POST["pass1"] == $_POST["pass2"]) {
				$sql = $NeptuneSQL->query("SELECT * FROM `neptune_users` WHERE `username` = '$Username'");
				if ($NeptuneSQL->fetch_array($sql)) {
					$NeptuneCore->fatal_error("Username already taken.");
				} else {
					$sql = $NeptuneSQL->query("INSERT INTO `neptune_users` VALUES('$Username','$Displayname','$Password','$Email','0','1','" . date ("Y-m-d H:i:s") . "','" . date ("Y-m-d H:i:s") . "','0','0','','')");
					setcookie("NeptuneUser", $Username, 2147483647, "/");
					setcookie("NeptunePass", $Password, 2147483647, "/");

					$QueryString = $NeptuneCore->var_get("system","query");
					unset($QueryString[0]);
					header("Location: ?" . implode("/",$QueryString));
				}
			} else {
				$NeptuneCore->fatal_error("Passwords do not match.");
			}
		} else {
			$QueryString = $NeptuneCore->var_get("system","query");
			unset($QueryString[0]);
			
			$NeptuneCore->neptune_title("Create Account");
			$NeptuneCore->neptune_echo('<form action="?register/' . implode("/",$QueryString) . '" method="POST"><div class="clearfix"><input class="large" type="text" placeholder="Username" name="user" /></div><div class="clearfix"><input class="large" type="password" placeholder="Password" name="pass1" /></div><div class="clearfix"><input class="large" type="password" placeholder="Password (confirm)" name="pass2" /></div><div class="clearfix"><input class="large" type="text" placeholder="Email (optional)" name="email" /></div><div class="clearfix"><button class="btn primary" type="submit" name="submit">Register</button></div></form>');
			$NeptuneCore->neptune_active("register-button");			
		}
	}
	$this->hook_function("register","core","register");
	
	function mod_core_login() {
		global $NeptuneCore;
		global $NeptuneSQL;
			
		if (isset($_POST["submit"])) {
			// Create new SQL class if it doesn't already exist. 
			if(!isset($NeptuneSQL)) {
				$NeptuneSQL = new NeptuneSQL();
			}
		
			$Username = strtolower($NeptuneSQL->escape_string($_POST["user"]));
			$Password = $NeptuneSQL->escape_string($_POST["pass"]);
			
			$Password = hash("sha512",$Username . $Password);
			
			$sql = $NeptuneSQL->query("SELECT * FROM `neptune_users` WHERE `username` = '$Username' AND `password` = '$Password'");
			if ($NeptuneSQL->fetch_array($sql)) {
				setcookie("NeptuneUser", $Username, 2147483647, "/");
				setcookie("NeptunePass", $Password, 2147483647, "/");
				
				$QueryString = $NeptuneCore->var_get("system","query");
				unset($QueryString[0]);
				header("Location: ?" . implode("/",$QueryString));
			} else {
				$NeptuneCore->neptune_title("Login Failed");
				$NeptuneCore->neptune_echo("<p>Incorrect username and/or password.</p>");
			}
		} else {
			$QueryString = $NeptuneCore->var_get("system","query");
			unset($QueryString[0]);
			
			$NeptuneCore->neptune_title("Login");
			$NeptuneCore->neptune_echo('<form action="?login/' . implode("/",$QueryString) . '" method="POST"><div class="clearfix"><input class="large" type="text" placeholder="Username" name="user" /></div><div class="clearfix"><input class="large" type="password" placeholder="Password" name="pass" /></div><div class="clearfix"><button class="btn primary" type="submit" name="submit">Login</button></div></form>');
			$NeptuneCore->neptune_active("login-button");
		}
	}
	$this->hook_function("login","core","login");
	
	function mod_core_logout() {
		global $NeptuneCore;

		setcookie("NeptuneUser", "", 2147483647, "/");
		setcookie("NeptunePass", "", 2147483647, "/");
			
		$QueryString = $NeptuneCore->var_get("system","query");
		unset($QueryString[0]);
		header("Location: ?" . implode("/",$QueryString));

	}
	$this->hook_function("logout","core","logout");
	
	function neptune_get_permissions() {
		global $NeptuneCore;
		global $NeptuneSQL;
		
		if (isset($_COOKIE["NeptuneUser"]) && isset($_COOKIE["NeptunePass"])) {
			// Create new SQL class if it doesn't already exist. 
			if(!isset($NeptuneSQL)) {
				$NeptuneSQL = new NeptuneSQL();
			}
			
			$Username = $NeptuneSQL->escape_string($_COOKIE["NeptuneUser"]);
			$Password = $NeptuneSQL->escape_string($_COOKIE["NeptunePass"]);

			$sql = $NeptuneSQL->query("SELECT * FROM `neptune_users` WHERE `username` = '$Username' AND `password` = '$Password'");
			$result = $NeptuneSQL->fetch_array($sql);

			return $result["permissions"];
		} else {
			return 0;
		}
	}
		
	function neptune_get_username() {
		global $NeptuneCore;
		global $NeptuneSQL;
		
		if (neptune_get_permissions()) {
			// Create new SQL class if it doesn't already exist. 
			if(!isset($NeptuneSQL)) {
				$NeptuneSQL = new NeptuneSQL();
			}
			
			$Username = $NeptuneSQL->escape_string($_COOKIE["NeptuneUser"]);
			$Password = $NeptuneSQL->escape_string($_COOKIE["NeptunePass"]);
			
			$sql = $NeptuneSQL->query("SELECT * FROM `neptune_users` WHERE `username` = '$Username' AND `password` = '$Password'");
			$result = $NeptuneSQL->fetch_array($sql);
			
			return $result["displayname"];
		} else {
			return "Guest";
		}
	}
	
	function neptune_get_username_from_id($Username) {
		global $NeptuneCore;
		global $NeptuneSQL;
		
		// Create new SQL class if it doesn't already exist. 
		if(!isset($NeptuneSQL)) {
			$NeptuneSQL = new NeptuneSQL();
		}
		
		$Username = strtolower($Username);
						
		$sql = $NeptuneSQL->query("SELECT * FROM `neptune_users` WHERE `username` = '$Username'");
		$result = $NeptuneSQL->fetch_array($sql);
		
		return $result["displayname"];
	}
?>
