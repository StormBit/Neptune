<?php
	/*
		Neptune Content Management System
		User Accounts - /system/core/useraccounts.php

		Manages user accouts
	*/

	function mod_core_register() {
		global $NeptuneCore;
		global $NeptuneSQL;
				
		if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 6') !== false)) {
			$NeptuneCore->fatal_error($NeptuneCore->var_get("locale","ie6useraccounts"));
		} else {
			if (isset($_POST["submit"])) {
				// Create new SQL class if it doesn't already exist. 
				if(!isset($NeptuneSQL)) {
					$NeptuneSQL = new NeptuneSQL();
				}
				
				$Username = strtolower($NeptuneSQL->escape_string($_POST["user"]));
				$Displayname = $NeptuneSQL->escape_string($_POST["user"]);
				$Password = $NeptuneSQL->escape_string($_POST["pass1"]);
				$Email = $NeptuneSQL->escape_string($_POST["email"]);
				
				$Password = hash("sha256", $NeptuneCore->var_get("auth","key") . $Username . $Password);
				
				if($_POST["pass1"] == $_POST["pass2"]) {
					$sql = $NeptuneSQL->query("SELECT * FROM `neptune_users` WHERE `username` = '$Username'");
					if ($NeptuneSQL->fetch_array($sql)) {
						$NeptuneCore->fatal_error($NeptuneCore->var_get("locale","usernametaken"));
					} else {
						if($Username == "")
						{
							$NeptuneCore->fatal_error($NeptuneCore->var_get("locale","usernameempty"));
						} else {
							$sql = $NeptuneSQL->query("INSERT INTO `neptune_users` VALUES('$Username','$Displayname','$Password','$Email','0','1','" . date ("Y-m-d H:i:s") . "','" . date ("Y-m-d H:i:s") . "','0','0','','')");
							setcookie("NeptuneUser", $Username, 2147483647, "/");
							setcookie("NeptunePass", $Password, 2147483647, "/");

							$QueryString = $NeptuneCore->var_get("system","query");
							unset($QueryString[0]);
							header("Location: ?" . implode("/",$QueryString));
						}
					}
				} else {
					$NeptuneCore->alert($NeptuneCore->var_get("locale","mismatchedpass"),"error");
					goto displayregister;
				}
			} else {
				displayregister:

				$QueryString = $NeptuneCore->var_get("system","query");
				unset($QueryString[0]);
				
				$NeptuneCore->title($NeptuneCore->var_get("locale","createaccount"));
				$NeptuneCore->neptune_echo('<form action="?register/' . implode("/",$QueryString) . '" method="POST"><div class="clearfix"><input class="large" type="text" placeholder="' . $NeptuneCore->var_get("locale","username") . '" name="user" /></div><div class="clearfix"><input class="large" type="password" placeholder="' . $NeptuneCore->var_get("locale","password") . '" name="pass1" /></div><div class="clearfix"><input class="large" type="password" placeholder="' . $NeptuneCore->var_get("locale","passwordconfirm") . '" name="pass2" /></div><div class="clearfix"><input class="large" type="text" placeholder="' . $NeptuneCore->var_get("locale","emailoptional") . '" name="email" /></div><div class="clearfix"><button class="btn btn-primary" type="submit" name="submit">' . $NeptuneCore->var_get("locale","register") . '</button></div></form>');
				$NeptuneCore->neptune_active("register-button");			
			}
		}
	}
	$this->hook_function("register","core","register");
	
	function mod_core_login() {
		global $NeptuneCore;
		global $NeptuneSQL;
		
		if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 6') !== false)) {
			$NeptuneCore->fatal_error($NeptuneCore->var_get("locale","ie6useraccounts"));
		} else {
			if (isset($_POST["submit"])) {
				// Create new SQL class if it doesn't already exist. 
				if(!isset($NeptuneSQL)) {
					$NeptuneSQL = new NeptuneSQL();
				}
			
				$Username = strtolower($NeptuneSQL->escape_string($_POST["user"]));
				$Password = $NeptuneSQL->escape_string($_POST["pass"]);
				
				$Password = hash("sha256", $NeptuneCore->var_get("auth","key") . $Username . $Password);
				
				$sql = $NeptuneSQL->query("SELECT * FROM `neptune_users` WHERE `username` = '$Username' AND `password` = '$Password'");
				if ($NeptuneSQL->fetch_array($sql)) {
					setcookie("NeptuneUser", $Username, 2147483647, "/");
					setcookie("NeptunePass", $Password, 2147483647, "/");
					
					$QueryString = $NeptuneCore->var_get("system","query");
					unset($QueryString[0]);
					header("Location: ?" . implode("/",$QueryString));
				} else {
					$NeptuneCore->alert($NeptuneCore->var_get("locale","baduserpass"),"error");
					goto displaylogin;
				}
			} else {
				displaylogin:

				$QueryString = $NeptuneCore->var_get("system","query");
				unset($QueryString[0]);
				
				$NeptuneCore->title($NeptuneCore->var_get("locale","login"));
				$NeptuneCore->neptune_echo('<form action="?login/' . implode("/",$QueryString) . '" method="POST"><div class="clearfix"><input class="large" type="text" placeholder="' . $NeptuneCore->var_get("locale","username") . '" name="user" /></div><div class="clearfix"><input class="large" type="password" placeholder="' . $NeptuneCore->var_get("locale","password") . '" name="pass" /></div><div class="clearfix"><button class="btn btn-primary" type="submit" name="submit">' . $NeptuneCore->var_get("locale","login") . '</button></div></form>');
				$NeptuneCore->neptune_active("login-button");
			}
		}
	}
	$this->hook_function("login","core","login");
	
	function mod_core_logout() {
		global $NeptuneCore;

		if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 6') !== false)) {
			$NeptuneCore->fatal_error($NeptuneCore->var_get("locale","ie6useraccounts"));
		} else {
			setcookie("NeptuneUser", "", 2147483647, "/");
			setcookie("NeptunePass", "", 2147483647, "/");
				
			$QueryString = $NeptuneCore->var_get("system","query");
			unset($QueryString[0]);
			header("Location: ?" . implode("/",$QueryString));
		}
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
		
	function neptune_get_username($display = true) {
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
			
			if ($display) {
				return $result["displayname"];
			} else {
				return $result["username"];
			}
		} else {
			return "Guest";
		}
	}
	
	function neptune_get_email() {
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
			
			if ($result["email"] != "") {
				$email = $result["email"];
			} else {
				$email = $NeptuneCore->var_get("locale","noemail");
			}
			return $email;
		} else {
			return "N/A";
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
