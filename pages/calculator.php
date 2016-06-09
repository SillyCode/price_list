<?php

include_once('class/warranty.php');

class calculator extends page {

	public function __construct() {}

	private function warranties() {
		return warranty::all();
	}

	protected function body() {
		$tpl = new template('calculator');
		$tpl->warranties = $this->warranties();
		$tpl->render();
	}

}

?>
