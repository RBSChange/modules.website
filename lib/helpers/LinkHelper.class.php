<?php

class LinkHelper
{	
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
	    $link->setQueryParameters($queryParams);
	    return $link;
	}
		
	/**
	 * @param array $queryParams
	 * @return f_web_ParametrizedLink
	 */
	public static function getUIParametrizedLink($queryParams = array())
	{
	    $link = new f_web_ParametrizedLink(Framework::getUIProtocol(), Framework::getUIDefaultHost(), f_web_HttpLink::UI_PATH);
	    $link->setQueryParameters($queryParams);
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
	    $lang = RequestContext::getInstance()->getLang();
	    $link = website_UrlRewritingService::getInstance()->getDefaultActionWebLink($moduleName, $actionName, $website, $lang, array());
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
	    $link->setQueryParameters(array('module' => $moduleName, 'action' => $actionName));
	    return $link;
	}
	
	/**
	 * @param string $moduleName
	 * @param string $actionName
	 * @return f_web_ParametrizedLink
	 */
	public static function getUIChromeActionLink($moduleName, $actionName)
	{
		$chromeUri = change_Controller::getInstance()->getStorage()->read('uixul_ChromeBaseUri');
		if (!$chromeUri)
		{
			return self::getUIActionLink($moduleName, $actionName);
		}
	    $link = new f_web_ChromeParametrizedLink($chromeUri);
	    $link->setQueryParameters(array('module' => $moduleName, 'action' => $actionName));
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
		$chromeUri = change_Controller::getInstance()->getStorage()->read('uixul_ChromeBaseUri');
		if (!$chromeUri)
		{
			return self::getUIRessourceLink($ressourceName);
		}
		
	    $link = new f_web_ChromeParametrizedLink($chromeUri);
	    $link->setArgSeparator(f_web_HttpLink::ESCAPE_SEPARATOR);
	    $link->setQueryParameters(array('module' => 'uixul', 'action' => 'GetChromeRessource', 'path' => $ressourceName));
	    return $link;
	}	

	/**
	 * @param f_persistentdocument_PersistentDocument $document
	 * @param string $lang
	 * @param array $parameters
	 * @return string or null
	 */
	public static function getDocumentUrl($document, $lang = null, $parameters = array())
	{
		return self::getDocumentUrlForWebsite($document, null, $lang, $parameters);
	}
	
	/**
	 * @param f_persistentdocument_PersistentDocument $document
	 * @param website_persistentdocument_website $website
	 * @param string $lang
	 * @param array $parameters
	 * @return string or null
	 */
	public static function getDocumentUrlForWebsite($document, $website, $lang = null, $parameters = array())
	{
		if (!($document instanceof f_persistentdocument_PersistentDocument))
		{
			Framework::error(f_util_ProcessUtils::getBackTrace());
			return null;
		}
		if ($lang === null) {$lang = RequestContext::getInstance()->getLang();}
		return website_UrlRewritingService::getInstance()->getDocumentLinkForWebsite($document, $website, $lang, $parameters)->getUrl();
	}
	
	/**
	 * @param string $moduleName
	 * @param string $actionName
	 * @param array $parameters
	 * @return string or null
	 */
	public static function getActionUrl($moduleName, $actionName, $parameters = array())
	{
		return self::getActionUrlForWebsite($moduleName, $actionName, null, null, $parameters);
	}	
	
	/**
	 * @param string $moduleName
	 * @param string $actionName
	 * @param website_persistentdocument_website $website
	 * @param string $lang
	 * @param array $parameters
	 * @return string or null
	 */
	public static function getActionUrlForWebsite($moduleName, $actionName, $website = null, $lang = null, $parameters = array())
	{
		if (empty($moduleName) || empty($actionName))
		{
			Framework::error(f_util_ProcessUtils::getBackTrace());
			return null;
		}
		if ($website === null){$website = website_WebsiteModuleService::getInstance()->getCurrentWebsite();}
		if ($lang === null) {$lang = RequestContext::getInstance()->getLang();}
		return website_UrlRewritingService::getInstance()->getActionLinkForWebsite($moduleName, $actionName, $website, $lang, $parameters)->getUrl();
	}
	
	/**
	 * @param f_persistentdocument_PersistentDocument $document
	 */
	public static function getPermalink($document)
	{
		return self::getActionUrl('website', 'Permalink', array('cmpref' => $document->getId()));
	}
	
	/**
	 * @param string $tag
	 * @param string $lang
	 * @param array $parameters
	 * @return string or null
	 */
	public static function getTagUrl($tag, $lang = null, $parameters = array())
	{
		return self::getTagUrlForContext($tag, null, $lang, $parameters);
	}
	
	/**
	 * @param string $tag
	 * @param f_persistentdocument_PersistentDocument $context
	 * @param string $lang
	 * @param array $parameters
	 * @return string or null
	 */
	public static function getTagUrlForContext($tag, $context = null, $lang = null, $parameters = array())
	{
		if (empty($tag))
		{
			Framework::error(f_util_ProcessUtils::getBackTrace());
			return null;
		}		
		if ($lang === null) {$lang = RequestContext::getInstance()->getLang();}
		$website = ($context instanceof website_persistentdocument_website) ? $context : null;
		
		$urs = website_UrlRewritingService::getInstance();		
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
				$pageId = null;
				if ($context === null)
				{
					$pageId = website_WebsiteModuleService::getInstance()->getCurrentPageId();				
				}
				else if ($context instanceof website_persistentdocument_page)
				{
					$pageId = $context->getId();
				}
				else if ($context instanceof website_persistentdocument_topic)
				{
					$page = $context->getIndexPage();
					if ($page) {$pageId = $page->getId();}
				}
				
				if ($pageId)
				{
					$currentPage = DocumentHelper::getDocumentInstance($pageId);
					$document = $ts->getDocumentBySiblingTag($tag, $currentPage);
				}
			}
			else if ($ts->isContextualTag($tag) && $ts->getTagContext($tag) == 'modules_website/website')
			{
				if ($context === null)
				{
					$website = website_WebsiteModuleService::getInstance()->getCurrentWebsite();
				}
				else if ($context instanceof f_persistentdocument_PersistentDocument)
				{
					$websiteId = $context->getDocumentService()->getWebsiteId($context);
					if ($websiteId) {$website = website_persistentdocument_website::getInstanceById($websiteId);}
				}
				
				if ($website !== null && !$website->isNew())
				{
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
				return $urs->getDocumentLinkForWebsite($document, $website, $lang, $parameters)->getUrl();
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
		return null;
	}

	/**
	 * Return the URL of the home page of the current website
	 * @return string
	 */
	public static function getHomeUrl()
	{
	    $website = website_WebsiteModuleService::getInstance()->getCurrentWebsite();
	    $lang = RequestContext::getInstance()->getLang();
        return website_UrlRewritingService::getInstance()->getRewriteLink($website, $lang, '')->getUrl();
	}


	/**
	 * Return the URL of the help page of the current website
	 * @return string
	 */
	public static function getHelpUrl()
	{
	    $ws = website_WebsiteModuleService::getInstance();
		try
		{
		    $website = $ws->getCurrentWebsite();
            $page = TagService::getInstance()->getDocumentByContextualTag(WebsiteConstants::TAG_HELP_PAGE, $website);
            if ($page !== null)
            {
    			return self::getDocumentUrl($page);
            }
		}
		catch (Exception $e)
		{
			Framework::exception($e);
		}
	    return $ws->getEmptyUrl();
	}
	
	/**
	 * @param string $url
	 * @return f_web_ParametrizedLink
	 */
	public static function buildLinkFromUrl($url)
	{
		if (f_util_StringUtils::isEmpty($url)) {return null;}
		$infos = parse_url($url);
		$link = new f_web_ParametrizedLink($infos['scheme'], $infos['host'], (isset($infos['path'])) ? $infos['path']: '/');
		if (isset($infos['query']) && $infos['query'] != '')
		{
			$parameters = array();
			parse_str($infos['query'], $parameters);
			if (count($parameters))
			{
				$link->setQueryParameters($parameters);
			}
		}
		if (isset($infos['fragment']) && $infos['fragment'] != '')
		{
			$link->setFragment($infos['fragment']);
		}
		return $link;
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
        else if (f_util_ClassUtils::methodExists($document, 'getLabelAsHtml'))
        {
            $label = $document->getLabelAsHtml();
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
	 * @param array $extraAttributes
	 * @return string
	 */
	public static function getCurrentUrl($extraAttributes = array())
	{
		$rq = RequestContext::getInstance();
		if ($rq->getAjaxMode())
		{
			$requestUri = $rq->getAjaxFromURI();
		}
		else
		{
			$requestUri = $rq->getPathURI();
		}
		$parts = explode('?', $requestUri);
		$currentLink = new f_web_ParametrizedLink($rq->getProtocol(), $_SERVER['SERVER_NAME'], $parts[0]);
		if (isset($parts[1]) && $parts[1] != '')
		{
			parse_str($parts[1], $queryParameters);
			$currentLink->setQueryParameters($queryParameters);
		}
		if (is_array($extraAttributes) && count($extraAttributes))
		{
			foreach ($extraAttributes as $name => $value) 
			{
				$currentLink->setQueryParameter($name, $value);
			}
		}
		return $currentLink->getUrl();
	}
}