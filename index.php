<?php

foreach(glob(dirname(__FILE__)."/includes/*.php") as $filename) {
	require_once($filename);
}

function content() {
	$requested_url = ($_SERVER['REQUEST_URI']);
	$class = str_replace('_','+', strtolower($requested_url));
	ob_start();
	return ob_get_clean();
}

$tpl = new template('nav_menu');
$tpl->render();

?>
