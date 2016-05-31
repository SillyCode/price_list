<?php

foreach(glob(dirname(__FILE__)."/includes/*.php") as $filename) {
	require_once($filename);
}

page::load()->render();

?>
