<?php

namespace Gsdk\MetaPage;

class JsonLd {

	private array $items = [];

	public function __get($name) {
		return $this->items[$name] ?? null;
	}

	public function addThing($thing) {
		$this->items[strtolower($thing->type)] = $thing;
		return $thing;
	}

	public function addCustom($type, $data) {
		return $this->addThing(new JsonLd\Custom($type, $data));
	}

	public function addOrganization($data = null) {
		return $this->addThing(new JsonLd\Organization($data));
	}

	public function addArticle($data = null) {
		return $this->addThing(new JsonLd\Article($data));
	}

	public function addBreadcrumbs($data = null) {
		return $this->addThing(new JsonLd\BreadcrumbList($data));
	}

	public function getHtml(): string {
		$html = [];
		foreach ($this->items as $item) {
			$s = $item->getHtml();
			if ($s)
				$html[] = $s;
		}

		return empty($html) ? '' : '<script type="application/ld+json">[' . implode(',', $html) . ']</script>';
	}

}