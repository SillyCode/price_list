<?php

class warranty {

	public $pn;
	public $desc;
	public $price;

	public function __construct($pn = null) {

	}

	public function all() {
		$warranties = [];
		$index = 0;
		foreach (simplexml_load_file('templates/items_warranties.xml') as $a => $warranty) {
			foreach ($warranty as $attr => $value) {
				$warranties[$index][$attr] = (string)$warranty->$attr;
			}
			$index++;
		}
		return $warranties;
	}

}

?>
