<?php
/**
 * @date Thu Mar 29 18:53:08 CEST 2007
 * @author INTbonjF
 */
class LinkHelper
{
	private static $urls = array();
		
	/**
	 * @param array $queryParams
	 * @param website_persistentdocument_website $website
	 * @return f_web_ParametrizedLink
	 */
	public static function getParametrizedLink($queryParams = array(), $website = NULL)
	{
	    if ($website === NULL)
	    {
	        $website = website_WebsiteModuleService::getInstance()->getCurrentWebsite();
	    }
	    $link = new f_web_ParametrizedLink($website->getProtocol(), $website->getDomain(), f_web_HttpLink::SITE_PATH);
	    $link->setQueryParametres($queryParams);
	    return $link;
	}
	
	/**
	 * @param array $queryParams
	 * @return f_web_ParametrizedLink
	 */
	public static function getUIParametrizedLink($queryParams = array())
	{
	    $link = new f_web_ParametrizedLink(Framework::getUIProtocol(), Framework::getUIDefaultHost(), f_web_HttpLink::UI_PATH);
	    $link->setQueryParametres($queryParams);
	    return $link;
	}
	
	/**
	 * @param string $moduleName
	 * @param string $actionName
	 * @param website_persistentdocument_website $website
	 * @return f_web_ParametrizedLink
	 */
	public static function getActionLink($moduleName, $actionName, $website = NULL)
	{
	    if ($website === NULL)
	    {
	        $website = website_WebsiteModuleService::getInstance()->getCurrentWebsite();
	    }
	    $link = new f_web_ParametrizedLink($website->getProtocol(), $website->getDomain(), f_web_HttpLink::SITE_PATH);
	    $link->setQueryParametres(array(AG_MODULE_ACCESSOR => $moduleName, AG_ACTION_ACCESSOR => $actionName));
	    return $link;
	}
		
	/**
	 * @param string $moduleName
	 * @param string $actionName
	 * @return f_web_ParametrizedLink
	 */
	public static function getUIActionLink($moduleName, $actionName)
	{
	    $link = new f_web_ParametrizedLink(Framework::getUIProtocol(), Framework::getUIDefaultHost(), f_web_HttpLink::UI_PATH);
	    $link->setQueryParametres(array(AG_MODULE_ACCESSOR => $moduleName, AG_ACTION_ACCESSOR => $actionName));
	    return $link;
	}
	
	/**
	 * @param string $moduleName
	 * @param string $actionName
	 * @return f_web_ParametrizedLink
	 */
	public static function getUIChromeActionLink($moduleName, $actionName)
	{
		if (!isset($_SESSION['ChromeBaseUri']))
		{
			return self::getUIActionLink($moduleName, $actionName);
		}
	    $link = new f_web_ChromeParametrizedLink($_SESSION['ChromeBaseUri']);
	    $link->setQueryParametres(array(AG_MODULE_ACCESSOR => $moduleName, AG_ACTION_ACCESSOR => $actionName));
	    return $link;
	}	
	
	/**
	 * @param string $ressourceName
	 * @param website_persistentdocument_website $website
	 * @return f_web_ResourceLink
	 */
	public static function getRessourceLink($ressourceName, $website = NULL)
	{
		if ($website === NULL)
	    {
	        $website = website_WebsiteModuleService::getInstance()->getCurrentWebsite();
	    }
	    $link = new f_web_ResourceLink($website->getProtocol(), $website->getDomain());
	    $link->setPath($ressourceName);
	    return $link;
	}	
	

	
	/**
	 * @param string $ressourceName
	 * @return f_web_ResourceLink
	 */
	public static function getUIRessourceLink($ressourceName)
	{
	    $link = new f_web_ResourceLink(Framework::getUIProtocol(), Framework::getUIDefaultHost());
	    $link->setPath($ressourceName);
	    return $link;
	}
	
