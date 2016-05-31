<?php

class template {

	private $__template;
	private $__language;
	private $__stack;
	private $__offset;
	private $__properties = array();

	public function __construct($template, $language = null) {
		$this->__template = $template;
		$this->__language = $language;
	}

	public function __isset($name) {
		return isset($this->__properties[$name]);
	}

	public function __set($name, $value) {
		$this->__properties[$name] = $value;
	}

	public function __get($name) {
		return array_key_exists($name, $this->__properties) ? $this->__properties[$name] : null;
	}

	public function __tostring() {
		ob_start();
		$this->render();
		return ob_get_clean();
	}

	private function get($name, &$value) {
		for ($i = count($this->__stack) - 1; $i >= 0; $i--) {
			$frame = $this->__stack[$i];
			if (is_array($frame) && array_key_exists($name, $frame)) {
				$value = $frame[$name];
				return true;
			} else if (is_object($frame) && property_exists($frame, $name)) {
				$value = $frame->$name;
				return true;
			}
		}
		$value = null;
		return false;
	}

	private function scan_next($matches, $i, $end) {
		$depth = 1;
		for (;$i < $end; $i++) {
			$match = $matches[$i];
			if (count($match) > 1) {
				$type = $match[1][0];
				switch ($type) {
					case 'if': { $depth++; break; }
					case 'loop': { $depth++; break; }
					case 'else': { if ($depth - 1 == 0) { return $i; } break; }
					case '/if': { if (--$depth == 0) { return $i; } break; }
					case '/loop': { if (--$depth == 0) { return $i; } break; }
				}
			}
		}
		throw new Exception('Failed to find block end');
	}

	public function render() {
		$filename = lib::buildpath(__DIR__."/../templates", "{$this->__template}.tpl");
		if (file_exists($filename) && strlen($input = @file_get_contents($filename)) > 0) {
			$this->__stack = array($this->__properties);
			$this->__offset = 0;
			$pattern = '/{(\/?\w+)(?:\s+(\w+)(?:\s+(?|(\w+)|(==|!=|<=|>=|<|>)\s+("\w+"|\w+)))?)?}/';
			if (preg_match_all($pattern, $input, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE)) {
				$this->render_range($matches, 0, count($matches), $input);
			}
			echo substr($input, $this->__offset);
		}
	}

	protected function header() {
		$tpl = new template('header');
		$tpl->render();
	}

	private function render_base64($match, $input) {
		$this->render_literal($match, $input);
		if ($this->get($match[2][0], $value) && strlen($value) > 0) {
			echo base64_encode($value);
		}
	}

	private function render_date($match, $input) {
		$this->render_literal($match, $input);
		if ($this->get($match[2][0], $value) && $value > 0 &&
			($result = gmdate('Y-m-d', $value + session::user()->timezone()->offset)) !== false) {
			echo $result;
		} else {
			echo $value;
		}
	}

	private function render_datetime($match, $input) {
		$this->render_literal($match, $input);
		if ($this->get($match[2][0], $value) && $value > 0 &&
			($result = gmdate('Y-m-d H:i:s', $value + session::user()->timezone()->offset)) !== false) {
			echo $result;
		} else {
			echo $value;
		}
	}

	private function render_escape($match, $input) {
		$this->render_literal($match, $input);
		if ($this->get($match[2][0], $value) && strlen($value) > 0) {
			echo htmlspecialchars($value);
		}
	}

	private function render_i18n($match, $input) {
		$this->render_literal($match, $input);
		echo htmlspecialchars(i18n::get(substr($match[1][0], 1), $this->__language));
	}

	private function render_if($match, $input, $matches, &$i, $end) {
		$this->render_literal($match, $input);
		$symbol = $match[2][0];
		if (count($match) == 5) { // {if variable operator comparand}
			if ($this->get($symbol, $value)) {
				$operator = $match[3][0];
				$comparand = $match[4][0];
				if ($comparand[0] == '"') { // regex pattern ensures trailing double quote
					$comparand = substr($comparand, 1, -1);
				} else {
					if ($this->get($comparand, $comparand)) {
						if (strlen($comparand) > 0 && $comparand[0] == '_') {
							$comparand = i18n::get(substr($comparand, 1), $this->__language);
						}
					}
				}
				switch ($operator) {
					case '==': { if ($value == $comparand) { return; } break; }
					case '!=': { if ($value != $comparand) { return; } break; }
					case '<=': { if ($value <= $comparand) { return; } break; }
					case '>=': { if ($value >= $comparand) { return; } break; }
					case '<': { if ($value < $comparand) { return; } break; }
					case '>': { if ($value > $comparand) { return; } break; }
				}
			}
		} else {
			if ($this->get($symbol, $value) && $value) {
				return;
			}
		}
		// skip to {else} or {/if}
		$n = $this->scan_next($matches, $i + 1, $end);
		$next = $matches[$n];
		if (count($next) < 2 || ($type = $next[1][0]) == 'else' || $type == '/if') {
			$this->__offset = $next[0][1] + strlen($next[0][0]);
			$i = $n;
		} else {
			throw new Exception('Expected {else} or {/if}');
		}
	}

