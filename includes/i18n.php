<?php

class i18n {

	private static $__languages = array();
	private $__language;

	public static function get($symbol, $language = null) {
		if ($language === null) {
			$language = config::default_language();
		}
		if (!array_key_exists($language, self::$__languages)) {
			self::$__languages[$language] = new self($language);
		}
		return self::$__languages[$language]->$symbol;
	}

	private function __construct($language) {
		$this->__language = $language;
		$filename = util::buildpath(config::i18n_dir(), "$language.txt");
		if (file_exists($filename)) {
			foreach (preg_split('/[\r\n]+/', file_get_contents($filename)) as $line) {
				if (($offset = strpos($line, ':')) !== false) {
					$name = trim(substr($line, 0, $offset));
					$value = trim(substr($line, $offset + 1));
					$this->$name = $value;
				}
			}
		}
	}

	public function __get($symbol) {
		$default_language = config::default_language();
		if ($this->__language != $default_language) {
			if (!array_key_exists($default_language, self::$__languages)) {
				self::$__languages[$default_language] = new self($default_language);
				return self::$__languages[$default_language]->$symbol;
			}
		}
		$this->$symbol = $symbol;
		return $symbol;
	}
}

?>
