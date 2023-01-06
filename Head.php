<?php

namespace Gsdk\MetaPage;

class Head {

	protected string $title = '';

	protected string $relativePath = '/';

	protected string $headAttr = '';

	protected $baseHref;

	protected array $meta = [];

	protected array $styles = [];

	protected array $scripts = [];

	protected array $contents = [];

	public function __get($name) {
		switch ($name) {
			case 'scripts':
			case 'styles':
				return $this->$name;
			case 'title':
			case 'relativePath':
			case 'baseHref':
				return $this->$name;
			case 'description':
			case 'keywords':
				$metaId = 'meta_name_' . $name;
				return isset($this->meta[$metaId]) ? $this->meta[$metaId]->getAttribute('content') : null;
		}

		return null;
	}

	public function fromString($html): void {
		if (empty($html))
			return;
		//if (preg_match('/<title>(.*)<\/title>/iU', $html, $m))
		//	$this->setTitle($m[1]);
		//preg_match_all('/<(\w+)(?:\s.*)>/imU', $html, $mTag, PREG_SET_ORDER);
		preg_match_all('/<(\w+\b)(?:([^>]*)\/?>)(?:([^<]*)(?:<\/\w+>))?/im', $html, $mTag, PREG_SET_ORDER);
		if (!$mTag)
			return;
		foreach ($mTag as $tag) {
			$attr = [];
			preg_match_all('/(\b(?:\w|-)+\b)\s*=\s*(?:"([^"]*)")/imU', $tag[0], $mAttr, PREG_SET_ORDER);
			if ($mAttr) {
				foreach ($mAttr as $m) {
					$attr[$m[1]] = $m[2];
				}
			}
			if ($tag[1] === 'title' && isset($tag[3])) {
				$this->setTitle($tag[3]);
			} else if ($tag[1] === 'meta') {
				$this->addMeta($attr);
			} else if ($tag[1] === 'base')
				$this->setBaseHref($attr['href']);
			else if ($tag[1] === 'link')
				$this->addLink($attr);
			else
				$this->addContent($tag[0]);

			//else var_dump($tag[1], $attr);
		}
		//echo $html, "\n\n",$this->getHtml();
		//exit;
	}

	public function setHeadAttr($attr): static {
		$this->headAttr = $attr;
		return $this;
	}

	public function setRelativePath($path): static {
		$this->relativePath = $path;
		return $this;
	}

	public function setTitle($title): static {
		$this->title = $title;
		return $this;
	}

	public function setKeywords($keywords): static {
		$this->addMetaName('keywords', $keywords);
		return $this;
	}

	public function setDescription($description): static {
		$this->addMetaName('description', $description);
		return $this;
	}

	public function getTitle() {
		return $this->title;
	}

	public function setBaseHref($href): static {
		$this->baseHref = new Head\BaseHref($href);
		return $this;
	}

	public function addContent($content): static {
		$this->contents[] = $content;
		return $this;
	}

	public function addMeta(array $attributes): static {
		return $this->_addMeta(new Head\Meta($attributes));
	}

	public function addLink(array $attributes): static {
		return $this->_addMeta(new Head\Link($attributes));
	}

	public function addLinkRel($rel, $href, array $attributes = []): static {
		$attributes['rel'] = $rel;
		$attributes['href'] = $href;
		return $this->addLink($attributes);
	}

	public function addMetaName($name, $content, array $attributes = []): static {
		$attributes['name'] = $name;
		$attributes['content'] = $content;
		return $this->addMeta($attributes);
	}

	public function addMetaProperty($property, $content, array $attributes = []): static {
		$attributes['property'] = $property;
		$attributes['content'] = $content;
		return $this->addMeta($attributes);
	}

	public function addMetaHttpEquiv($keyValue, $content, array $attributes = []): static {
		$attributes['http-equiv'] = $keyValue;
		$attributes['content'] = $content;
		return $this->addMeta($attributes);
	}

	public function addStyle($href, array $attributes = []): static {
		$this->styles[] = new Head\Style($this->url($href, 'css'), $attributes);
		return $this;
	}

	public function addScript($src, array $attributes = []): static {
		$this->scripts[] = new Head\Script($this->url($src, 'js'), $attributes);
		return $this;
	}

	public function url($url, $path = null) {
		if (substr($url, 0, 1) !== '/' && substr($url, 0, 4) !== 'http') {
			return $this->relativePath . ($path ? $path . '/' : '') . $url;
		}
		return $url;
	}

	public function getStyles() {
		return $this->styles;
	}

	public function getScripts() {
		return $this->scripts;
	}

	public function __toString() {
		$html = '<head>' . "\n";

		$html .= '<title>' . $this->title . '</title>' . "\n";

		foreach ($this->meta as $meta) {
			$html .= $meta->getHtml() . "\n";
		}

		foreach ($this->styles as $style)
			$html .= $style->getHtml() . "\n";

		foreach ($this->scripts as $script)
			$html .= $script->getHtml() . "\n";

		if ($this->baseHref)
			$html .= $this->baseHref->getHtml() . "\n";

		$html .= implode('', $this->contents);

		$html .= '</head>';

		return $html;
	}

	protected function _addMeta(Head\AbstractMeta $meta) {
		$uid = $meta->getIdentifier();
		if ($uid)
			$this->meta[$uid] = $meta;
		else
			$this->meta[] = $meta;
		return $this;
	}

}