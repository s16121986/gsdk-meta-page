<?php

namespace Gsdk\MetaPage\Head;

class Link extends AbstractMeta {

	public function __construct(array $attributes = []) {
		$this->setAttributes($attributes);
	}

	public function getHtml(): string {
		return $this->_getHtml('link', false);
	}

}