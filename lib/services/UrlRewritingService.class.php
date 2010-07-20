<?php
/**
 * @date Fri Feb 09 19:03:46 CET 2007
 * @author INTbonjF
 */
class website_UrlRewritingService extends BaseService
{
	const DEFAULT_URL_SUFFIX = '.html';
	
	/**
	 * @var Array
	 */	
	private $m_documentRules = array();
	
	/**
	 * @var Array
	 */	
	private $m_actionRules = array();
	
	/**
	 * @var Array
	 */	
	private $m_tagRules = array();
	
	/**
	 * @var Array
	 */	
	private $m_registeredRules = array();
	
	/**
	 * Unique instance of the UrlRewritter
	 *
	 * @var website_UrlRewritingService.
	 */
	protected static $instance;
	
	/**
	 * Rules array
	 *
	 * @var array
	 */
	protected $m_rules = array();
	
	/**
	 * Path to the compiled rules file (cache)
	 *
	 * @var string
	 */
	protected $m_compiledRulesFile;
	
	/**
	 * Suffix for rewritten URLs (generally '.html')
	 *
	 * @var string
	 */
	protected $m_suffix = self::DEFAULT_URL_SUFFIX;
	
	protected $m_useOnlyRulesTemplates = false;
	
	public function beginOnlyUseRulesTemplates()
	{
		$this->m_useOnlyRulesTemplates = true;
	}
	
	public function endOnlyUseRulesTemplates()
	{
		$this->m_useOnlyRulesTemplates = false;
	}
	
	/**
	 * Builds the unique instance of the UrlRewritter object.
	 */
	protected function __construct()
	{
		$this->m_compiledRulesFile = f_util_FileUtils::buildChangeBuildPath('urlrewriting_rules.php');
		$this->importRules();
	}
	
	/**
	 * Returns the unique instance of the website_UrlRewritingService object. It is created if needed.
	 *
	 * @return website_UrlRewritingService
	 */
	public static function getInstance()
	{
		if (self::$instance === null)
		{
			self::$instance = self::getServiceClassInstance(get_class());
		}
		return self::$instance;
	}
	
	/**
	 * Returns the Rule that matches the URL.
	 *
	 * @param string $url The URL to get the rule for.
	 * @return website_lib_urlrewriting_Rule
	 */
	public function getRuleByUrl($url)
	{
		$pp = $this->getPersistentProvider();
		$info = $pp->getPageForUrl($url, website_WebsiteModuleService::getInstance()->getCurrentWebsite()->getId());
		
		if (!is_null($info))
		{
			$lang = $info['document_lang'];
			if (isset($info['to_url']))
			{
				$rule = new website_lib_urlrewriting_ModuleActionRule('modules_website', $url, 'website', 'Display', array('lang' => array('value' => $lang)));
				$rule->setRedirectionUrl($info['to_url']);
				$rule->setMovedPermanently($info['redirect_type'] == 301);
				return $rule;
			}
			
			$document = DocumentHelper::getDocumentInstance(intval($info['document_id']));			
			if (!($document instanceof website_persistentdocument_page))
			{
				$moduleName = $document->getPersistentModel()->getModuleName();
				$page = $document->getDocumentService()->getDisplayPage($document);
				if ($page === null)
				{
					$rule = new website_lib_urlrewriting_ModuleActionRule("modules_".$moduleName, $url, $moduleName, 'Display', array('id' => array('value' => $info['document_id']), 'lang' => array('value' => $lang), 'module' => array('value' => $moduleName), 'action' => array('value' => 'Display')));
					$rule->match($url);
					return $rule;	
				}
				$request = Controller::getInstance()->getContext()->getRequest(); 
				$moduleParameters = $request->getModuleParameters($moduleName);
				if (!is_array($moduleParameters))
				{
					$moduleParameters = array();
				}
				$moduleParameters[K::COMPONENT_ID_ACCESSOR] = $info['document_id'];
				$request->setParameter($moduleName. 'Param', $moduleParameters);
			}
			else
			{
				$page = $document;
			}
			
			$rule = new website_lib_urlrewriting_DocumentModelRule('modules_website',  $url,  'modules_website/page', 'detail', array('id' => array('value' => $page->getId()), 'lang' => array('value' => $lang), 'module' => array('value' => 'website'), 'action' => array('value' => 'Display')));
			$rule->match($url);
		}
		else
		{
			// find rule for URL
			$rule = $this->findMatchingRule($url);
			if ($rule instanceof website_lib_urlrewriting_DocumentModelRule) 
			{
				$rule->checkMatchRedirection($url);
			}
		}
		
		return $rule;
	}
	
