<?php
class website_TalesUrl implements PHPTAL_Tales
{
	/**
	 * url: modifier.
	 */
	static public function url($src, $nothrow)
	{
		$parts = array_map('trim', explode(',', $src));
		if (count($parts) < 1 || empty($parts[0])) {return "''";}
		$p1 = array_shift($parts);
		$document = (is_numeric($p1)) ? $p1 : phptal_tales($p1, $nothrow);
		
		list ($formatterStr, $parametersStr) = self::buildFormattersAndParameters($parts);
		
		return "website_TalesUrl::buildURL($document, array(".implode(', ', $parametersStr)."), $formatterStr)";
	}
	
	/**
	 * tagurl: modifier.
	 */
	static public function tagurl($src, $nothrow)
	{
		$parts = array_map('trim', explode(',', $src));
		if (count($parts) < 1 || empty($parts[0])) {return "''";}
		$tag = phptal_tales(array_shift($parts), $nothrow);
		
		list ($formatterStr, $parametersStr) = self::buildFormattersAndParameters($parts);
		
		return "website_TalesUrl::buildTagURL($tag, array(".implode(', ', $parametersStr)."), $formatterStr)";
	}
	
	/**
	 * actionurl: modifier.
	 */
	static public function actionurl($src, $nothrow)
	{
		$parts = array_map('trim', explode(',', $src));
		if (count($parts) < 2 || empty($parts[0]) || empty($parts[1])) {return "''";}
		$module = phptal_tales(array_shift($parts), $nothrow);
		$action = phptal_tales(array_shift($parts), $nothrow);
		
		list ($formatterStr, $parametersStr) = self::buildFormattersAndParameters($parts);
		
		return "website_TalesUrl::buildActionURL($module, $action, array(".implode(', ', $parametersStr)."), $formatterStr)";
	}
	
	/**
	 * currenturl: modifier.
	 */
	static public function currenturl($src, $nothrow)
	{
		$parts = array_map('trim', explode(',', $src));
		
		list ($formatterStr, $parametersStr) = self::buildFormattersAndParameters($parts);
		
		return "website_TalesUrl::buildCurrentURL(array(".implode(', ', $parametersStr)."), $formatterStr)";
	}
	
	/**
	 * @param array $parts
	 */
	protected static function buildFormattersAndParameters($parts)
	{
		$formatter = 'html';
		$parameters = array();
		foreach ($parts as $data)
		{
			if (empty($data)) {continue;}
			if (strpos($data, '='))
			{
				$subParts = explode('=' , $data);
				if (count($subParts) == 2)
				{
					list($name, $value) = $subParts;
					$name = trim($name);
					$value = trim($value);
					$l = strlen($value);
					if ($l === 0)
					{
						$parameters[$name] = '';
					}
					else
					{
						$parameters[$name] = $value;
					}
				}
			}
			else if (in_array(strtolower($data), array('js', 'attr', 'raw')))
			{
				$formatter = strtolower($data);
			}
		}
		
		$formatterStr = var_export($formatter , true);
				
		$parametersStr = array();
		foreach ($parameters as $name => $value) 
		{
			$l = strlen($value);
			if ($l > 0 && !is_numeric($value) && $value[0] != '\'')
			{
				$parametersStr[] = var_export($name , true). ' => ' . phptal_tales($value, true);
			}
			else if ($l > 1 && $value[0] == '\'' && $value[$l-1] == '\'')
			{
				$value = htmlspecialchars_decode(substr($value, 1, $l - 2));
				$parametersStr[] = var_export($name , true). ' => ' . var_export($value , true);
			}
			else
			{
				$parametersStr[] = var_export($name , true). ' => ' . var_export($value , true);
			}
		}
		return array($formatterStr, $parametersStr);
	}
	
