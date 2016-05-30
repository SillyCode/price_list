<?php

class lib {

	function show_page($page) {
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
}

?>
