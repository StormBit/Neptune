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

	// No need to overdeclare NeptuneCore if it's already set
	if(!isset($NeptuneCore)) {
		$NeptuneCore = new NeptuneCore();
	}

	// time to determine which cache has been selected
	// (atm supporting several at once will complicate things)
	// APC will be preferred for simplicity
	switch($NeptuneCore->var_get('cache', 'type')) {

		case 'none':
			$cache = new nonecache_neptune();
			define('cache_available', true, true);
		break;

		case 'apc':
			$cache = new apc_neptune();
			define('cache_available', true, true);
		break;

		case 'memcached':
			if(!($NeptuneCore->var_get('cache', 'memcached'))) {
				$cache = new memcached_neptune();
				define('cache_available', true, true);
			}
			else {
				// we need memcached servers to function if this is enabled, so throw error.
				$NeptuneCore->fatal_error('FATAL ERROR: You haven\'t set any memcached servers in config!');
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
		function set($var, $val, $expire=0) {
			apc_store($var, $val, $expire);
		}
		// the get function
		function get($var) {
			return apc_fetch($var);
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

	// XCache Class, interfaces with XCache which
	//  is also a rather nice cache.
	class xcache_neptune {
		function set($var, $val, $expire=0) {
			xcache_set($var, $val, $expire);
		}
		function get($var) {
			return xcache_get($var);
		}
		function delete($var) {
			xcache_unset($var);
		}
		function flush($var) {
			// seems like there's no workaround -- for now
		}
	}

	// eAccelerator Cache Class.
	//  nobody really uses eA, but it's included
	//  just-in-case.
	class eaccelerator_neptune {
		function set($var, $val, $expire=0) {
			eaccelerator_put($var, $val, $expire);
		}
		function get($var) {
			return eaccelerator_get($var);
		}
		function delete($var) {
			eaccelerator_rm($var);
		}
		function flush($var) {
			foreach(eaccelerator_list_keys() as $var => $val) {
				eaccelerator_rm($variable['name']);
			}
		}
	}

	// memcached class -- potentially usable WITH a PHP accelerator.
	class memcached_neptune {
		// memcached uses a pseudo-class, so we must use that
		//   additionally it stores variables within each instance
		//   so ensuring there is only one is more important than
		//   before.
		private static $memcached;
		private static function init() {
			global $NeptuneCore;
			self::$memcached = new Memcached();
			self::$memcached->addServers($NeptuneCore->var_get('cache', 'memcached'));
		}
		function set($var, $val, $expire=0) {
			if(!self::$memcached) {
				self::init();
			}
			self::$memcached->set($var, $val, $expire);
		}
		function get($var) {
			if(!self::$memcached) {
				self::init();
			}
			return self::$memcached->get($var);
		}
		function delete($var) {
			if(!self::$memcached) {
				self::init();
			}
			self::$memcached->delete($var);
		}
		function flush($var) {
			if(!self::$memcached) {
				self::init();
			}
			self::$memcached->flush();
		}
	}

	class nonecache_neptune {
	// Neptune Ordered Numeric Enclosure
	// AKA DOING IT THE REGULAR WAY

	private static $none;
	private static function init() {
		global $NeptuneCore;
		self::$none = array();
	}

	function set($var, $val, $expire=0) {
		if(!self::$none) {
			self::init();
		}
		self::$none['stack'][$var] = $val;
	}

	function get($var) {
		if(!self::$none) {
			self::init();
		}
		return self::$none['stack'][$var];
	}

	function delete($var) {
		if(!self::$none) {
			self::init();
		}
		unset(self::$none['stack'][$var];
	}

	function flush($var) {
		if(!self::$none) {
			self::init();
		}
		unset(self::$none);
		self::init();
	}
?>
