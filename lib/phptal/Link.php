<?php
// change:link
//   <a href="#" change:link="page 14526; lang fr; anchor top">...</a>

/**
 * @package phptal.php.attribute
 */
class PHPTAL_Php_Attribute_CHANGE_Link extends PHPTAL_Php_Attribute
{
	/**
     * Called before element printing.
     */
    public function before(PHPTAL_Php_CodeWriter $codewriter)
    {
		$parameters = array();
		$lang = 'null';
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

		$expressions = $codewriter->splitExpression($this->expression);
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
						$documentId = $codewriter->evaluateExpression($value.'/getId');
						break;
					case 'documentId':
						$documentId = $codewriter->evaluateExpression($value);
						break;
					case 'home':
						$home = true;
						break;
					case 'anchor':
						$anchor = $codewriter->evaluateExpression($value);
						break;
					case 'lang':
						$lang = $codewriter->evaluateExpression($value);
						break;
					case 'popup':
						$popup = true;
						$popupParameters = PHPTAL_Php_Attribute_CHANGE_Popup::parsePopupArg($value);
						break;
					case 'tag':
						$tag = $value;
						break;
					case 'class-add':
						$classes[] = $value;
						break;
					case 'href':
						$href = "<?php echo ".$codewriter->evaluateExpression($value)."; ?>";
						break;
					case 'title' :
						$title = "<?php echo ".$codewriter->evaluateExpression($value)."; ?>";
						break;
					case 'forWebsite':
						$forWebsiteId = $codewriter->evaluateExpression($value.'/getId');
						break;
					case 'forWebsiteId':
						$forWebsiteId = $codewriter->evaluateExpression($value);
						break;
					default:					
						$parameters[$attribute] = $codewriter->evaluateExpression($value);
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
			self::addLocaleToTitle($title, 'm.website.frontoffice.link-broken');
		}
		else if ($module !== null)
		{
			if ($action === null)
			{
				$action = 'Index';
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
				$websiteId = website_WebsiteService::getInstance()->getCurrentWebsite()->getId();
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
			self::addLocaleToTitle($title, 'm.website.frontoffice.in-a-new-window');
			$this->phpelement->getOrCreateAttributeNode('onclick')
				->setValueEscaped('<?php echo PHPTAL_Php_Attribute_CHANGE_Popup::getOnClick('.var_export($popupParameters, true).'); ?>');
		}

		if ($title !== null)
		{
			$this->phpelement->getOrCreateAttributeNode('title')->setValueEscaped($title);
		}
		
		$tagName = $this->phpelement->getLocalName();
		if ($tagName == 'form')
		{
			$this->phpelement->getOrCreateAttributeNode('action')->setValueEscaped($hrefCode);
		}
		else if ($tagName == 'img')
		{
			$this->phpelement->getOrCreateAttributeNode('src')->setValueEscaped($hrefCode);
		}
		else
		{
			if (!$this->phpelement->hasAttribute('class'))
			{
				$this->phpelement->getOrCreateAttributeNode('class')->setValueEscaped(implode(" ", $classes));
			}
			$this->phpelement->getOrCreateAttributeNode('href')->setValueEscaped($hrefCode);
		}
	}

	/**
     * Called after element printing.
     */
    public function after(PHPTAL_Php_CodeWriter $codewriter)
    {
	}
	
	/**
	 * @see PHPTAL_Php_Attribute::parseSetExpression()
	 * Ajout des caractères [ et ] dans les nom des attributs
	 */
    protected function parseSetExpression($exp)
    {
        $exp = trim($exp);
        // (dest) (value)
        if (preg_match('/^([a-z0-9:\[\]\-_]+)\s+(.*?)$/si', $exp, $m)) {
            return array($m[1], trim($m[2]));
        }
        // (dest)
        return array($exp, null);
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
		return '<?php echo PHPTAL_Php_Attribute_CHANGE_Link::getUrl('.$documentId.', ' . $lang . ', ' . $this->generateParameters($parameters) . ', ' . $anchor . ', ' . $forWebsiteId . '); ?>';
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
			return $url . ($anchor ? '#'.$anchor : '');
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
		return '<?php echo PHPTAL_Php_Attribute_CHANGE_Link::getRedirectionUrl(\''.$module.'\', \'' . $action . '\', ' . $lang . ', ' . $this->generateParameters($parameters) . ', ' . $anchor . ', ' . $forWebsiteId . '); ?>';
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
		return $url . ($anchor ? '#'.$anchor : '');
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
		return '<?php echo PHPTAL_Php_Attribute_CHANGE_Link::getTaggedPage(\'' . $tag . '\',' . $lang .','.$this->generateParameters($parameters) .', ' . $anchor . ', ' . $forWebsiteId . '); ?>';
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
			return $url . ($anchor ? '#'.$anchor : '');
		}
		catch (Exception $e)
		{
			Framework::exception($e);
		}
		return '#';
	}

	/**
	 * @param string $title
	 * @param string $locale
	 */
	private static function addLocaleToTitle(&$title, $locale)
	{
		$message = "(" . LocaleService::getInstance()->transFO($locale, array('attr')).")";
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
}