	/**
	 * @param f_persistentdocument_PersistentDocument $document
	 * @param array $parameters
	 * @param string $formatters
	 */
	public static function buildURL($document, $parameters, $formatter)
	{
		$url = '';
		try 
		{
			if (is_int($document))
			{
				$document = DocumentHelper::getDocumentInstance($document);
			}
			
			if ($document instanceof f_persistentdocument_PersistentDocument)
			{
				$lang = self::extractLang($parameters);
				
				$website = null;				
				if (isset($parameters['website']) && ($parameters['website'] instanceof website_persistentdocument_website))
				{
					$website = $parameters['website'];
					unset($parameters['website']);
				}
				
				$link = website_UrlRewritingService::getInstance()->getDocumentLinkForWebsite($document, $website, $lang, $parameters);
				$link->setArgSeparator(f_web_HttpLink::STANDARD_SEPARATOR);
				$url = $link->getUrl();
				
				$url = self::formatUrl($url, $formatter, $lang);
			}
		}
		catch (Exception $e)
		{
			Framework::exception($e);
		}
		return $url;
	}	
	
	/**
	 * @param string $tag
	 * @param array $parameters
	 * @param string $formatters
	 */
	public static function buildTagURL($tag, $parameters, $formatter)
	{
		$url = '';
		try 
		{
			if (strpos($tag, 'ctx_') === 0)
			{
				$tag = 'contextual_website_website_modules_' . substr($tag, 4);
			}
			
			if ($tag)
			{
				$lang = self::extractLang($parameters);
				
				$website = null;
				if (isset($parameters['website']) && ($parameters['website'] instanceof website_persistentdocument_website))
				{
					$website = $parameters['website'];
					unset($parameters['website']);
				}
				
				$url = LinkHelper::getTagUrlForContext($tag, $website, $lang, $parameters);
				$url = self::formatUrl($url, $formatter, $lang);
			}
		}
		catch (Exception $e)
		{
			Framework::exception($e);
		}
		return $url;
	}	
	
	/**
	 * @param string $module
	 * @param string $action
	 * @param array $parameters
	 * @param string $formatters
	 */
	public static function buildActionURL($module, $action, $parameters, $formatter)
	{
		$url = '';
		try 
		{
			if (ModuleService::getInstance()->moduleExists($module))
			{
				$lang = self::extractLang($parameters);
				
				$website = null;				
				if (isset($parameters['website']) && ($parameters['website'] instanceof website_persistentdocument_website))
				{
					$website = $parameters['website'];
					unset($parameters['website']);
				}
				else 
				{
					$website = website_WebsiteModuleService::getInstance()->getCurrentWebsite();
				}
				
				$link = website_UrlRewritingService::getInstance()->getActionLinkForWebsite($module, $action, $website, $lang, $parameters);
				$link->setArgSeparator(f_web_HttpLink::STANDARD_SEPARATOR);
				$url = $link->getUrl();
				
				$url = self::formatUrl($url, $formatter, $lang);
			}
		}
		catch (Exception $e)
		{
			Framework::exception($e);
		}
		return $url;
	}
	
	/**
	 * @param array $parameters
	 * @param string $formatters
	 */
	public static function buildCurrentURL($parameters, $formatter)
	{
		$lang = self::extractLang($parameters);
		$url = LinkHelper::getCurrentUrl($parameters);
		return self::formatUrl($url, $formatter, $lang);
	}
	
	/**
	 * @param string $url
	 * @param string $formatter
	 * @return string
	 */
	protected static function formatUrl($url, $formatter, $lang)
	{
		if ($formatter === 'html')
		{
			$url = LocaleService::getInstance()->transformHtml($url, $lang);
		}
		elseif ($formatter === 'js')
		{
			$url = LocaleService::getInstance()->transformJs($url, $lang);
		}
		elseif ($formatter === 'attr')
		{
			$url = LocaleService::getInstance()->transformAttr($url, $lang);
		}
		return $url;
	}
	
	/**
	 * @param array $parameters
	 * @return string
	 */
	protected static function extractLang(&$parameters)
	{
		if (isset($parameters['lang']))
		{
			$lang = $parameters['lang'];
			unset($parameters['lang']);
		}
		else
		{
			$lang = RequestContext::getInstance()->getLang();
		}
		return $lang;
	}
}