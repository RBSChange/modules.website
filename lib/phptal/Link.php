<?php

//require_once 'PHPTAL/Php/Attribute.php';
//require_once 'PHPTAL/Php/Attribute/TAL/Content.php';

// change:link
//   <a href="#" change:link="page 14526; lang fr; anchor top">...</a>

/**
 * @package phptal.php.attribute
 */
class PHPTAL_Php_Attribute_CHANGE_link extends PHPTAL_Php_Attribute
{
	public function start()
	{
		$parameters = array();
		$lang = null;
		$anchor = 'null';
		$module = null;
		$action = null;
		$popup  = false;
		$back   = false;
		$tag = null;
		$href = null;
		$title = null;
		$documentId = null;
		$classes = array('link');
		$exception = null;
		$forWebsiteId = 'null';
		$home = false;

		$expressions = $this->tag->generator->splitExpression($this->expression);

		foreach ($expressions as $exp)
		{
			try
			{
				list($attribute, $value) = $this->parseSetExpression($exp);
				switch ($attribute)
				{
					case 'back':
						$back = true;
						break 2;
					case 'module':
						$module = $value;
						break;
					case 'action':
						$action = $value;
						break;
					case 'document':
						$documentId = $this->tag->generator->evaluateExpression($value.'/getId');
						break;
					case 'documentId':
						$documentId = $this->tag->generator->evaluateExpression($value);
						break;
					case 'page': // @deprecated use document instead
						$documentId = $this->tag->generator->evaluateExpression($value);
						break;
					case 'pageId': // @deprecated use documentId instead
						$documentId = $value;
						break;
					case 'home':
						$home = true;
						break;
					case 'anchor':
						$anchor = $this->evaluate($value, false);
						break;
					case 'lang':
						$lang = $this->tag->generator->evaluateExpression($value);
						break;
					case 'popup':
						$popup = true;
						$popupParameters = PHPTAL_Php_Attribute_CHANGE_popup::parsePopupArg($value);
						break;
					case 'tag':
						$tag = $value;
						break;
					case 'class-add':
						$classes[] = $value;
						break;
					case 'href':
						$href = "<?php echo ".$this->tag->generator->evaluateExpression($value)."; ?>";
						break;
					case 'title' :
						$title = "<?php echo ".$this->tag->generator->evaluateExpression($value)."; ?>";
						break;
					case 'forWebsite':
						$forWebsiteId = $this->tag->generator->evaluateExpression($value.'/getId');
						break;
					case 'forWebsiteId':
						$forWebsiteId = $this->evaluateParameter($value);
						break;
					default:
						$parameters[$attribute] = $this->tag->generator->evaluateExpression($value);
						break;
				}
			}
			catch (Exception $e)
			{
				$exception = $e;
			}
		}

		// hrefCode
		if ($exception !== null)
		{
			Framework::exception($exception);
			$hrefCode = '#';
			$classes[] = 'link-broken';
			self::addLocaleToTitle($title, '&modules.website.frontoffice.link-broken;');
		}
		else if ($module !== null)
		{
			if ($action === null)
			{
				$action = AG_DEFAULT_ACTION;
			}
			$hrefCode = $this->_getHrefCodeRedirection($module, $action, $lang, $parameters, $anchor, $forWebsiteId);
		}
		else if ($back)
		{
			$hrefCode = "<?php echo (isset(\$_SERVER['HTTP_REFERER'])?\$_SERVER['HTTP_REFERER'] : '');?>";
		}
		else if ($home)
		{
			if ($forWebsiteId && $forWebsiteId !== 'null')
			{
				$websiteId = $forWebsiteId;
			}
			else
			{
				$websiteId = website_WebsiteModuleService::getInstance()->getCurrentWebsite()->getId();
			}
			$hrefCode = $this->_getHrefCode($websiteId, $lang, $parameters, $anchor, $forWebsiteId);
		}
		else if ($tag !== null)
		{
			$hrefCode = $this->_getTagCode($tag, $lang, $parameters, $anchor, $forWebsiteId);
		}
		else if ($href !== null)
		{
			$hrefCode = $href;
		}
		else if ($documentId !== null)
		{
			$hrefCode = $this->_getHrefCode($documentId, $lang, $parameters, $anchor, $forWebsiteId);
		}

		if ($popup)
		{
			$classes[] = 'popup';
			self::addLocaleToTitle($title, '&modules.website.frontoffice.in-a-new-window;');
			$this->tag->attributes['onclick'] = '<?php echo PHPTAL_Php_Attribute_CHANGE_popup::getOnClick('.var_export($popupParameters, true).'); ?>';
		}

		if ($title !== null)
		{
			$this->tag->attributes['title'] = $title;
		}

		if ($this->tag->name == 'form')
		{
			$this->tag->attributes['action'] = $hrefCode;
		}
		else if ($this->tag->name == 'img')
		{
			$this->tag->attributes['src'] = $hrefCode;
		}
		else
		{
			if (!$this->tag->hasAttribute('class'))
			{
				$this->tag->attributes['class'] = join(" ", $classes);
			}
			$this->tag->attributes['href'] = $hrefCode;
		}
	}

	public function end()
	{
	}

	/**
	 * @param integer $documentId
	 * @param string $lang
	 * @param array $parameters
	 * @param string $anchor
	 * @param integer $forWebsiteId
	 * @return string
	 */
	private function _getHrefCode($documentId, $lang, $parameters, $anchor, $forWebsiteId)
	{
		return '<?php echo PHPTAL_Php_Attribute_CHANGE_link::getUrl('.$documentId.', ' . var_export($lang, true) . ', ' . $this->generateParameters($parameters) . ', ' . $anchor . ', ' . $forWebsiteId . '); ?>';
	}

