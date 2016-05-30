<?php

foreach(glob(dirname(__FILE__)."/../includes/*.php") as $filename) {
	require_once($filename);
}

class cpbx_astribank {

	public function __construct() {}
	public function populate(template $tpl) {}
}

$tpl = new template('cpbx_astribank');
$tpl->render();

?>
