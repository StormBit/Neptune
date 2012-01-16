<?php
	/*
		Neptune Content Management System
		User Accounts - /system/core/useraccounts.php

		Manages user accouts
	*/

	function mod_core_register() {
		global $NeptuneCore;
		global $NeptuneSQL;
			
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
				die("Username already taken.");
			} else {
				$sql = $NeptuneSQL->query("INSERT INTO `neptune_users`(`username`,`displayname`,`password`,`email`) VALUES('$Username','$Displayname','$Password','$Email')");
				setcookie("NeptuneUser", $Username, 2147483647, "/");
				setcookie("NeptunePass", $Password, 2147483647, "/");

				$QueryString = $NeptuneCore->var_get("system","query");
				unset($QueryString[0]);
				header("Location: ?" . implode("/",$QueryString));
			}
		} else {
			die("Passwords do not match.");
		}
	}
	$this->hook_function("register","core","register");
	
	function mod_core_login() {
		global $NeptuneCore;
		global $NeptuneSQL;
			
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
			die("Invalid credentials");
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
		
		// Create new SQL class if it doesn't already exist. 
		if(!isset($NeptuneSQL)) {
			$NeptuneSQL = new NeptuneSQL();
		}
		
		if (isset($_COOKIE["NeptuneUser"]) && isset($_COOKIE["NeptunePass"])) {
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
		
		// Create new SQL class if it doesn't already exist. 
		if(!isset($NeptuneSQL)) {
			$NeptuneSQL = new NeptuneSQL();
		}
		
		if (neptune_get_permissions()) {
			$Username = $NeptuneSQL->escape_string($_COOKIE["NeptuneUser"]);
			$Password = $NeptuneSQL->escape_string($_COOKIE["NeptunePass"]);
			
			$sql = $NeptuneSQL->query("SELECT * FROM `neptune_users` WHERE `username` = '$Username' AND `password` = '$Password'");
			$result = $NeptuneSQL->fetch_array($sql);
			
			return $result["displayname"];
		} else {
			return "Guest";
		}
	}
?>