	/**
	 * Finds the first rule that matches the URL.
	 *
	 * @param string $url The URL to get the rule for.
	 * @return website_lib_urlrewriting_Rule
	 */
	private function findMatchingRule($url)
	{
		foreach ($this->m_documentRules as $rules)
		{
			foreach ($rules as $rule)
			{
				if ($rule->match($url))
				{
					return $rule;
				}
			}
		}
		foreach ($this->m_actionRules as $rules)
		{
			foreach ($rules as $rule)
			{
				if ($rule->match($url))
				{
					return $rule;
				}
			}
		}
		foreach ($this->m_tagRules as $rules)
		{
			foreach ($rules as $rule)
			{
				if ($rule->match($url))
				{
					return $rule;
				}
			}
		}
		return null;
	}
	
	/**
	 * @param string $tag
	 * @param string $lang
	 * @return website_lib_urlrewriting_TaggedPageRule
	 */
	private function findRuleByTagAndLang($tag, $lang = null)
	{
		if (isset($this->m_tagRules[$tag]))
		{
			foreach ($this->m_tagRules[$tag] as $rule)
			{
				$definedLang = $rule->getDefinedLang();
				if ($definedLang === $lang)
				{
					return $rule;
				}
				else if ($definedLang === null)
				{
					return $rule;
				}
			}
		}
		return null;
	}
	
	/**
	 * @param string $module
	 * @param string $action
	 * @param string $lang
	 * @return website_lib_urlrewriting_ModuleActionRule
	 */
	private function findRuleByModuleActionAndLang($module, $action, $lang = null)
	{
		if (isset($this->m_actionRules[$module.'#'.$action]))
		{
			foreach ($this->m_actionRules[$module.'#'.$action] as $rule)
			{	
				$definedLang = $rule->getDefinedLang();
				if ($definedLang === $lang)
				{
					return $rule;
				}
				else if ($definedLang === null)
				{
					return $rule;
				}
			}
		}
		return null;
	}
	
	/**
	 * @param f_persistentdocument_PersistentDocument $document
	 * @param string $lang
	 * @return website_lib_urlrewriting_DocumentModelRule
	 */
	private function findRuleByDocumentAndLang($document, $lang = null)
	{
		$documentModelName = $document->getDocumentModelName();
		if (!isset($this->m_documentRules[$documentModelName]))
		{
			return null;
		}
		
		$localizedRule = null;
		$nonLocalizedRule = null;
		$nonLocalizedConditionalRule = null;
			
		foreach ($this->m_documentRules[$documentModelName] as $rule)
		{
			$definedLang = $rule->getDefinedLang();
			// A documentmodel based rule for the given $document has been found.
			// Let's see if the rule is localized and is matching the given $lang.
			if ($definedLang === $lang)
			{	
				// Check rule's condition
				if ($rule->hasCondition())
				{
					if ($rule->checkCondition($document))
					{
						return $rule;
					}
				}
				else if ($localizedRule === null)
				{
					$localizedRule = $rule;
			
				}
			}
			else if ($definedLang === null)
			{
				if ($localizedRule !== null)
				{
					return $localizedRule;
				}
				// Check rule's condition
				if ($rule->hasCondition() && $nonLocalizedConditionalRule === null)
				{
					if ($rule->checkCondition($document))
					{
						$nonLocalizedConditionalRule = $rule;
					}
				}
				else if ($nonLocalizedRule === null)
				{
					$nonLocalizedRule = $rule;
				}
			}
		}
		if ($nonLocalizedConditionalRule)
		{
			return $nonLocalizedConditionalRule;
		}
		return $nonLocalizedRule;
	}
	