	/**
	 * @param string $ressourceName
	 * @return f_web_ResourceLink
	 */
	public static function getUIChromeRessourceLink($ressourceName)
	{
		if (!isset($_SESSION['ChromeBaseUri']))
		{
			return self::getUIRessourceLink($ressourceName);
		}
		
	    $link = new f_web_ChromeParametrizedLink($_SESSION['ChromeBaseUri']);
	    $link->setArgSeparator(f_web_HttpLink::ESCAPE_SEPARATOR);
	    $link->setQueryParametres(array(AG_MODULE_ACCESSOR => 'uixul', AG_ACTION_ACCESSOR => 'GetChromeRessource', 'path' => $ressourceName));
	    return $link;
	}	

	/**
	 * @param f_persistentdocument_PersistentDocument $document
	 * @param string $lang
	 * @param array $parameters
	 * @param Boolean $useCache
	 * @return string
	 */
	public static function getDocumentUrl($document, $lang = null, $parameters = array(), $useCache = true)
	{
		if ($useCache)
		{
			$key = md5($document->getId() . $lang . serialize($parameters));
			if (isset(self::$urls[$key]))
			{
				return self::$urls[$key];
			}
		}
		$urs = website_UrlRewritingService::getInstance();

		$url = $urs->getDocumentUrl($document, $lang, $parameters);
		if ($url === null)
		{
			if (Framework::isInfoEnabled())
			{
				Framework::info(__METHOD__ . ' No url rewriting Founded for document : ' . $document->__toString());
			}
			$url = $urs->getNonRewrittenDocumentUrl($document, $lang, $parameters);
		}	
		if ($useCache)
		{
			self::$urls[$key] = $url;
		}
		return $url;
	}
	
	
	/**
	 * @param string $moduleName
	 * @param string $actionName
	 * @param array $parameters
	 * @return string
	 */
	public static function getActionUrl($moduleName, $actionName, $parameters = array())
	{
	    $key = md5($moduleName . $actionName . serialize($parameters));
		if (isset(self::$urls[$key]))
		{
			return self::$urls[$key];
		}
		$url = website_UrlRewritingService::getInstance()->getUrl($moduleName, $actionName, $parameters);
        self::$urls[$key] = $url;
		return $url;
	}	
	
	public static function getTagUrl($tag, $lang = null, $parameters = array())
	{
		$urs = website_UrlRewritingService::getInstance();
		$website = website_WebsiteModuleService::getInstance()->getCurrentWebsite();		
		$url = $urs->getTagUrl($tag, $website, $lang, $parameters);
		if ($url !== null) {return $url;}
		
		$ts = TagService::getInstance();
		try 
		{
			$document = null;
			if ($ts->isExclusiveTag($tag))
			{
				$document = $ts->getDocumentByExclusiveTag($tag);
			}
			else if ($ts->isFunctionalTag($tag))
			{
				$currentPageId = website_WebsiteModuleService::getInstance()->getCurrentPageId();
				if ($currentPageId)
				{
					$currentPage = DocumentHelper::getDocumentInstance($currentPageId);
					$document = $ts->getDocumentBySiblingTag($tag, $currentPage);
				}
			}
			else if ($ts->isContextualTag($tag) && $ts->getTagContext($tag) == 'modules_website/website')
			{
				$currentPageId = website_WebsiteModuleService::getInstance()->getCurrentPageId();
				if ($currentPageId)
				{
					$currentPage = DocumentHelper::getDocumentInstance($currentPageId);
					$website = website_WebsiteModuleService::getInstance()->getParentWebsite($currentPage);
					$document = $ts->getDocumentByContextualTag($tag, $website);
				}
			}
			else
			{
				$taggedDocuments = $ts->getDocumentsByTag($tag);
				if (f_util_ArrayUtils::isNotEmpty($taggedDocuments))
				{
					$document = $taggedDocuments[0];
				}
			}
			
			if ($document !== null)
			{
				return self::getDocumentUrl($document, $lang, $parameters);
			}
			else
			{
				Framework::warn(__METHOD__ . ' no document found for tag ' . $tag);
			}
		} 
		catch (Exception $e)
		{
			Framework::warn(__METHOD__ . ' ' . $e->getMessage());
		}
		return '';
	}

		
	/**
	 * Returns the URL of "what has been given as argument(s)". Well, this is a
	 * Helper method... There are three possible cases as you can see in the
	 * provided examples.
	 * @deprecated use LinkHelper::getDocumentUrl or LinkHelper::getActionUrl
	 * @example LinkHelper::getUrl($myPageDocument)
	 * @example LinkHelper::getUrl($myPageDocument, $lang)
	 * @example LinkHelper::getUrl($myPageDocument, $lang, array('param1'=>'value1', 'param2'=>'value2'))
	 * @example LinkHelper::getUrl('mymodule', 'myaction')
	 * @example LinkHelper::getUrl('mymodule', 'myaction', array('param1'=>'value1', 'param2'=>'value2'))
	 *
	 * @return string
	 */
	public static function getUrl()
	{
		$args = func_get_args();
		$argsCount = count($args);		
		if ($argsCount >= 1 && $args[0] instanceof f_persistentdocument_PersistentDocument)
		{
			if (!isset($args[1]))
			{
				$args[1] = RequestContext::getInstance()->getLang(); // lang
			}
			if (!isset($args[2]))
			{
				$args[2] = array(); // additional parameters
			}
			return self::getDocumentUrl($args[0], $args[1], $args[2]);
		}
		else if (($argsCount == 2 || $argsCount == 3) && is_string($args[0]) && is_string($args[1]))
		{
			if (!isset($args[2]))
			{
				$args[2] = array(); // additional parameters
			}
			if (!isset($args[2]['lang']))
			{
				$args[2]['lang'] = RequestContext::getInstance()->getLang(); // lang
			}
			return self::getActionUrl($args[0], $args[1], $args[2]);
		}
		
		return '';
	}


