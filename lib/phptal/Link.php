<?php

//require_once 'PHPTAL/Php/Attribute.php';
//require_once 'PHPTAL/Php/Attribute/TAL/Content.php';

// change:link
//   <a href="#"
//        change:link="page 14526; lang fr; anchor top"
//   >

/**
 * @package phptal.php.attribute
 */
class PHPTAL_Php_Attribute_CHANGE_link extends PHPTAL_Php_Attribute
{
	public function start()
	{
		$parameters = array();
		$lang = '';
		$anchor = 'null';
		$module = null;
		$action = null;
		$popup  = false;
		$back   = false;
		$tag = null;
		$href = null;
		$title = null;
		$pageId = null;
		$classes = array("link");
		$exception = null;

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
						$pageId = $this->tag->generator->evaluateExpression($value.'/getId');
						break;
					case 'home':
						$ws = website_WebsiteModuleService::getInstance();
						$website = $ws->getCurrentWebsite();
						if ($website !== null && ($page = $ws->getIndexPage($website)) !== null)
						{
							$pageId = $page->getId();
						}
						break;
					case 'page':
						$pageId = $this->tag->generator->evaluateExpression($value);
						break;
					case 'anchor':
						$anchor = $this->evaluate($value, false);
						break;
					case 'pageId':
						$pageId = $value;
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
			$hrefCode = $this->_getHrefCodeRedirection($module, $action, $lang, $parameters, $anchor);
		}
		else if ($back)
		{
			$hrefCode = "<?php echo (isset(\$_SERVER['HTTP_REFERER'])?\$_SERVER['HTTP_REFERER'] : '');?>";
		}
		else if ($tag !== null)
		{
			$hrefCode = $this->getTagCode($tag, $lang, $parameters, $anchor);
		}
		else if ($href !== null)
		{
			$hrefCode = $href;
		}
		else if ($pageId !== null)
		{
			$hrefCode = $this->_getHrefCode($pageId, $lang, $parameters, $anchor);
		}

		if ($popup)
		{
			$classes[] = "popup";
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

	private static function addLocaleToTitle(&$title, $locale)
	{
		$message = "(".f_Locale::translate($locale).")";
		if ($title === null)
		{
			$title = $message;
		}
		else
		{
			$title .= " ".$message;
		}
	}

	private function getTagCode($tag, $lang, $parameters, $anchor)
	{
		if (empty($lang))
		{
			$lang = 'null';
		}
		else
		{
			$lang = '\''. $lang . '\'';
		}
		return '<?php echo PHPTAL_Php_Attribute_CHANGE_link::getTaggedPage(\'' . $tag . '\',' . $lang .','.$this->generateParameters($parameters) .', ' . $anchor . '); ?>';
	}

	public function end()
	{
	}

	public function _getHrefCode($pageId, $lang, $parameters, $anchor)
	{
		$code = '<?php ';
		if (empty($lang))
		{
			$lang = 'null';
		}
		else
		{
			$lang = '\''. $lang . '\'';
		}

		$code .= 'echo PHPTAL_Php_Attribute_CHANGE_link::getUrl('.$pageId.', ' . $lang . ', ' . $this->generateParameters($parameters) . ', ' . $anchor . '); ?>';
		return $code;
	}

	public function _getHrefCodeRedirection($module, $action, $lang, $parameters, $anchor)
	{
		$code = '<?php ';
		if (empty($lang))
		{
			$lang = 'null';
		}
		else
		{
			$lang = '\''. $lang . '\'';
		}
		$code .= 'echo PHPTAL_Php_Attribute_CHANGE_link::getRedirectionUrl(\''.$module.'\', \'' . $action . '\', ' . $lang . ', ' . $this->generateParameters($parameters) . ', ' . $anchor . '); ?>';
		return $code;
	}

	public static function getUrl($pageId, $lang, $parameters, $anchor)
	{
		try
		{
			if (is_null($lang))
			{
				$lang = RequestContext::getInstance()->getLang();
			}

			$page = DocumentHelper::getDocumentInstance($pageId);

			// If the page is not available in the requested lang,
			if (!$page->isLangAvailable($lang))
			{
				$lang = $page->getLang();
			}

			$url = LinkHelper::getDocumentUrl($page, $lang, $parameters);
			if ($anchor)
			{
				$url .= '#'.$anchor;
			}
			return $url;
		}
		catch (Exception  $e)
		{
			Framework::exception($e);
			return '#';
		}
	}

	public static function getRedirectionUrl($module, $action, $lang, $parameters, $anchor)
	{
		if (!is_null($lang))
		{
			if (!is_array($parameters))
			{
				$parameters = array();
			}
			$parameters[K::LANG_ACCESSOR] = $lang;
		}
		$url = LinkHelper::getActionUrl($module, $action, $parameters);
		if ($anchor)
		{
			$url .= '#'.$anchor;
		}
		return $url;
	}

	public static function getTaggedPage($tag, $lang, $parameters, $anchor)
	{
		$url = null;
		if (strpos($tag, 'ctx_') === 0)
		{
			$tag = 'contextual_website_website_modules_' . substr($tag, 4);
		}

		try
		{
			$url = LinkHelper::getTagUrl($tag, $lang, $parameters);

			if (empty($url))
			{
				return '#';
			}
			if ($anchor)
			{
				$url .= '#'.$anchor;
			}
			return $url;
		}
		catch (Exception $e)
		{
			Framework::exception($e);
			return '#';
		}
	}

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

	protected function parseSetExpression($exp)
	{
		$exp = trim($exp);
		// (dest) (value)
		if (preg_match('/^([a-z0-9:\-_\[\]]+)\s+(.*?)$/i', $exp, $m)){
			array_shift($m);
			return $m;
		}
		// (dest)
		return array($exp, null);
	}
}