	private static $fromURL = array('/[^a-z0-9_\-\.]+/i', '/\-+/i', '/\.+/i');
	private static $toURL = array('-', '-', '.');
	
	/**
	 * Returns the label of the given document to be used in the URL.
	 *
	 * @param string $str
	 * @return string
	 */
	public static function getUrlLabel($str)
	{
		$str = f_util_StringUtils::strip_accents($str);
		// remove the first letter if it is followed by an apostrophe?
		//$str = preg_replace("/^[a-z]'/i", '', $str);
		$str = preg_replace(self::$fromURL, self::$toURL, $str);
		// remove trailing dashes
		$str = trim($str, '-. ');
		return $str;
	}
	
	/**
	 * Builds and returns the URL for a document.
	 *
	 * @param f_persistentdocument_PersistentDocument $document
	 * @param string $lang
	 * @param array $parameters
	 * @return string
	 */
	public function getDocumentUrl($document, $lang = null, $additionnalParameters = array())
	{
		if ($lang === null)
		{
			$lang = RequestContext::getInstance()->getLang();
		}
					
		//Get associated webSite;
		if ($document instanceof website_persistentdocument_website)
		{
			return $this->generateUrl($document, $lang, null, $additionnalParameters);
		}
		else
		{
			$websiteId = $document->getDocumentService()->getWebsiteId($document);
			if ($websiteId === null) 
			{
				$website = website_WebsiteModuleService::getInstance()->getCurrentWebsite();
			}
			else 
			{
				$website = DocumentHelper::getDocumentInstance($websiteId, 'modules_website/website');
			}
			
			if (DocumentHelper::equals($website->getIndexPage(), $document))
			{
				return $this->generateUrl($website, $lang, null, $additionnalParameters);
			}
		}
		
		// Let's see if the page has a static URL defined.
		if (!$this->m_useOnlyRulesTemplates)
		{
			$url = $document->getDocumentService()->getUrlRewriting($document, $lang);
			if ($url !== null)
			{
				return $this->generateUrl($website, $lang, $url, $additionnalParameters);
			}
		}

		$rule = $this->findRuleByDocumentAndLang($document, $lang);
		
		if ($rule === null)
		{
			if (Framework::isDebugEnabled())
			{
				Framework::debug(__METHOD__ . " Unable to rewrite URL for Document \"" . $document->__toString() . "\": no matching rule.");
			}
			return null;
		}
		
		$parameters = array();
		foreach ($rule->getParameters() as $name => $parameter)
		{
			// skip 'module' and 'action' parameters
			if ('module' != $name && 'action' != $name)
			{
				if (isset($parameter['method']))
				{
					$methodName = $parameter['method'];
				}
				else
				{
					$methodName = 'get' . ucfirst($name);
				}
				
				if ($name == 'lang')
				{
					$parameters[$name] = $lang;
				}
				else if (f_util_ClassUtils::methodExists($document, $methodName . 'ForLang'))
				{
					$parameters[$name] = self::getUrlLabel($document->{$methodName . 'ForLang'}($lang));
					if (empty($parameters[$name]))
					{
						if (Framework::isDebugEnabled())
						{
							Framework::debug(__METHOD__ . " " . get_class($document) . '::' . $methodName . 'ForLang returned a null value: cannot build the URL for Document "' . $document->__toString() . '".');
						}
						return null;						
					}
				}
				else if (f_util_ClassUtils::methodExists($document, $methodName))
				{
					$parameters[$name] = self::getUrlLabel($document->{$methodName}());
					if (empty($parameters[$name]))
					{
						if (Framework::isDebugEnabled())
						{
							Framework::debug(__METHOD__ . " " . get_class($document) . '::' . $methodName . ' returned a null value: cannot build the URL for Document "' . $document->__toString() . '".');
						}
						return null;	
					}
				}
				else
				{
					if (Framework::isDebugEnabled())
					{
						Framework::debug(__METHOD__ . " In rule '" . $rule->toString() . "': unable to handle parameter '" . $name . "': method '" . $methodName . "' does not exist or is not callable on '\$document' of type '" . get_class($document) . "'.");
					}
					return null;	
				}
			}
		}
		$result = $this->generateUrlByRule($website, $lang, $rule, $parameters, $additionnalParameters);
		return $result;
	}
	
