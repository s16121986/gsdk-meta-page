<?php

namespace Gsdk\MetaPage\Head;

class BaseHref extends AbstractMeta {

	public function __construct($href, array $attributes = []) {
		$this->setAttributes($attributes);
		$this->href = $href;
	}

	public function getIdentifier() {
		return 'base';
	}

	public function getHtml(): string {
		return $this->_getHtml('base', false);
	}

}