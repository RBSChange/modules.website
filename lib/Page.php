<?php
class website_Page implements f_mvc_Context
{

	private $attributes = array();

	/**
	 * @see f_mvc_Context::getAttribute()
	 *
	 * @param String $name
	 * @param String $defaultValue
	 * @return mixed
	 */
	function getAttribute($name, $defaultValue = null)
	{
		// For compatibility, until 3.1
		// TODO: for 3.1 : remove
		if ($name === website_BlockAction::BLOCK_PAGE_ATTRIBUTE)
		{
			return $this;
		}
		if (isset($this->attributes[$name]))
		{
			return $this->attributes[$name];
		}
		return $defaultValue;
	}

	/**
	 * @see f_mvc_Context::getAttributes()
	 *
	 * @return array<String,
	 */
	function getAttributes()
	{
		// For compatibility we merge attributes
		return array_merge($this->attributes, array(website_BlockAction::BLOCK_PAGE_ATTRIBUTE => $this));
		// TODO: for 3.1 : decomment
		//return $this->attributes;
	}

	/**
	 * @see f_mvc_Context::hasAttribute()
	 *
	 * @param String $name
	 * @return Boolean
	 */
	function hasAttribute($name)
	{
		return isset($this->attributes[$name]);
	}

	/**
	 * @see f_mvc_Context::hasNonEmptyAttribute()
	 *
	 * @param String $name
	 * @return Boolean
	 */
	function hasNonEmptyAttribute($name)
	{
		return !f_util_StringUtils::isEmpty($this->getAttribute($name));
	}

	/**
	 * @see f_mvc_Context::removeAttribute()
	 *
	 * @param String $name
	 */
	function removeAttribute($name)
	{
		if ($this->hasAttribute($name))
		{
			unset($this->attributes[$name]);
		}
	}

	/**
	 * @see f_mvc_Context::setAttribute($name, $value)
	 */
	public function setAttribute($name, $value)
	{
		$this->attributes[$name] = $value;
	}


	/**
	 * @var website_persistentdocument_page
	 */
	private $page;

	/**
	 * @param website_persistentdocument_page $page
	 */
	function __construct($page)
	{
		if (!($page instanceof website_persistentdocument_page))
		{
			throw new IllegalArgumentException("$page is not a website_persistentdocument_page but a " . get_class($page));
		}
		
		if (!$page->isNew())
		{
			website_WebsiteModuleService::getInstance()->setCurrentPageId($page->getId());
		}
			
		$prs = website_PageRessourceService::getInstance();
		$prs->setPage($page);
			
		$this->attributes['title'] = $page->getMetatitle();
		$this->attributes['description'] = $page->getDescription();
		$this->attributes['keywords'] = $page->getKeywords();
		$this->addMeta('robots', $page->getRobotsmeta());

		$this->setPlainMarker(website_MarkerService::getInstance()->getHtmlMarker(website_WebsiteModuleService::getInstance()->getCurrentWebsite()));
		
		$this->page = $page;
	}

	/**
	 * Just for compatibility
	 * @return website_Page
	 */
	public function getPage()
	{
		return $this;
	}

	/**
	 * @return website_persistentdocument_page
	 */
	function getPersistentPage()
	{
		return $this->page;
	}

	public function getId()
	{
		return $this->page->getId();
	}

	/**
	 * @deprecated in favor to setTitle()
	 * @see block_Context::setMetatitle($string)
	 */
	public function setMetatitle($string)
	{
		$this->setTitle($string);
	}

	/**
	 * @deprecated: use block meta mechanism instead and implement getMeta() on your block
	 * @var String $string
	 */
	public function setTitle($string)
	{
		$this->attributes['title'] = $string;
	}

	/**
	 * @return String
	 */
	public function getTitle()
	{
		return str_replace($this->metaReplaceFrom, $this->metaReplaceTo, $this->attributes['title']);
	}

	/**
	 * @see block_Context::addScript($string)
	 * @example addScript("modules.website.lib.js.jquery")
	 */
	public function addScript($string)
	{
		if (!isset($this->attributes['scripts']))
		{
			$this->attributes['scripts'] = array();
		}
		$this->attributes['scripts'][$string] = true;
	}

	/**
	 * @deprecated: use block meta mechanism instead and implement getMeta() on your block
	 * @see block_Context::setKeywords($string)
	 */
	public function setKeywords($string)
	{
		$this->attributes['keywords'] = $string;
	}

	/**
	 * @deprecated: use block meta mechanism instead and implement getMeta() on your block
	 * @see block_Context::setDescription($string)
	 */
	public function setDescription($string)
	{
		$this->attributes['description'] = $string;
	}

