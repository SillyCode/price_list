<?php

abstract class core_page {
	protected $errors;

	abstract protected function body();

	protected function __construct() {}

	protected function error($message) {
		$this->errors[$message] = (object) array('message' => $message);
	}

	protected function footer() {}

	protected function header() {}

	public static function is_postback() {
		return (strtolower($_SERVER['REQUEST_METHOD']) == 'post');
	}

	public static function is_ajax() { // Header added by JQuery and others
		return array_key_exists('HTTP_X_REQUESTED_WITH', $_SERVER);
	}

	public function render() {
		$this->header();
		$this->body();
		$this->footer();
		echo @ob_get_clean();
	}
}
