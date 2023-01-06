<?php

namespace Gsdk\MetaPage\Head;

abstract class AbstractMeta {

	protected $attributes = [];

	public function __set($name, $value) {
		$this->setAttribute($name, $value);
	}

	public function __get($name) {
		return $this->getAttribute($name);
	}

	public function setAttributes(array $attributes) {
		foreach ($attributes as $k => $v) {
			$this->setAttribute($k, $v);
		}
	}

	public function getAttributes(): array {
		return $this->attributes;
	}

	public function setAttribute($name, $value) {
		$this->attributes[$name] = $value;
	}

	public function getAttribute($name) {
		return isset($this->attributes[$name]) ? $this->attributes[$name] : null;
	}

	public function getIdentifier() {
		return false;
	}

	protected function _getHtml($tag, $close = false) {
		$s = '<' . $tag;
		foreach ($this->attributes as $k => $v) {
			if (is_bool($v)) {
				if ($v)
					$s .= ' ' . $k;
			} else if ($v) {
				$s .= ' ' . $k . '="' . $v . '"';
			}
		}
		$s .= ($close ? '></' . $tag . '>' : '>');
		return $s;
	}

	abstract public function getHtml(): string;

	public function __toString() {
		return $this->getHtml();
	}

}