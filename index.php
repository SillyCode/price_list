<?php

foreach(glob(dirname(__FILE__)."/includes/*.php") as $filename) {
	require_once($filename);
}

$tpl = new template('nav_menu');
$tpl->render();

?>
