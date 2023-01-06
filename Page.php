<?php

namespace Gsdk\MetaPage;

class Page {

	protected Head $head;

	protected ?JsonLd $jsonLd;

	protected $H1;

	protected $text;

	protected array $data = [];

	public function __construct() {
		$this->head = new Head();
	}

	public function __get($name) {
		switch ($name) {
			case 'h1':
				return $this->H1;
			case 'title':
			case 'keywords':
			case 'description':
				return $this->getHead()->$name;
		}

		if (isset($this->$name))
			return $this->$name;
		else if (isset($this->data[$name]))
			return $this->data[$name];

		return null;
		//return $this->getHead()->getMetaContent($name);
	}

	public function __set($name, $value) {
		switch ($name) {
			case 'title':
			case 'page_title':
				$this->getHead()->setTitle($value);
				break;
			case 'keywords':
			case 'page_keywords':
				$this->getHead()->addMetaName('keywords', $value);
				break;
			case 'description':
			case 'page_description':
				$this->getHead()->addMetaName('description', $value);
				break;
			default:
				$this->data[$name] = $value;
		}
	}

	public function setData($data): static {
		if (is_array($data)) {
			foreach ($data as $k => $v) {
				$this->$k = $v;
			}
		}
		return $this;
	}

	public function getHead(): Head {
		return $this->head;
	}

	public function getJsonLd(): JsonLd {
		return $this->jsonLd ?? ($this->jsonLd = new JsonLd());
	}

	public function setH1($h1): static {
		$this->H1 = $h1;
		return $this;
	}

	public function setTitle($title): static {
		$this->setH1($title);
		if (!$this->head->getTitle())
			$this->head->setTitle($title);
		return $this;
	}

	public function setText($text): static {
		$this->text = $text;
		return $this;
	}

	public static function doctype(): string {
		//<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
		return '<!DOCTYPE html>';
	}

	public static function html(): string {
		return '<html>';
	}

	public function h1(): string {
		return '<h1>' . ($this->H1 ? $this->H1 : $this->getHead()->getTitle()) . '</h1>';
	}

}