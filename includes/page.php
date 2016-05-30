<?php

abstract class page extends core_page {

	protected function footer() {
		$tpl = new template('footer');
		$tpl->render();
	}

	protected function header() {
		$tpl = new template('header');
		$tpl->render();
	}

	public static function load($pagename = null) {
		if ($pagename === null) {
			$pagename = str_replace(' ', '_', util::request('p'));
		}
// 		if (strlen($pagename) <= 0) {
// 			$pagename = config::default_page();
// 		}

		if (($page = static::load_page($pagename)) !== null) {
			var_dump($pagename);
			return $page;
		}
// 		static::load_page(config::default_page())->not_found();
	}

	private static function load_page($pagename) {
		$classname = "$pagename";
		$filename = util::buildpath(config::page_dir(), "$classname.php");
		if ($filename !== null && file_exists($filename)) {
			include_once($filename);
			if (class_exists($classname)) {
				return new $classname();
			}
		}
		return null;
	}
}

?>
