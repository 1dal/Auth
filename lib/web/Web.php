<?php

namespace Auth\web;

use Auth\api\ActionQueue_api;
use Auth\Auth;
use Auth\web\Web;

class Web {
	public static $config;

	/**
	 * Load a controller class
	 * 
	 * @param string $className
	 */
	static public function loadController($className) {
		if(!class_exists($className)) {
		    $className = Auth::alphanumeric($className);
			$fn = dirname(__FILE__)."/controller/${className}.php";
			Auth::loadClassFromFile($fn, $className, "Auth\\web\\controller\\${className}");
		}
	}
	
	/**
	 * Load a view class
	 * 
	 * @param string $className
	 */
	static public function loadView($className) {
		if(!class_exists($className)) {
		    $className = Auth::alphanumeric($className);
			$fn = dirname(__FILE__)."/view/${className}.php";
			Auth::loadClassFromFile($fn, $className, "Auth\\web\\view\\${className}");
		}
	}
	
	/**
	 * Fizzle out with an error
	 * 
	 * @param string $error	A description of the error
	 */
	static public function fizzle($error) {
		header("HTTP/1.1 500 Internal Server Error");
		echo "<h1>500 Internal Server Error</h1>";
		echo "<p>".htmlentities($error)."</p>";
		
		die();
	}

	static public function redirect($to) {
		/* Run queue first if necessary */
		ActionQueue_api::start();

		/* Now redirect */
		global $config;
		header('location: ' . $to);
		exit(0);
	}
	
	static public function constructURL($controller, $action, $arg, $fmt) {
		$config = self::$config;
		$part = array();
	
		if(count($arg) == 1 && $action == $config['default']['action']) {
			/* We can abbreviate if there is only one argument and we are using the default view */
			if($controller != $config['default']['controller'] ) {
				/* The controller isn't default, need to add that */
				array_push($part, urlencode($arg[0]));
				array_unshift($part, urlencode($controller));
			} else {
				/* default controller and action. Check for default args */
				if($arg[0] != $config['default']['arg'][0]) {
					array_push($part, urlencode($arg[0]));
				}
			}
		} else {
			/* urlencode all arguments */
			foreach($arg as $a) {
				array_push($part, urlencode($a));
			}
	
			/* Nothing is default: add controller and view */
			array_unshift($part, urlencode($controller), urlencode($action));
		}
	
		/* Only add format suffix if the format is non-default (ie, strip .html) */
		$fmt_suff = (($fmt != $config['default']['format'])? "." . urlencode($fmt) : "");
		return $config['webroot'] . implode("/", $part) . $fmt_suff;
	}
	
	public static function escapeHTML($inp) {
		return htmlentities($inp, null, 'UTF-8');
	}
}

?>