	/**
	 * Return the URL of the home page of the current website
	 *
	 * @return string
	 */
	public static function getHomeUrl()
	{
	    $ws = website_WebsiteModuleService::getInstance();
	    $url = $ws->getEmptyUrl();
		try
		{
            $website = $ws->getCurrentWebsite();
            if ($website instanceof website_persistentdocument_website
            && ! is_null($page = $ws->getIndexPage($website)))
            {
                $url = LinkHelper::getUrl($page);
            }
		}
		catch (Exception $e)
		{
			Framework::exception($e);
		}
	    return $url;
	}


	/**
	 * Return the URL of the help page of the current website
	 *
	 * @return string
	 */
	public static function getHelpUrl()
	{
	    $ws = website_WebsiteModuleService::getInstance();
	    $url = $ws->getEmptyUrl();
		try
		{
		    $website = $ws->getCurrentWebsite();
            if ($website instanceof website_persistentdocument_website
            && ! is_null($page = $ws->getDocumentByContextualTag(WebsiteConstants::TAG_HELP_PAGE, $ws->getCurrentWebsite())))
            {
    			$url = LinkHelper::getUrl($page);
            }
		}
		catch (Exception $e)
		{
			Framework::exception($e);
		}
	    return $url;
	}


	/**
	 * Build a full link (<a/> element) for the given document.
	 *
	 * @param f_persistentdocument_PersistentDocument $document
	 * @param string $lang
	 * @param string $class
	 * @param string $title
	 * @param array<string=>string> $attributes
	 * @return string
	 */
	public static function getLink($document, $lang = null, $class = 'link', $title = '', $attributes = null)
	{
	    return self::_buildLink($document, $lang, $class, $title, false, $attributes);
	}


	/**
	 * Build a full POPUP link (<a/> element) for the given document.
	 *
	 * @param f_persistentdocument_PersistentDocument $document
	 * @param string $lang
	 * @param string $class
	 * @param string $title
	 * @return string
	 */
	public static function getPopupLink($document, $lang = null, $class = 'link', $title = '', $attributes = null, $width = null, $height = null)
	{
		if ( ! is_null($width) || ! is_null($height) )
		{
			$popup = array();
			if ( ! is_null($width) ) $popup['width'] = $width;
			if ( ! is_null($height) ) $popup['height'] = $height;
		}
		else
		{
			$popup = true;
		}
		return self::_buildLink($document, $lang, $class, $title, $popup, $attributes);
	}


