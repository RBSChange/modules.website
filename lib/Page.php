<?php
interface f_mvc_Context
{
	/**
	 * @param String $name
	 * @param mixed $value
	 */
	function setAttribute($name, $value);
	
	/**
	 * @param String $name
	 * @param String $defaultValue
	 * @return mixed
	 */
	function getAttribute($name, $defaultValue = null);
	
	/**
	 * @return array<String, mixed>
	 */
	function getAttributes();
	
	/**
	 * @param String $name
	 */
	function removeAttribute($name);
	
	/**
	 * @param String $name
	 * @return Boolean
	 */
	function hasAttribute($name);
	
	/**
	 * @param String $name
	 * @return Boolean
	 */
	function hasNonEmptyAttribute($name);
}

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
		$website = null;
		
		if (!$page->isNew())
		{
			website_PageService::getInstance()->setCurrentPageId($page->getId());
			$website =  website_WebsiteService::getInstance()->getCurrentWebsite();
		}
			
		$prs = website_PageRessourceService::getInstance();
		$prs->setPage($page);
			
		$this->attributes['title'] = $page->getMetatitle();
		$this->attributes['description'] = $page->getDescription();
		$this->attributes['keywords'] = $page->getKeywords();
		$this->addMeta('robots', $page->getRobotsmeta());
		if ($website !== null)
		{
			$markers = website_MarkerService::getInstance()->getByWebsiteAndLang($website, RequestContext::getInstance()->getLang());
			foreach ($markers as $marker) 
			{
				$mms = $marker->getDocumentService();
				$this->appendToPlainMarker($mms->getHtmlBody($marker));						
				$this->appendToPlainHeadMarker($mms->getHtmlHead($marker));
			}
		}
		
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
	 * @return String
	 */
	public function getTitle()
	{
		return str_replace($this->metaReplaceFrom, $this->metaReplaceTo, $this->attributes['title']);
	}

	/**
	 * @see block_Context::addScript($string)
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
	 * @see block_Context::getNavigationtitle()
	 */
	public function getNavigationtitle()
	{
		return $this->page->getNavigationLabel();
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
	 * @see block_Context::getAncestors()
	 * @return array<integer>
	 */
	public function getAncestorIds()
	{
		if (!isset($this->attributes['ancestorsids']))
		{

			$ancestors = website_PageService::getInstance()->getCurrentPageAncestors();
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
		$this->metas[] = array($name, $content, $scheme, $isHttpEquiv);
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
	 * @param string $paramName
	 * @param string $paramValue
	 * @param string $paramModule
	 */
	public function addCanonicalParam($paramName, $paramValue, $paramModule = null)
	{
		if (!isset($this->attributes['canonical']))
		{
			$this->attributes['canonical'] = array();
		}
		if ($paramModule !== null)
		{
			$this->attributes['canonical'][$paramModule . 'Param'][$paramName] = $paramValue;
		}
		else
		{
			$this->attributes['canonical'][$paramName] = $paramValue;
		}
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
		
		// Add the link only if it is not already added.
		if (isset($this->attributes['links']))
		{
			foreach ($this->attributes['links'] as $link)
			{
				if ($href == $link['href'] && $relation == $link['rel'] && $type == $link['type'])
				{
					return;
				}
			}
		}

		$linkParams = array();
		if ($href !== null)
		{
			$linkParams['href'] = $href;
		}
		if ($relation !== null)
		{
			$linkParams['rel'] = $relation;
		}
		if ($type !== null)
		{
			$linkParams['type'] = $type;
		}
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
	public function setPlainMarker($marker, $position = 'bottom')
	{
		$this->attributes['plainmarker-' . $position] = $marker;
		return $this;
	}

	/**
	 * @param string $marker
	 * @return website_Page
	 */
	public function appendToPlainMarker($marker, $position = 'bottom')
	{
		$key = 'plainmarker-' . $position;
		if (!isset($this->attributes[$key]))
		{
			$this->setPlainMarker($marker, $position);
		}
		else
		{
			$this->attributes[$key] .= "\n" . $marker;
		}
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getPlainMarker($position = 'bottom')
	{
		$key = 'plainmarker-' . $position;
		return isset($this->attributes[$key]) ? $this->attributes[$key] : '';
	}

	/**
	 * @param string $marker
	 * @return website_Page
	 */
	public function setPlainHeadMarker($marker)
	{
		$this->attributes['plainheadmarker'] = $marker;
		return $this;
	}

	/**
	 * @param string $marker
	 * @return website_Page
	 */
	public function appendToPlainHeadMarker($marker)
	{
		if (!isset($this->attributes['plainheadmarker']))
		{
			$this->setPlainHeadMarker($marker);
		}
		else
		{
			$this->attributes['plainheadmarker'] .= "\n" . $marker;
		}
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getPlainHeadMarker()
	{
		return isset($this->attributes['plainheadmarker']) ? $this->attributes['plainheadmarker'] : '';
	}	
	
	/**
	 * @param string $htmlBody
	 * @param string $templatePath
	 */
	public function renderHTMLBody($htmlBody, $templatePath)
	{
		$openingBodyMarker = $this->getPlainMarker('top');
		if ($openingBodyMarker !== '' && website_PageRessourceService::getInstance()->getUseMarkers())
		{
			$openingBodyTagEnd = strpos($htmlBody, '>');
			$htmlBody = substr_replace($htmlBody, $openingBodyMarker, $openingBodyTagEnd+1, 0);
		}
		
		$closingBodyMarker = $this->getPlainMarker('bottom');
		if ($closingBodyMarker !== '' && website_PageRessourceService::getInstance()->getUseMarkers())
		{
			$htmlBody = str_replace('</body>', $closingBodyMarker . PHP_EOL . '</body>', $htmlBody);
		}
		$this->htmlBody = $htmlBody;
		include($templatePath);
	}

	//Templateting Function

	public function getLang()
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
		$data = array('id' => $this->getId(), 'lang' => $this->getLang(), 'dev_mod' => Framework::inDevelopmentMode());
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
	public final function getSkin()
	{
		if ($this->skin === false)
		{
			$this->skin = website_PageService::getInstance()->getSkin($this->getPersistentPage());
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

		$skin = $this->getSkin();
		$prs->setSkin($skin);
		if ($skin && $skin->isNew())
		{
			$style = $prs->getPageStylesheetInLine();
			if ($style)
			{
				$styleInclusions[] = $style;
			}
		}
		else
		{
			$style = $prs->getPageStylesheetInclusion();
			if ($style)
			{
				$styleInclusions[] = $style;
			}
			
			$style = $prs->getPagePrintStylesheetInclusion();
			if ($style)
			{
				$styleInclusions[] = $style;
			}
		}

		return implode(PHP_EOL, $styleInclusions);
	}

	protected function getScripts()
	{
		$prs = website_PageRessourceService::getInstance();
		$html = $prs->getPageJavascriptInclusion();
		if (isset($this->attributes['scripts']))
		{
			if ($html === null)
			{
			 	$html = $prs->getPageJavascriptInlineInclusion(array_keys($this->attributes['scripts']));
			}
			else
			{
				$html .= "\n" . $prs->getPageJavascriptInlineInclusion(array_keys($this->attributes['scripts']));
			}
		}
		return $html;
	}

	protected function getStyles()
	{
		$ss = website_StyleService::getInstance();
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
		return $ss->execute('html', $this->getSkin());
	}

	protected function getLinkTags()
	{
		$hasCanonical = false;
		if (!isset($this->attributes['links'])) {return "";}
		$links = array();
		foreach ($this->attributes['links'] as $linkTagAttributes)
		{
			$hasCanonical = $hasCanonical || (isset($linkTagAttributes['rel']) && $linkTagAttributes['rel'] === 'canonical');
			$link = '<link ';
			foreach ($linkTagAttributes as $attributeName => $value)
			{
				$link .= $attributeName . '="' . htmlspecialchars($value) .'" ';
			}
			$link .= '/>';
			$links[] = $link;
		}
		
		if (!$hasCanonical && isset($this->attributes['canonical']))
		{
			$partURI = explode('?', RequestContext::getInstance()->getPathURI());
			$originalParams = array();
			if (isset($partURI[1])) {parse_str($partURI[1], $originalParams);}
			$canonicalParams = $this->attributes['canonical'];
			f_web_HttpLink::sortQueryParamerters($canonicalParams);
			if (http_build_query($canonicalParams) !== http_build_query($originalParams))
			{
				$link = new f_web_ParametrizedLink(RequestContext::getInstance()->getProtocol(), $this->getWebsite()->getDomain(), $partURI[0]);
				$link->setQueryParameters($canonicalParams);
				$links[] = '<link rel="canonical" href="'.htmlspecialchars($link->getUrl()) .'" />';
			}
		}
		return implode("\n", $links);
	}

	/**
	 * @return change_User
	 */
	public function getSessionUser()
	{
		return change_Controller::getInstance()->getUser();
	}

	public function addContainerStylesheet()
	{	
		$stylesheet = website_PageRessourceService::getInstance()->getContainerStyleIdByAncestorIds($this->getAncestorIds());
		if ($stylesheet !== null)
		{
			$this->addStyle($stylesheet);
		}
	}
	
	private $docType;
	
	public function setDoctype($docType)
	{
		$this->docType = $docType;	
	}
	
	public function getDoctype()
	{
		if ($this->docType)
		{
			return $this->docType;
		}
		return '';
	}
	
	// Deprecated
	
	/**
	 * @deprecated (will be removed in 4.0)
	 */
	public function inBackofficeMode()
	{
		return $this->getAttribute(website_BlockAction::BLOCK_BO_MODE_ATTRIBUTE, false);
	}

	/**
	 * @deprecated (will be removed in 4.0) use getSessionUser
	 */
	public function getUser()
	{
		return $this->getSessionUser();
	}
}