<?php

namespace Gsdk\MetaPage\JsonLd;

abstract class AbstractThing {

	const SCHEME = 'https://schema.org';

	protected $type;

	protected $data = [];

	public function __construct($type, $data) {
		$this->type = $type;

		if (is_array($data))
			foreach ($data as $k => $v) {
				$this->$k = $v;
			}
	}

	public function __call(string $name, array $arguments) {
		$this->data[$name] = $arguments[0];
		return $this;
	}

	public function __set($name, $value) {
		$this->data[$name] = $value;
	}

	public function __get($name) {
		return isset($this->data[$name]) ? $this->data[$name] : null;
	}

	public function getHtml() {
		$data = [
			'@context' => self::SCHEME,
			'@type' => $this->type
		];

		foreach ($this->data as $k => $v) {
			if (empty($v))
				continue;
			$data[$k] = $v;
		}

		return json_encode($data);
	}

}