	/**
	 * @param integer $documentId
	 * @param string $lang
	 * @param array $parameters
	 * @param string $anchor
	 * @param integer $forWebsiteId
	 * @return string
	 */
	public static function getUrl($documentId, $lang, $parameters, $anchor, $forWebsiteId)
	{
		try
		{
			$document = DocumentHelper::getDocumentInstance($documentId);
				
			$lang = ($lang === null) ? RequestContext::getInstance()->getLang() : $lang;
			// If the document is not available in the requested lang.
			if (!$document->isLangAvailable($lang))
			{
				$lang = $document->getLang();
			}

			$website = ($forWebsiteId !== null) ? website_persistentdocument_website::getInstanceById($forWebsiteId) : null;
			$url = LinkHelper::getDocumentUrlForWebsite($document, $website, $lang, $parameters);
			if ($anchor)
			{
				if (strpos($url, '#') !== false)
				{
					list($url,) = explode('#', $url);
				}
				$url .= '#'.$anchor;
			}
			return f_util_HtmlUtils::textToHtml($url);
		}
		catch (Exception  $e)
		{
			Framework::exception($e);
		}
		return '#';
	}

	/**
	 * @param string $module
	 * @param string $action
	 * @param string $lang
	 * @param array $parameters
	 * @param string $anchor
	 * @param integer $forWebsiteId
	 * @return string
	 */
	private function _getHrefCodeRedirection($module, $action, $lang, $parameters, $anchor, $forWebsiteId)
	{
		return '<?php echo PHPTAL_Php_Attribute_CHANGE_link::getRedirectionUrl(\''.$module.'\', \'' . $action . '\', ' . var_export($lang, true) . ', ' . $this->generateParameters($parameters) . ', ' . $anchor . ', ' . $forWebsiteId . '); ?>';
	}

	/**
	 * @param string $module
	 * @param string $action
	 * @param string $lang
	 * @param array $parameters
	 * @param string $anchor
	 * @param integer $forWebsiteId
	 * @return string
	 */
	public static function getRedirectionUrl($module, $action, $lang, $parameters, $anchor, $forWebsiteId)
	{
		$website = ($forWebsiteId !== null) ? website_persistentdocument_website::getInstanceById($forWebsiteId) : null;
		$url = LinkHelper::getActionUrlForWebsite($module, $action, $website, $lang, $parameters);
		if ($anchor)
		{
			if (strpos($url, '#') !== false)
			{
				list($url,) = explode('#', $url);
			}
			$url .= '#'.$anchor;
		}
		return f_util_HtmlUtils::textToHtml($url);
	}

	/**
	 * @param string $tag
	 * @param string $lang
	 * @param array $parameters
	 * @param string $anchor
	 * @param integer $forWebsiteId
	 * @return string
	 */
	private function _getTagCode($tag, $lang, $parameters, $anchor, $forWebsiteId)
	{
		return '<?php echo PHPTAL_Php_Attribute_CHANGE_link::getTaggedPage(\'' . $tag . '\',' . var_export($lang, true) .','.$this->generateParameters($parameters) .', ' . $anchor . ', ' . $forWebsiteId . '); ?>';
	}

	/**
	 * @param string $tag
	 * @param string $lang
	 * @param array $parameters
	 * @param string $anchor
	 * @param integer $forWebsiteId
	 * @return string
	 */
	public static function getTaggedPage($tag, $lang, $parameters, $anchor, $forWebsiteId)
	{
		if (strpos($tag, 'ctx_') === 0)
		{
			$tag = 'contextual_website_website_modules_' . substr($tag, 4);
		}

		try
		{
			$context = null;
			if (TagService::getInstance()->isContextualTag($tag))
			{
				$context = ($forWebsiteId !== null) ? website_persistentdocument_website::getInstanceById($forWebsiteId) : null;
			}
			$url = LinkHelper::getTagUrlForContext($tag, $context, $lang, $parameters);
			if (empty($url))
			{
				return '#';
			}
			if ($anchor)
			{
				if (strpos($url, '#') !== false)
				{
					list($url,) = explode('#', $url);
				}
				$url .= '#'.$anchor;
			}
			return f_util_HtmlUtils::textToHtml($url);
		}
		catch (Exception $e)
		{
			Framework::exception($e);
		}
		return '#';
	}

	/**
	 * @param string $exp
	 * @return string
	 */
	protected function parseSetExpression($exp)
	{
		$exp = trim($exp);
		// (dest) (value)
		$matches = array();
		if (preg_match('/^([a-z0-9:\-_\[\]]+)\s+(.*?)$/i', $exp, $matches))
		{
			array_shift($matches);
			return $matches;
		}
		// (dest)
		return array($exp, null);
	}

	/**
	 * @param string $title
	 * @param string $locale
	 */
	private static function addLocaleToTitle(&$title, $locale)
	{
		$message = "(".f_Locale::translate($locale).")";
		$title .= ($title ? '' : ' ') . $message;
	}

	/**
	 * @param array $parameters
	 * @return string
	 */
	private function generateParameters($parameters)
	{
		if (count($parameters) == 0)
		{
			return 'null';
		}
		$str = 'array(';
		foreach ($parameters as $name => $value)
		{
			$str .= "'$name' => " . $value . ",";
		}
		return $str . ')';
	}

	private function evaluateParameter($value)
	{
		$normalizedValue = $this->evaluate($value);
		if ($normalizedValue[0] == '\'')
		{
			$normalizedValue = substr($normalizedValue, 1, strlen($normalizedValue) - 2);
		}
		if (strpos($normalizedValue, '$ctx') === false)
		{
			return var_export(f_util_Convert::fixDataType($normalizedValue), true);
		}
		return $normalizedValue;
	}
}