	/**
	 * Builds and returns the URL for a tag.
	 * @param string $tag
	 * @param website_persistentdocument_website $website
	 * @param string $lang
	 * @param array $parameters
	 * @return string or null
	 */
	public function getTagUrl($tag, $website = null, $lang = null, $additionnalParameters = array())
	{
		if ($lang === null)
		{
			$lang = RequestContext::getInstance()->getLang();
		}
		
		$ts = TagService::getInstance();
		if (!$ts->isFunctionalTag($tag))
		{
			$rule = $this->findRuleByTagAndLang($tag, $lang);
			if ($rule === null)
			{
				$moduleName = null;
				if ($ts->isDetailPageTag($tag, $moduleName))
				{
					$rule = $this->findRuleByModuleActionAndLang($moduleName, 'ViewDetail', $lang);
				}
				else if ($ts->isListPageTag($tag, $moduleName))
				{
					$rule = $this->findRuleByModuleActionAndLang($moduleName, 'ViewList', $lang);
				}
			}
			
			if ($rule instanceof website_lib_urlrewriting_Rule)
			{
				$ruleParameterNames = array_keys($rule->getParameters());
				$ruleParameters = array();
				foreach ($ruleParameterNames as $ruleParameterName)
				{
					if (array_key_exists($ruleParameterName, $additionnalParameters))
					{
						$ruleParameters[$ruleParameterName] = $additionnalParameters[$ruleParameterName];
						unset($additionnalParameters[$ruleParameterName]);
					}
				}
				if ($website === null)
				{
					$website = website_WebsiteModuleService::getInstance()->getCurrentWebsite();
				}
				return $this->generateUrlByRule($website, $lang, $rule, $ruleParameters, $additionnalParameters);
			}
		}
		
		return null;
	}
	
	/**
	 * @param website_persistentdocument_website $website
	 * @param String $lang
	 * @param website_lib_urlrewriting_Rule $rule
	 * @param array $ruleParameters
	 * @param array $additionnalParameters
	 * @return String
	 */
	private function generateUrlByRule($website, $lang, $rule, $ruleParameters, $additionnalParameters)
	{
		$url = $rule->getUrl($ruleParameters);
		if (! $rule->isDirectoryLike() && ! $rule->hasSuffix())
		{
			$url .= $this->m_suffix;
		}
		return $this->generateUrl($website, $lang, strtolower($url), $additionnalParameters);
	}
	
	/**
	 * @param website_persistentdocument_website $website
	 * @param String $lang
	 * @param String $url
	 * @param Array $additionnalParameters
	 * @return String
	 */
	private function generateUrl($website, $lang, $url, $additionnalParameters)
	{
		unset($additionnalParameters['lang']);
		$rq = RequestContext::getInstance();
		try
		{
			$rq->beginI18nWork($lang);
			$link = new f_web_PageLink($website->getProtocol(), $website->getDomain(), $website->getLocalizebypath());
			$link->setPageName($url)->setQueryParameters($additionnalParameters);
			$pageUrl = $link->getUrl();
			$rq->endI18nWork();
		}
		catch (Exception $e)
		{
			$rq->endI18nWork($e);
		}
		return $pageUrl;
	}
	
