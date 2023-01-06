<?php

namespace Gsdk\MetaPage\Head;

class Script extends AbstractMeta {

	protected $attributes = [
		//'type' => 'text/javascript',
		'async' => false,
		'defer' => false
	];

	public function __construct($src, array $attributes = []) {
		$this->setAttributes($attributes);
		$this->src = $src;
	}

	public function getHtml(): string {
		return $this->_getHtml('script', true);
	}

}