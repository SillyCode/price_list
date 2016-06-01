<?php

class calculator extends page {

	public function __construct() {}
	protected function body() {
		$tpl = new template('calculator');
		$tpl->render();
	}

}

?>