	private function render_literal($match, $input) {
		echo substr($input, $this->__offset, $match[0][1] - $this->__offset);
		$this->__offset = $match[0][1] + strlen($match[0][0]);
	}

	private function render_loop($match, $input, $matches, &$i, $end) {
		$this->render_literal($match, $input);
		$symbol = $match[2][0];
		$n = $this->scan_next($matches, $i + 1, $end);
		$next = $matches[$n];
		if (count($next) < 2 || $next[1][0] != '/loop') {
			throw new Exception('Expected {/loop}');
		}
		if ($this->get($symbol, $elements) &&
			(is_array($elements) || $elements instanceof Countable)) {
			$offset = $this->__offset;
			foreach ($elements as $element) {
				$this->__offset = $offset;
				$this->__stack[] = $element;
				$this->render_range($matches, $i + 1, $n, $input);
				array_pop($this->__stack);
				$this->render_literal($next, $input);
			}
		}
		$this->__offset = $next[0][1] + strlen($next[0][0]);
		$i = $n;
	}

// 	private function render_mac($match, $input) {
// 		$this->render_literal($match, $input);
// 		if ($this->get($match[2][0], $value)) {
// 			echo util::bin2mac($value);
// 		}
// 	}

	private function render_range($matches, $i, $end, $input) {
		for (;$i < $end; $i++) {
			$match = $matches[$i];
			if (($count = count($match)) > 2) {
				$type = $match[1][0];
				$methodname = "render_$type";
				if (method_exists($this, $methodname)) {
					$this->$methodname($match, $input, $matches, $i, $end);
				} else {
					throw new Exception("Unknown type: $type");
				}
			} else {
				$symbol = $match[1][0];
				if ($symbol[0] == '_') { // {_symbol}
					$this->render_i18n($match, $input);
				} else if ($symbol == '/if') { // {/if}
					$this->render_literal($match, $input);
				} else if ($symbol == 'else') { // {/else}, skip to {/if} since {if} was true
					$n = $this->scan_next($matches, $i + 1, $end);
					$next = $matches[$n];
					if (count($next) < 2 || $next[1][0] != '/if') {
						throw new Exception('Expected {/if}');
					}
					$this->render_literal($match, $input);
					$this->__offset = $next[0][1] + strlen($next[0][0]);
					$i = $n;
				} else if ($symbol == '/loop') { // {/loop}, handled by render_loop
					throw new Exception('Unexpected symbol: {/loop}');
				} else { // variable
					$this->render_variable($match, $input);
				}
			}
		}
	}

	private function render_variable($match, $input) {
		$this->render_literal($match, $input);
		if ($this->get($match[1][0], $value) && strlen($value) > 0) {
			echo ($value[0] == '_') ? i18n::get(substr($value, 1), $this->__language) : $value;
		}
	}

	public function setup_pagination(
		$column, $default_column,
		$direction, $default_direction,
		$page, $items, $items_per_page) {
		$pages = ceil($items / $items_per_page);
		if ($pages <= 0)  { $pages = 1; }
		if (strlen($column) <= 0) {
			$column = $default_column;
			$direction = $default_direction;
		} else {
			if (!in_array($direction, array('asc', 'desc'))) { $direction = 'desc'; }
		}
		if (!valid::unsigned($page) || $page < 1) { $page = 1; }
		if ($page > $pages) { $page = $pages; }
		$this->__properties['page'] = $page;
		$this->__properties['pages'] = $pages;
		$this->__properties['next'] = $page + 1;
		$this->__properties['prev'] = $page - 1;
		$this->__properties['column'] = $column;
		$this->__properties['direction'] = $direction;
	}
}

?>