	/**
	 * Returns the non-rewritten URL of the page able to display the document's detail.
	 *
	 * @param f_persistentdocument_PersistentDocument $document
	 * @param string $lang
	 * @param array<string => string> $parameters
	 * @return string
	 */
	public function getNonRewrittenDocumentUrl($document, $lang = null, $parameters = array())
	{
		$documentService = $document->getDocumentService();
		if (f_util_ClassUtils::methodExists($documentService, 'generateUrl'))
		{
			return $documentService->generateUrl($document, $lang, $parameters);
		}
		
		$module = $document->getPersistentModel()->getModuleName();
		if (! is_array($parameters))
		{
			$parameters = array();
		}
		$parameters[$module . 'Param'] = array(K::COMPONENT_ID_ACCESSOR => $document->getId());
		if (! is_null($lang))
		{
			$parameters[K::LANG_ACCESSOR] = $lang;
		}
		return LinkHelper::getActionUrl($module, 'ViewDetail', $parameters);
	}
	
	/**
	 * Returns the suffix used in the pages URL.
	 *
	 * @return string
	 */
	public function getSuffix()
	{
		return $this->m_suffix;
	}
	
	/**
	 * Sets the suffix to use in the pages URL.
	 *
	 * @return string
	 */
	public function setSuffix($suffix)
	{
		$this->m_suffix = $suffix;
		return $this;
	}
	
	/**
	 * Returns the URL to access the given  module/action, with the given parameters.
	 *
	 * @param string $module The module name.
	 * @param string $action The action name.
	 * @param array $parameters Optionnal URL parameters.
	 * @return string The rewritten URL if possible, or the 'system URL' otherwise.
	 */
	public function getUrl($module, $action, $parameters = null)
	{
		
		$url = null;
		
		if (! is_array($parameters))
		{
			$parameters = array();
		}
		
		if (array_key_exists('lang', $parameters))
		{
			$lang = $parameters['lang'];
		}
		else
		{
			$lang = RequestContext::getInstance()->getLang();
			$parameters['lang'] = $lang;
		}
		
		$rule = $this->findRuleByModuleActionAndLang($module, $action, $lang);
		
		
		if ($rule !== null)
		{	
			$localized = $rule->getDefinedLang() !== null;
			// Add all the parameters defined in the rule, skipping
			// parameters defined in $parameters argument and 'module'/'action'.
			// If the rule is localized, skip the 'lang' parameter.
			foreach ($rule->getParameters() as $name => $param)
			{
				if (isset($param['__parameterType']) && $param['__parameterType'] == 'module')
				{
					if (isset($parameters[$module.'Param['.$name.']']))
					{
						$parameters[$name] = $parameters[$module.'Param['.$name.']'];
						unset($parameters[$module.'Param['.$name.']']);
					}
					else
					{
						unset($parameters[$name]);
					}
				}
				
				if (array_key_exists('value', $param) && !array_key_exists($name, $parameters) && $name != 'module' && $name != 'action' && (($localized && $name != 'lang') || ! $localized))
				{
					$parameters[$name] = $param['value'];
				}
			}
			
			$url = $rule->getUrl($parameters);
			if (substr($url, - 1) == '?')
			{
				$url = substr($url, 0, - 1);
			}
			
			if (array_key_exists('lang', $parameters))
			{
				unset($parameters['lang']);
			}
			
			$rq = RequestContext::getInstance();
			if ($lang === null)
			{
				$lang = $rq->getLang();
			}
			
			try
			{
				$rq->beginI18nWork($lang);
				$currentWebsite = website_WebsiteModuleService::getInstance()->getCurrentWebsite();
				$link = new f_web_PageLink($currentWebsite->getProtocol(), $currentWebsite->getDomain(), $currentWebsite->getLocalizebypath());
				$link->setPageName($url)->setQueryParameters($parameters);
				$rq->endI18nWork();
			}
			catch (Exception $e)
			{
				$rq->endI18nWork($e);
			}
			return $link->getUrl();
		}
		else
		{
			// If $rule is null here, this means that no rule has been found.
			// So we must include the module/action information to the URL parameters.
			$parameters[AG_MODULE_ACCESSOR] = $module;
			$parameters[AG_ACTION_ACCESSOR] = $action;
			
			return LinkHelper::getParametrizedLink($parameters)->getUrl();
		}
	}
	