	/**
	 * @deprecated: use block meta mechanism instead and implement getMeta() on your block
	 * @see block_Context::setNavigationtitle($string)
	 */
	public function setNavigationtitle($string)
	{
		$this->page->setNavigationtitle($string);
	}

	/**
	 * @see block_Context::getNavigationtitle()
	 */
	public function getNavigationtitle()
	{
		return $this->page->getNavigationtitle();
	}

	/**
	 * @deprecated: use block meta mechanism instead and implement getMeta() on your block
	 * @see block_Context::appendToDescription($string)
	 */
	public function appendToDescription($string)
	{
		if (isset($this->attributes['description']))
		{
			$this->attributes['description'] .= ' ' . $string;
		}
		else
		{
			$this->attributes['description'] = $string;
		}
	}

	/**
	 * @see block_Context::addStyle($style, $media)
	 */
	public function addStyle($style, $media = "screen")
	{
		if (!isset($this->attributes['styles']))
		{
			$this->attributes['styles'] = array();
		}
		if (!isset($this->attributes['styles'][$media]))
		{
			$this->attributes['styles'][$media] = array();
		}
		$this->attributes['styles'][$media][$style] = true;
	}

	/**
	 * @deprecated: use block meta mechanism instead and implement getMeta() on your block
	 * @see block_Context::addKeyword($string)
	 */
	public function addKeyword($string)
	{
		if (isset($this->attributes['keywords']))
		{
			$this->attributes['keywords'] .= ', ' . $string;
		}
		else
		{
			$this->attributes['keywords'] = $string;
		}
	}

	/**
	 * @see block_Context::getAncestors()
	 * @return array<integer>
	 */
	public function getAncestorIds()
	{
		if (!isset($this->attributes['ancestorsids']))
		{

			$ancestors = website_WebsiteModuleService::getInstance()->getCurrentPageAncestors();
			$this->attributes['ancestorsids'] = DocumentHelper::getIdArrayFromDocumentArray($ancestors);

		}
		return $this->attributes['ancestorsids'];
	}


	/**
	 * returns the page parent, ie. a topic or a website instance
	 * @return f_persistentdocument_PersistentDocument
	 */
	public function getParent()
	{
		$parentId = f_util_ArrayUtils::lastElement($this->getAncestorIds());
		return DocumentHelper::getDocumentInstance($parentId);
	}
	
	/**
	 * @return website_persistentdocument_website
	 */
	public function getWebsite()
	{
		$websiteId = f_util_ArrayUtils::firstElement($this->getAncestorIds());
		return DocumentHelper::getDocumentInstance($websiteId);
	}

	private $metas = array();

	/**
	 *
	 * @param String $name
	 * @param String $content
	 * @param String $scheme
	 * @param Boolean $isHttpEquiv
	 */
	public function addMeta($name, $content, $scheme = null, $isHttpEquiv = false)
	{
		$this->metas[] = func_get_args();
	}

	/**
	 * @return string the html that is the result of addMeta()
	 */
	function getMetas()
	{
		$html = '';
		foreach ($this->metas as $meta)
		{
			$attrName = (isset($meta[3]) && $meta[3]) ? "http-equiv" : "name";
			$html .= '<meta '.f_util_HtmlUtils::buildAttribute($attrName, $meta[0]).' '
			.f_util_HtmlUtils::buildAttribute("content", $meta[1]);
			if (isset($meta[2]))
			{
				$html .= ' '.f_util_HtmlUtils::buildAttribute("scheme", $meta[2]);

			}
			$html .= ' />';
		}
		return $html;
	}

	private $metaReplaceFrom = array();
	private $metaReplaceTo = array();

	/**
	 * @param String $name
	 * @param String $value
	 */
	public function addBlockMeta($name, $value)
	{
		$this->metaReplaceFrom[] = "{".$name."}";
		$this->metaReplaceTo[] = $value;
	}

	/**
	 * @param String $title
	 * @param String $href
	 */
	public function addRssFeed($title, $href)
	{
		$this->addLink('alternate', 'application/rss+xml', $href, $title);
	}

	/**
	 * @param String $relation
	 * @param String $type
	 * @param String $href
	 * @param String $title
	 * @param String $lang
	 */
	public function addLink($relation, $type, $href, $title = null, $lang = null)
	{
		if (!isset($this->attributes['links']))
		{
			$this->attributes['links'] = array();
		}
		$linkParams = array('href' => $href, 'rel' => $relation, 'type' => $type);
		if ($title !== null)
		{
			$linkParams['title'] = $title;
		}
		if ($lang !== null)
		{
			$linkParams['hreflang'] = $lang;
		}
		$this->attributes['links'][] = $linkParams;
	}