	/**
	 * Build a full link (<a/> element) for the given document.
	 *
	 * @param f_persistentdocument_PersistentDocument $document
	 * @param string $lang
	 * @param string $class
	 * @param string $title
	 * @param boolean $popup
	 * @param array<string=>string> $attributes
	 * @return string
	 */
	public static function _buildLink($document, $lang = null, $class = 'link', $title = '', $popup = false, $attributes = null)
	{
	    if (is_null($lang))
	    {
	        $lang = RequestContext::getInstance()->getLang();
	    }

		if (f_util_ClassUtils::methodExists($document, 'getUrl'))
        {
            $url = $document->getUrl();
        }
        else
        {
            $url = LinkHelper::getDocumentUrl($document, $lang);
        }

        if (f_util_ClassUtils::methodExists($document, 'getNavigationtitle'))
        {
            $label = $document->getNavigationtitle();
        }
        else
        {
            $label = $document->getLabel();
        }

		if (!empty($title))
		{
			$title = ' title="'.substr($title, 0, 80).'"';
		}

		if ($popup !== false)
		{
			$onclick = ' onclick="return accessiblePopup(this';
			if (is_array($popup))
			{
				if (isset($popup['width']) && is_numeric($popup['width']))
				{
					$onclick .= ','.$popup['width'];
					if (isset($popup['height']) && is_numeric($popup['height']))
					{
						$onclick .= ','.$popup['height'];
					}
				}
				else if (isset($popup['height']) && is_numeric($popup['height']))
				{
					$onclick .= ',null,'.$popup['height'];
				}

			}
			$onclick .= ')"';
			if ( is_array($attributes) )
			{
				if (isset($attributes['class']))
				{
					$attributes['class'] .= ' popup';
				}
				else
				{
					$attributes['class'] = 'popup';
				}
			}
			else
			{
				$attributes = array('class' => 'popup');
			}
		}
		else
		{
		    $onclick = '';
		}

		$attributesString = '';
		if ( is_array($attributes) )
		{
			foreach ($attributes as $name => $value)
			{
				if ($name == 'class')
				{
					$class .= ' '.$value;
				}
				else if ($name != 'lang' && $name != 'href')
				{
					$attributesString .= " $name=\"".addcslashes($value, '"')."\"";
				}
			}
		}

		$html = '<a href="'.$url.'" lang="'.$lang.'" xml:lang="'.$lang.'"';
		if ($class !== null)
		{
			$html .= ' class="'.$class.'"';
		}
		$html .= $title.$onclick.$attributesString;
		$html .= '>'.f_Locale::translate($label, null, $lang).'</a>';
		return $html;
	}


	/**
	 * Returns the <a/> element for the link "Add to favorites".
	 *
	 * @param string $label Text in the link.
	 * @param string $title Title of the link (tooltip).
	 * @param string $class CSS class name.
	 * @return string
	 */
	public static function getAddToFavoriteLink($label = null, $title = null, $class = null)
	{
		try
		{
			$url = LinkHelper::getTagUrl(WebsiteConstants::TAG_ADD_TO_FAVORITES_PAGE);
			if (f_util_StringUtils::isEmpty($url))
			{
				$url = website_WebsiteModuleService::getInstance()->getEmptyUrl();
			}
		}
		catch (Exception $e)
		{
			if (Framework::isDebugEnabled())
			{
				Framework::exception($e);
			}
			$url = website_WebsiteModuleService::getInstance()->getEmptyUrl();
		}
		
		if (is_null($label))
		{
			$label = f_Locale::translate('&modules.website.frontoffice.AddToFavorite;');
		}
		if (is_null($title))
		{
			$title = f_Locale::translate('&modules.website.frontoffice.AddToFavoriteTitle;');
		}
		if (is_string($class))
		{
			$class = ' class="'.$class.'"';
		}
		return sprintf(
			'<a href="%s" title="%s"%s onclick="accessibleAddToFavorite(this); return false;">%s</a>',
			$url, $title, $class, $label
			);
	}