	/**
	 * Loads the URL rewriting rules.
	 *
	 * @see website_urlrewriting_RulesParser
	 */
	protected function importRules()
	{
		include_once (website_urlrewriting_RulesParser::getInstance()->getCachedFilePath());
	}
	
	/**
	 * @param website_lib_urlrewriting_DocumentModelRule $rule
	 */
	private function addDocumentRule($rule)
	{
		$key = $rule->getDocumentModelName();
		if (!isset($this->m_documentRules[$key]))
		{
			$this->m_documentRules[$key] = array($rule);
		}
		else if ($rule->getDefinedLang() !== null)
		{
			array_unshift($this->m_documentRules[$key], $rule);
		}
		else
		{
			$this->m_documentRules[$key][] = $rule;
		}
		$this->m_registeredRules[$rule->getUniqueId()] = true;
	}
	
	/**
	 * @param website_lib_urlrewriting_ModuleActionRule $rule
	 */
	private function addActionRule($rule)
	{
		$key = $rule->getModule().'#'.$rule->getAction();
		if (!isset($this->m_actionRules[$key]))
		{
			$this->m_actionRules[$key] = array($rule);
		}
		else if ($rule->getDefinedLang() !== null)
		{
			array_unshift($this->m_actionRules[$key], $rule);
		}
		else
		{
			$this->m_actionRules[$key][] = $rule;
		}
		$this->m_registeredRules[$rule->getUniqueId()] = true;
	}
	
	/**
	 * @param website_lib_urlrewriting_TaggedPageRule $rule
	 */
	private function addTagRule($rule)
	{
		$key = $rule->getPageTag();
		if (!isset($this->m_tagRules[$key]))
		{
			$this->m_tagRules[$key] = array($rule);
		}
		else if ($rule->getDefinedLang() !== null)
		{
			array_unshift($this->m_tagRules[$key], $rule);
		}
		else
		{
			$this->m_tagRules[$key][] = $rule;
		}
		$this->m_registeredRules[$rule->getUniqueId()] = true;
	}
	
	/**
	 * Returns all the registered rules.
	 *
	 * @return array<website_lib_urlrewriting_Rule>
	 */
	public function getRules()
	{
		return $this->m_rules;
	}
	
	/**
	 * Removes all the registered rules.
	 */
	public function removeAllRules()
	{
		$this->m_rules = array();
		$this->m_actionRules = array();
		$this->m_documentRules = array();
		$this->m_tagRules = array();
	}
	