	/**
	 * @param string $marker
	 * @return website_Page
	 */
	public function setPlainMarker($marker)
	{
		$this->attributes['plainmarker'] = $marker;
		return $this;
	}


	public function appendToPlainMarker($marker)
	{
		if (!isset($this->attributes['plainmarker']))
		{
			$this->setPlainMarker($marker);
			return;
		}
		$this->attributes['plainmarker'] = $this->attributes['plainmarker'] . "\n" . $marker;
	}

	/**
	 * Add a marker to the current page MARKERS.
	 *
	 * @param string $name Marker name
	 * @param array $parameters Marker parameters
	 * @return website_Page
	 */
	public function addMarker($name, $parameters = array())
	{
		if (!isset($this->attributes['markers']))
		{
			$this->attributes['markers'] = array();
		}

		$this->attributes['markers'][$name] = $parameters;
		return $this;
	}

	/**
	 * Set a specific marker parameter.
	 *
	 * @param string $markerName Marker name
	 * @param string $paramName Marker's parameter name
	 * @param mixed $paramValue Marker's parameter value
	 * @return website_Page
	 */
	public function setMarkerParameter($markerName, $paramName, $paramValue)
	{
		if (!isset($this->attributes['markers']))
		{
			$this->attributes['markers'] = array();
		}

		if (!isset($this->attributes['markers'][$markerName]))
		{
			$this->attributes['markers'][$markerName] = array();
		}
		$this->attributes['markers'][$markerName][$paramName] = $paramValue;
		return $this;
	}

	/**
	 * Retrieve all markers as a JavaScript Object.
	 *
	 * @return string
	 */
	public function getMarkersForJavascript()
	{
		if (!isset($this->attributes['markers'])) {return '{}';};
		return JsonService::getInstance()->encode($this->attributes['markers']);
	}

	/**
	 * @param string $htmlBody
	 * @param string $templatePath
	 */
	public function renderHTMLBody($htmlBody, $templatePath)
	{
		if (isset($this->attributes['plainmarker']))
		{
			$htmlBody = str_replace('</body>', $this->attributes['plainmarker'] . K::CRLF . '</body>', $htmlBody);
		}
		$this->htmlBody = $htmlBody;
		include($templatePath);
	}

	//Templateting Function

	protected function getLang()
	{
		return RequestContext::getInstance()->getLang();
	}

	protected function getDescription()
	{
		return str_replace($this->metaReplaceFrom, $this->metaReplaceTo, $this->attributes['description']);
	}

	protected function getKeywords()
	{
		return str_replace($this->metaReplaceFrom, $this->metaReplaceTo, $this->attributes['keywords']);
	}

	protected function getJSONHandler()
	{
		$data = array('id' => $this->getId(), 'lang' => $this->getLang());
		return JsonService::getInstance()->encode($data);
	}

	protected function renderBenchTimes()
	{
		if ($this->benchTimes !== null)
		{
			$this->benchTimes['renderTOTAL'] = microtime(true) - $this->benchTimes['renderStart'];
			unset($this->benchTimes['renderStart']);
			unset($this->benchTimes['c']);
			echo '<script type="text/javascript">renderBenchTimes(' . JsonService::getInstance()->encode($this->benchTimes) . ');</script>';
		}
	}
	
	/**
	 * @var skin_persistentdocument_skin
	 */
	private $skin = false;

	/**
	 * @return skin_persistentdocument_skin
	 */
	protected function getSkin()
	{
		if ($this->skin === false)
		{
			$skin = $this->page->getSkin();
			if ($skin === null)
			{
				$ancestors = array_reverse($this->getAncestorIds());
				foreach ($ancestors as $ancestorId)
				{
					$doc = DocumentHelper::getDocumentInstance($ancestorId);
					if (f_util_ClassUtils::methodExists($doc, 'getSkin'))
					{
						$skin = $doc->getSkin();
					}
					if ($skin !== null) {break;}
				}
			}
			$this->skin = $skin;
		}
		return $this->skin;

	}


	/**
	 * Inclusion of all "default" styles...
	 *
	 * @return String
	 */
	protected function getStylesheetInclusions()
	{
		$prs = website_PageRessourceService::getInstance();
		$styleInclusions = array();
		if (!$this->page->isNew())
		{
			$skin = $this->getSkin();
			$prs->setSkin($skin);
			if ($skin && $skin->isNew())
			{
				$styleInclusions[] = $prs->getGlobalScreenStylesheetInLine();
				$pageStylesheetInclusion = $prs->getPageStylesheetInLine();
				if ($pageStylesheetInclusion)
				{
					$styleInclusions[] = $pageStylesheetInclusion;
				}
			}
			else
			{
				$styleInclusions[] = $prs->getGlobalScreenStylesheetInclusion();
				$pageStylesheetInclusion = $prs->getPageStylesheetInclusion();
				if ($pageStylesheetInclusion)
				{
					$styleInclusions[] = $pageStylesheetInclusion;
				}
				$styleInclusions[] = $prs->getGlobalPrintStylesheetInclusion();
			}

		}
		return implode(K::CRLF, $styleInclusions);
	}