	/**
	 * Returns the <a/> element for the link "Print this page".
	 *
	 * @param string $label Text in the link.
	 * @param string $title Title of the link (tooltip).
	 * @param string $class CSS class name.
	 * @return string
	 */
	public static function getPrintLink($label = null, $title = null, $class = null)
	{
		try
		{
			$url = LinkHelper::getTagUrl(WebsiteConstants::TAG_PRINT_PAGE);
			if (f_util_StringUtils::isEmpty($url))
			{
				$url = website_WebsiteModuleService::getInstance()->getEmptyUrl();
			}
		}
		catch (Exception $e)
		{
			if (Framework::isDebugEnabled())
			{
				Framework::exception($e);
			}
			$url = website_WebsiteModuleService::getInstance()->getEmptyUrl();
		}		

		if (is_null($label))
		{
			$label = f_Locale::translate('&modules.website.frontoffice.Print;');
		}
		if (is_null($title))
		{
			$title = f_Locale::translate('&modules.website.frontoffice.PrintTitle;');
		}
		if (is_string($class))
		{
			$class = ' class="'.$class.'"';
		}
		return sprintf(
			'<a href="%s" title="%s"%s onclick="accessiblePrint(this); return false;">%s</a>',
			$url, $title, $class, $label
			);
	}


	/**
	 * Returns the <a/> element for the link to the help page.
	 *
	 * @param string $label Text in the link.
	 * @param string $title Title of the link (tooltip).
	 * @param string $class CSS class name.
	 * @return string
	 */
	public static function getHelpLink($label = null, $title = null, $class = null)
	{
		try
		{
			$url = LinkHelper::getTagUrl(WebsiteConstants::TAG_HELP_PAGE);
			if (f_util_StringUtils::isEmpty($url))
			{
				$url = website_WebsiteModuleService::getInstance()->getEmptyUrl();
			}
		}
		catch (Exception $e)
		{
			if (Framework::isDebugEnabled())
			{
				Framework::exception($e);
			}
			$url = website_WebsiteModuleService::getInstance()->getEmptyUrl();
		}	
		
		if (is_null($label))
		{
			$label = f_Locale::translate('&modules.website.frontoffice.Help;');
		}
		if (is_null($title))
		{
			$title = f_Locale::translate('&modules.website.frontoffice.HelpTitle;');
		}
		if (is_string($class))
		{
			$class = ' class="'.$class.'"';
		}
		return sprintf('<a href="%s" title="%s"%s>%s</a>', $url, $title, $class, $label);
	}


	/**
	 * Returns the <a/> element for the link to the legal notice page.
	 *
	 * @param string $label Text in the link.
	 * @param string $title Title of the link (tooltip).
	 * @param string $class CSS class name.
	 * @return string
	 */
	public static function getLegalNoticeLink($label = null, $title = null, $class = null)
	{
		
		try
		{
			$url = LinkHelper::getTagUrl(WebsiteConstants::TAG_LEGAL_NOTICE_PAGE);
			if (f_util_StringUtils::isEmpty($url))
			{
				$url = website_WebsiteModuleService::getInstance()->getEmptyUrl();
			}
		}
		catch (Exception $e)
		{
			if (Framework::isDebugEnabled())
			{
				Framework::exception($e);
			}
			$url = website_WebsiteModuleService::getInstance()->getEmptyUrl();
		}	

		if (is_null($label))
		{
			$label = f_Locale::translate('&modules.website.frontoffice.LegalNotice;');
		}
		if (is_null($title))
		{
			$title = f_Locale::translate('&modules.website.frontoffice.LegalNoticeTitle;');
		}
		if (is_string($class))
		{
			$class = ' class="'.$class.'"';
		}
		return sprintf('<a href="%s" title="%s"%s>%s</a>', $url, $title, $class, $label);
	}


	/**
	 * Returns the current URL with all the parameters.
	 * If $extraAttributes is an array, the parameters it contains will override
	 * the ones of the current URL.
	 *
	 * @param array $extraAttributes
	 * @return string
	 */
	public static function getCurrentUrl($extraAttributes = array())
	{
		$url = paginator_Url::getInstanceFromCurrentUrl();
		if (is_array($extraAttributes))
		{
			$url->setQueryParameters($extraAttributes);
		}
		return $url->getStringRepresentation();
	}
	
	static function getCurrentUrlComplete($extraAttributes = array())
	{
		$relativeURL = self::getCurrentUrl($extraAttributes);
		return "http".(isset($_SERVER["HTTPS"]) ? "s" : null)."://".$_SERVER["HTTP_HOST"].$relativeURL;		
	}
}