	/**
	 * Forwards the execution to the module/action that is able to display the
	 * stuff, after having set right parameters.
	 *
	 * @param website_lib_urlrewriting_Rule $rule
	 * @param Controller $controller
	 */
	public function redirect($rule, $controller = null)
	{
		if ($rule->getRedirectionUrl())
		{
			if ($rule->hasMovedPermanently())
			{
				header("HTTP/1.1 301 Moved Permanently");
			}
			else
			{
				header("HTTP/1.1 302 Found");
			}
			$url = $rule->getRedirectionUrl();
			if (strpos($url, 'http') !== 0)
			{
				$website = website_WebsiteModuleService::getInstance()->getCurrentWebsite();
				$url = $this->generateUrl($website, $rule->getLang(), $rule->getRedirectionUrl(), array());
			}
			header("Location: ".$url);
			exit();
		}
			
		$info = $this->buildRedirectionInfo($rule);	
		if (is_null($info))
		{
			throw new UrlRewritingException("Unable to redirect: [module/action] information is missing.");
		}
		
		$module = $info['module'];
		$action = $info['action'];
		
		if (is_null($controller))
		{
			$controller = HttpController::getInstance();
		}
		$request = $controller->getContext()->getRequest();
		
		// set parameters to the request
		foreach ($info as $name => $value)
		{
			if ($request->hasParameter($name) && is_array($value) && is_array($request->getParameter($name)))
			{
				$request->setParameter($name, array_merge($request->getParameter($name), $value));
			}
			else
			{
				$request->setParameter($name, $value);
			}
		}
		// forwards to the desired module/action
		$controller->forward($module, $action);
	}
	
	public function getRedirectionInfoForRelativeUrl($relativeUrl)
	{
		$rule = $this->getRuleByUrl($relativeUrl);
		if ($rule === null)
		{
			throw new UrlRewritingException(__METHOD . ": $relativeUrl does not match any rule");
		}
		return $this->buildRedirectionInfo($rule);
	}
	
	/**
	 * @param website_lib_urlrewriting_Rule $rule
	 * @return array<'module', 'action', params>
	 */
	protected function buildRedirectionInfo($rule)
	{
		$infos = null;
		$matches = $rule->getMatches();
		if (is_array($matches))
		{
			if (array_key_exists('id', $matches))
			{
				$matches[K::COMPONENT_ID_ACCESSOR] = $matches['id'];
				unset($matches['id']);
			}
			if (array_key_exists('lang', $matches))
			{
				$matches[K::COMPONENT_LANG_ACCESSOR] = $matches['lang'];
				unset($matches['lang']);
			}
			
			// guess module
			if (isset($matches['module']))
			{
				// get module information
				$module = $matches['module'];
				// set all the parameters found as module's parameters,
				// skipping module/action information
				unset($matches['module']);
			}
			else if ($rule instanceof website_lib_urlrewriting_ModuleActionRule)
			{
				$module = $rule->getModule();
			}
			else if ($rule instanceof website_lib_urlrewriting_DocumentModelRule)
			{
				$module = $rule->getParentModule();
			}
			else
			{
				$module = 'website';
			}
			
			// guess action
			if (isset($matches['action']))
			{
				// get action information
				$action = $matches['action'];
				// set all the parameters found as module's parameters,
				// skipping module/action information
				unset($matches['action']);
			}
			else
			{
				if ($rule instanceof website_lib_urlrewriting_ModuleActionRule)
				{
					$action = $rule->getAction();
				}
				else if ($rule instanceof website_lib_urlrewriting_DocumentModelRule)
				{
					$action = 'View' . ucfirst($rule->getViewMode());
				}
				else
				{
					$action = 'Display';
				}
			}
			
			//$params = $rule->getParameters();
			

			if ($rule instanceof website_lib_urlrewriting_TaggedPageRule)
			{
				$tag = $rule->getPageTag();
				// retrieve ID from exclusive tag
				$ds = f_persistentdocument_DocumentService::getInstance();
				
				if (TagService::getInstance()->isExclusiveTag($tag))
				{
					try
					{
						$matches[K::COMPONENT_ID_ACCESSOR] = $ds->getDocumentByExclusiveTag($tag)->getId();
					}
					catch (TagException $e)
					{
						Framework::exception($e);
						$module = 'website';
						$action = 'Error404';
					}
				}
				else if (TagService::getInstance()->isContextualTag($tag))
				{
					try
					{
						$matches[K::COMPONENT_ID_ACCESSOR] = $ds->getDocumentByContextualTag($tag, website_WebsiteModuleService::getInstance()->getCurrentWebsite())->getId();
					}
					catch (TagException $e)
					{
						Framework::exception($e);
						$module = 'website';
						$action = 'Error404';
					}
				}
				else
				{
					if (Framework::isWarnEnabled())
					{
						Framework::warn('[' . __CLASS__ . '::' . __METHOD__ . '] Tag "' . $tag . '" cannot be used to identify a page.');
					}
					$module = 'website';
					$action = 'Error404';
				}
			}
			$infos = array();
			$infos['module'] = $module;
			$infos['action'] = $action;
			if (!is_null($rule->getLang()))
			{
				$matches[K::COMPONENT_LANG_ACCESSOR] = $rule->getLang();
			}
			
			if (!empty($matches))
			{
				$parameters = $rule->getParameters();
				foreach ($matches as $name => $value)
				{
					if (isset($matches[$name]))
					{
						if (isset($parameters[$name]) && isset($parameters[$name]['__parameterType']) && $parameters[$name]['__parameterType'] == 'global')
						{
							$infos[$name] = $value;
						}
						else 
						{
							$infos[$module.'Param'][$name] = $value;
						}
					}
				}				
			}
		}
		
		// Manage parameters mapping
		$mappingArray = $rule->getParameterForwardArray();
		foreach ($rule->getMatches() as $parameter => $paramValue)
		{
			$mappedName = $parameter == K::COMPONENT_ID_ACCESSOR ? 'id' : $parameter;
			if (array_key_exists($mappedName, $mappingArray))
			{
				$destParamArray = $mappingArray[$mappedName];
				// There may be multiple destination parameters for an input parameter
				foreach ($destParamArray as $destParam)
				{
					// destination parameter's name contains '[' and ']': it's an array
					if (($p1 = strpos($destParam, '[')) && ($p2 = strpos($destParam, ']')))
					{
						$infos[substr($destParam, 0, $p1)][substr($destParam, $p1 + 1, $p2 - $p1 - 1)] = $paramValue;
					}
					else
					{
						$infos[$destParam] = $paramValue;
					}
				}
			}
		}
		
		return $infos;
	}
	
