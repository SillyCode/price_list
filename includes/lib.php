<?php

class lib {

	public function show_page($page) {
		include($page); //Parse the text
	}

	public static function buildpath() {
			$arguments = func_get_args();
			$first = array_shift($arguments);
			$arguments = array_map(function($argument) {
					return ltrim($argument, DIRECTORY_SEPARATOR);
			}, $arguments);
			array_unshift($arguments, $first);
			return implode(DIRECTORY_SEPARATOR, $arguments);
	}

	public static function request($name) {
		if (array_key_exists($name, $_REQUEST)) {
			return $_REQUEST[$name];
		}
		return null;
	}

	public static function post($name) {
		if (array_key_exists($name, $_POST)) {
			return $_POST[$name];
		}
		return null;
	}
}

?>
