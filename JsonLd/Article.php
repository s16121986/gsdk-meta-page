<?php

namespace Gsdk\MetaPage\JsonLd;

class Article extends AbstractThing {

	public function __construct($data = null) {
		parent::__construct('Article', $data);
	}

	public function entry($url, $type = 'WebPage'): static {
		$this->data['mainEntityOfPage'] = [
			'@type' => $type,
			'@id' => $url
		];
		return $this;
	}

	public function author($name, $type = 'Organization'): static {
		$this->data['author'] = [
			'@type' => $type,
			'name' => $name
		];
		return $this;
	}

	public function publisher($name, $logo = '', $type = 'Organization'): static {
		$this->data['publisher'] = [
			'@type' => $type,
			'name' => $name,
			'logo' => [
				'@type' => 'ImageObject',
				'url' => $logo
			]
		];
		return $this;
	}

}