	protected function getScripts()
	{
		$js = JsService::getInstance();
		$html = "";
		if (!$this->page->isNew())
		{
			$frontofficeScripts = website_PageRessourceService::getInstance()->getAvailableScripts();
			foreach ($frontofficeScripts as $script)
			{
				$js->registerScript($script);
			}
			$html .= $js->execute();
		}
		if (isset($this->attributes['scripts']))
		{
			$frontofficeScriptsComputed = $js->getComputedRegisteredScripts();
			foreach (array_keys($this->attributes['scripts']) as $script)
			{
				$js->registerScript($script);
			}
			$html .= $js->execute(null, false, $frontofficeScriptsComputed);
		}
		return $html;
	}

	protected function getStyles()
	{
		$ss = StyleService::getInstance();
		if (isset($this->attributes['styles']))
		{
			foreach ($this->attributes['styles'] as $media => $styles)
			{
				foreach (array_keys($styles) as $style)
				{
					$ss->registerStyle($style, $media);
				}
			}
		}
		return $ss->execute(K::HTML, $this->getSkin());
	}

	protected function getLinkTags()
	{
		if (!isset($this->attributes['links'])) {return "";}
		$links = array();
		foreach ($this->attributes['links'] as $linkTagAttributes)
		{
			$link = '<link ';
			foreach ($linkTagAttributes as $attributeName => $value)
			{
				$link .= $attributeName . '="' . htmlspecialchars($value) .'" ';
			}
			$link .= '/>';
			$links[] = $link;
		}
		return implode("\n", $links);
	}

	/**
	 * @return FrameworkSecurityUser
	 */
	public function getSessionUser()
	{
		return HttpController::getInstance()->getContext()->getUser();
	}

	/**
	 * DEPRECATED BLOCK CONTEXT METHOD
	 */

	/**
	 * @deprecated
	 * @return website_persistentdocument_page
	 */
	public function getPageDocument()
	{
		return $this->page;
	}

	/**
	 * @deprecated
	 * @return boolean
	 */
	public function inBackofficeMode()
	{
		return $this->getAttribute(website_BlockAction::BLOCK_BO_MODE_ATTRIBUTE, false);
	}

	/**
	 * @deprecated use getAncestorIds
	 * @return array<integer>
	 */
	public function getAncestors()
	{
		return $this->getAncestorIds();
	}

	/**
	 * @deprecated
	 * @return Integer
	 */
	public function getNearestContainerId()
	{
		// if ancestors size is 2 then you retrieve the website !
		return f_util_ArrayUtils::lastElement($this->getAncestorIds());
	}

	/**
	 * @deprecated
	 * @return Request
	 */
	public function getGlobalRequest()
	{
		return HttpController::getInstance()->getContext()->getRequest();
	}

	/**
	 * @deprecated
	 * @return Context
	 */
	public function getGlobalContext()
	{
		return HttpController::getInstance()->getContext();
	}

	/**
	 * @deprecated
	 * @return boolean
	 */
	public function inIndexingMode()
	{
		return false;
	}

	/**
	 * @deprecated use getSessionUser
	 * @return FrameworkSecurityUser
	 */
	public function getUser()
	{
		return $this->getSessionUser();
	}
	
	public function addContainerStylesheet()
	{		
		$ancestors = array_reverse($this->getAncestorIds());
		foreach ($ancestors as $ancestorId)
		{
			$doc = DocumentHelper::getDocumentInstance($ancestorId);
			if (f_util_ClassUtils::methodExists($doc, 'getStylesheet'))
			{
				$stylesheet = $doc->getStylesheet();
			}
			if ($stylesheet !== null)
			{
				$this->addStyle('modules.website.' . $stylesheet);
				return;
			}
		}
	}
	/**
	 * Doctype for the HTML output, defaults to XHTML 1.0 Strict but if you define DEFAULT_DOC_TYPE to 'XHMTL-1.0-Transitional'
	 * you can swith to XHTML 1.0 Transitional.
	 * @return String
	 */
	public function getDoctype()
	{
		if (defined('DEFAULT_DOC_TYPE') && 'DEFAULT_DOC_TYPE' == 'XHMTL-1.0-Transitional')
		{
			return '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
		}
		return '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
	}
}