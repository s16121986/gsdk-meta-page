<?php

namespace Gsdk\MetaPage\JsonLd;

class Organization extends AbstractThing {

	public function __construct($data = null) {
		parent::__construct('Organization', $data);
	}

	public function addSocialUrl($url) {
		$this->data['sameAs'][] = $url;
		return $this;
	}

}