	/**
	 * Indicates whether $url has a suffix.
	 * Suffix is any combination of two or more letters and/or digits.
	 *
	 * @param string $url
	 * @return boolean
	 */
	public static function hasSuffix($url)
	{
		return preg_match('/\.([a-z0-9]{2,})$/', $url) ? true : false;
	}
	
	/**
	 * Removes the suffix found in $url and returns the new URL.
	 * Suffix is any combination of two or more letters and/or digits.
	 *
	 * @param string $url
	 * @return string
	 */
	public function removeSuffix($url)
	{
		return preg_replace('/\.([a-z0-9]{2,})$/', '', $url);
	}
	
	
	/**
	 * Indicates whether a rule has already been registered.
	 *
	 * @param website_lib_urlrewriting_Rule $rule
	 * @deprecated (with no replacement)
	 * @return boolean
	 */
	public function ruleExists($rule)
	{
		if (Framework::isWarnEnabled())
		{
			Framework::warn(__METHOD__ . ' has been deprecated. Please remove calls to this method!');
		}
		return isset($this->m_registeredRules[$rule->getUniqueId()]);
	}
	
	/**
	 * Adds a rule to the service.
	 *
	 * @param website_lib_urlrewriting_Rule $rule The rule to add.
	 * @deprecated (with no replacement)
	 */
	public function addRule($rule)
	{
		if ($rule instanceof website_lib_urlrewriting_DocumentModelRule)
		{
			$this->addDocumentRule($rule);
		}
		else if ($rule instanceof website_lib_urlrewriting_ModuleActionRule)
		{
			$this->addActionRule($rule);
		}
		else if ($rule instanceof website_lib_urlrewriting_TaggedPageRule)
		{
			$this->addTagRule($rule);
		}
		else 
		{
			throw new Exception(__METHOD__ . " Invalid rule type " . get_class($rule));
		}
	}
}
