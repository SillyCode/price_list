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
			$pagename = str_replace(' ', '_', lib::request('p'));
		}
		if (strlen($pagename) <= 0) {
			$pagename = 'nav_menu';
		}
		if (($page = static::load_page($pagename)) !== null) {
			return $page;
		}
	}

	private static function load_page($pagename) {
		$classname = $pagename;
		$filename = lib::buildpath(__DIR__."/../pages", "$classname.php");
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
