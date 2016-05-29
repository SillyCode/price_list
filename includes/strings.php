<?php

// Consider only en right now. It's not that I'm gonna translate it to any other language.
foreach(glob(__DIR__."/../i18n/en/*.txt") as $strings) {
	$contents = file_get_contents($strings);
	//TODO: localize strings
}

?>