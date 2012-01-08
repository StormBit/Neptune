<?php
	/*
		Neptune Content Management System
		Cache Management! - /system/core/cache.php

		This will be the object to manage external caches
		(such as APC, memcached or XCache).
	*/

	// Checking if NepNep is defined, for security purposes.	
	if(!defined('NepNep')) {
		die('NO U');
	}
	
	// if the cache expiry variable is not set, obviously no expiry is needed
	if(NeptuneCore::var_get('cache', 'expire')) {
		$expire = NeptuneCore::var_get('cache', 'expire');
	} else {
		$expire = false;
	}
	
	// time to determine which cache has been selected
	// (atm supporting several at once will complicate things)
	// APC will be preferred for simplicity
	switch(NeptuneCore::var_get('cache', 'type')) {
		case 'apc':
			$cache = new apc_neptune();
			define('cache_available', true, true);
		break;
		
		case 'memcached':
			if(!(NeptuneCore::var_get('cache', 'memcached'))) {
				$cache = new memcached_neptune();
				define('cache_available', true, true);
			}
			else {
				// we need memcached servers to function if this is enabled, so throw error.
				die('FATAL ERROR: You haven\'t set any memcached servers in config!');
			}
		break;
		
		case 'xcache':
			$cache = new xcache_neptune();
			define('cache_available', true, true);
		break;
	}

	// APC Class, interfaces the alternative
	//  PHP Cache to store values persistantly
	//  which should reduce the need for SQL
	//  queries pretty significantly.
	//  Cannot be used in conjunction with Xcache.
	class apc_neptune {
		// the set function
		function set($var, $val) {
			global $expire;
			apc_store($var, $val, $expire);
		}
		// the get function
		function get($var) {
			return apc_get($var);
		}
		// the delete function
		function delete($var) {
			apc_delete($var);
		}
		// the function to flush the cache
		function flush() {
			return apc_clear_cache('user');
		}
	}
/*	
	// currently broken - DO NOT USE
	class memcached_neptune {
		private static $memcached;
		private static $memcache;
		$memcached = new Memcached();
		$memcached->addServers(NeptuneCore::var_get('cache', 'memcached'));
		function set($var) {
			global $memcached, $memcache;
		}
		function get($var) {
			global $memcached, $memcache;
		}
		function delete($var) {
			global $memcached, $memcache;
		}
		function flush($var) {
			global $memcached, $memcache;
		}
	}
	
	// currently broken - DO NOT USE
	class xcache_neptune {
		private static $memcached;
		function set($var) {
		}
		function get($var) {
		}
		function delete($var) {
		}
		function flush($var) {
		}
	}
*/
?>