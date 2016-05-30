<?php

abstract class core_page {
	protected $errors;

	abstract protected function body();

	protected function __construct() {}

	protected function error($message) {
		$this->errors[$message] = (object) array('message' => $message);
	}

	protected function footer() {}

	protected function forbidden() {
		ob_clean();
		header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden');
		// HTML needs to be self-contained so we cannot use templates
		echo <<<EOF
<!DOCTYPE html>
<html>
<head>
	<title>403 Forbidden</title>
</head>
<body>
<h1>Forbidden</h1>
<p>You don't have permission to access {$_SERVER['REQUEST_URI']} on this server.</p>
<hr>
<address>{$_SERVER['SERVER_SOFTWARE']} Server at {$_SERVER['SERVER_NAME']} Port {$_SERVER['SERVER_PORT']}</address>
</body>
</html>
EOF;
exit;
	}

	protected function header() {}

	public static function is_postback() {
		return (strtolower($_SERVER['REQUEST_METHOD']) == 'post');
	}

	public static function is_ajax() { // Header added by JQuery and others
		return array_key_exists('HTTP_X_REQUESTED_WITH', $_SERVER);
	}

	protected function not_found() {
		ob_clean();
		header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
		// HTML needs to be self-contained so we cannot use templates
		echo <<<EOF
<!DOCTYPE html>
<html>
<head>
	<title>404 Not Found</title>
</head>
<body>
<h1>Not Found</h1>
<p>The requested URL {$_SERVER['REQUEST_URI']} was not found on this server.</p>
<hr>
<address>{$_SERVER['SERVER_SOFTWARE']} Server at {$_SERVER['SERVER_NAME']} Port {$_SERVER['SERVER_PORT']}</address>
</body>
</html>
EOF;
exit;
	}

	public function render() {
		$this->header();
		$this->body();
		$this->footer();
		echo @ob_get_clean();
	}
}
