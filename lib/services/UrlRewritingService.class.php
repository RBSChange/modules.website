<?php
/**
 * @package modules.website
 * @method website_UrlRewritingService getInstance()
 */
class website_UrlRewritingService extends change_BaseService
{
	/**
	 * @param f_persistentdocument_PersistentDocument $document
	 * @param string $lang
	 * @param array $parameters
	 * @return string
	 */
	public function getDocumentUrl($document, $lang = null, $parameters = array())
	{
		$lang = ($lang !== null) ? $lang : RequestContext::getInstance()->getLang();
		return $this->getDocumentLinkForWebsite($document, null, $lang, $parameters)->getUrl();
	}
	
	/**
	 * @param f_persistentdocument_PersistentDocument $document
	 * @param website_persistentdocument_website $website
	 * @param string $lang
	 * @param array $parameters
	 * @return f_web_ParametrizedLink
	 */
	public function getDocumentLinkForWebsite($document, $website, $lang, $parameters = array())
	{
		if ($document === null || $lang === null) {return f_web_ParametrizedLink::getNullLink();}
		$websiteIds = $document->getDocumentService()->getWebsiteIds($document);
		if (is_array($websiteIds) && count($websiteIds) === 0) {return f_web_ParametrizedLink::getNullLink();}
		
		if (!is_array($parameters)) {$parameters = array();}
		
		$targetWebsite = $website;
		if ($targetWebsite === null)
		{
			$targetWebsite = website_WebsiteService::getInstance()->getCurrentWebsite();			
			if ($websiteIds !== null)
			{
				if (!in_array($targetWebsite->getId(), $websiteIds))
				{
					$targetWebsite = DocumentHelper::getDocumentInstance(f_util_ArrayUtils::firstElement($websiteIds));
				}
			}
		}
		else if (is_array($websiteIds) && !in_array($targetWebsite->getId(), $websiteIds))
		{
			return f_web_ParametrizedLink::getNullLink();
		}
		
		$path = $this->getCustomPath($document, $targetWebsite, $lang);		
		if ($path !== null)
		{
			$link = $this->getRewriteLink($targetWebsite, $lang, $path, $parameters);
		}
		else
		{
			$link = $document->getDocumentService()->getWebLink($this, $document, $targetWebsite, $lang, $parameters);
			if ($link === null)
			{
				$path = $this->getDocumentDefaultPath($document, $lang);
				$link = $this->getRewriteLink($targetWebsite, $lang, $path, $parameters);
			}
		}
		return $link;
	}

	/**
	 * @param string $str
	 * @return string
	 */
	public function encodePathString($str)
	{
		if (empty($str))
		{
			return '';
		}
		$str = f_util_StringUtils::stripAccents($str);
		$str = preg_replace(array('/[^a-zA-Z0-9_\-]+/', '/\-+/'), array('-', '-'), $str);
		if ($str != '-')
		{
			$str = trim($str, '-');
		}
		return $str;
	}
	
	/**
	 * @param f_persistentdocument_PersistentDocument $document
	 * @return string
	 */
	public function getDocumentDefaultPath($document, $lang)
	{
		$model = $document->getPersistentModel();
		$label = ($model->isLocalized() && $document->isLangAvailable($lang)) ? $document->getLabelForLang($lang) :  $document->getVoLabel();
		return '/' . $model->getModuleName() . '/' .$this->encodePathString($label) . ',' . $document->getId() . '.html';
	}
	
	
	/**
	 * @param website_persistentdocument_website $website
	 * @param string $lang
	 * @return string || null
	 */
	public function getPathPrefix($website, $lang)
	{
		if ($website === null || $lang === null || !$website->isLangAvailable($lang))
		{
			return null;
		}
		return ($website->getLocalizebypath()) ?  '/' . $lang : '';
	}
	
	
	/**
	 * @param website_persistentdocument_website $website
	 * @param string $lang
	 * @param string $path
	 * @param array $queryParams
	 * @return f_web_ParametrizedLink
	 */
	public function getRewriteLink($website, $lang, $path, $queryParams = array())
	{
		if ($path === null || $website === null || $lang === null || !$website->isLangAvailable($lang)) 
		{
			return f_web_ParametrizedLink::getNullLink();
		}
		$pathPrefix = ($website->getLocalizebypath()) ?  '/' . $lang : '';
		$link = new f_web_ParametrizedLink($website->getProtocol(), $website->getDomainForLang($lang), $pathPrefix . $path);
		if (f_util_ArrayUtils::isNotEmpty($queryParams))
		{
			$link->setQueryParameters($queryParams);
		}
		return $link;
	}	
	
	/**
	 * @param string $moduleName
	 * @param $lang $actionName
	 * @param string $lang
	 * @param array $parameters
	 * @return string
	 */
	public function getActionUrl($moduleName, $actionName, $lang = null, $parameters = array())
	{
		$website = website_WebsiteService::getInstance()->getCurrentWebsite();
		$lang = ($lang !== null) ? $lang : RequestContext::getInstance()->getLang();
		return $this->getActionLinkForWebsite($moduleName, $actionName, $website, $lang, $parameters)->getUrl();
	}	
	
	/**
	 * @param string $moduleName
	 * @param string $actionName
	 * @param website_persistentdocument_website $website
	 * @param string $lang
	 * @param array $parameters
	 * @return f_web_ParametrizedLink
	 */
	public function getActionLinkForWebsite($moduleName, $actionName, $website, $lang, $parameters = array())
	{
		if ($moduleName === null || $actionName === null || $website === null || $lang === null) 
		{
			return f_web_ParametrizedLink::getNullLink();
		}
		if (!is_array($parameters)) {$parameters = array();}
		//mod
		$moduleService = ModuleBaseService::getInstanceByModuleName($moduleName);
		if ($moduleService === null)
		{
			Framework::error(__METHOD__ . ' ' . $moduleName . ' has no module service!');
			$moduleService = ModuleBaseService::getInstance();
		}
		$path = $moduleService->generateActionRewritePath($this, $moduleName, $actionName, $website, $lang, $parameters);
		if ($path !== null)
		{
			return $this->getRewriteLink($website, $lang, $path, $parameters);
		}
		return $this->getDefaultActionWebLink($moduleName, $actionName, $website, $lang, $parameters);
	}	
	
	/**
	 * @param string $moduleName
	 * @param string $actionName
	 * @param website_persistentdocument_website $website
	 * @param string $lang
	 * @param array $parameters
	 * @return string
	 */
	public function getActionRulePath($moduleName, $actionName, $website, $lang, &$parameters)
	{
		$rewriteRules = $this->getRewriteRules();
		$moduleAction = $moduleName . '/' . $actionName;
		if (isset($rewriteRules['actions'][$moduleAction]))
		{
			$rule = $rewriteRules['actions'][$moduleAction];
			$pathTemplate = $rule['lang'][$lang];
			$moduleNameKey = $moduleName . 'Param';
			$mParameters = isset($parameters[$moduleNameKey]) && is_array($parameters[$moduleNameKey]) ? $parameters[$moduleNameKey] : array();
										
			$matches = array();
			preg_match_all('/\$\{([a-zA-Z0-9]+)\}/', $pathTemplate, $matches, PREG_SET_ORDER);
			foreach ($matches as $match)
			{
				$pn = $match[1];
				if (isset($mParameters[$pn]))
				{
					$value = $mParameters[$pn];
				}
				else if (isset($parameters[$pn]))
				{
					$value = $parameters[$pn];
				}
				else
				{
					$value = '-' . $pn . '-';
				}
				$replace = (is_string($value)) ? $this->encodePathString($value) : $value;
				if ($replace === $value) 
				{
					unset($parameters[$pn]); 
					unset($mParameters[$pn]);
				}
				$pathTemplate = str_replace($match[0], $replace, $pathTemplate);
			}
			foreach ($rule['parameters'] as $pn => $data) 
			{
				if (isset($data['value'])) {$mParameters[$pn] = $data['value'];}
			}
			if (count($mParameters))
			{
				$parameters[$moduleNameKey] = $mParameters;
			}
			else
			{
				unset($parameters[$moduleNameKey]);
			}
			return $pathTemplate;
		}
		return null;
	}
	
	/**
	 * @param string $moduleName
	 * @param $lang $actionName
	 * @param website_persistentdocument_website $website
	 * @param string $lang
	 * @param array $parameters
	 * @return f_web_ParametrizedLink
	 */	
	public function getDefaultActionWebLink($moduleName, $actionName, $website, $lang, $parameters)
	{
		$pathPrefix = ($website->getLocalizebypath()) ?  '/' .  $lang . '/action/' : '/action/';
		$path = $pathPrefix . $moduleName . '/' . $actionName;
		$link = new f_web_ParametrizedLink($website->getProtocol(), $website->getDomain(), $path);
		if (count($parameters))
		{	
			//$parameters = $this->convertToModuleParameters($parameters, $moduleName);
			$link->setQueryParameters($parameters);
		}
		return $link;		
	}

	private function convertToModuleParameters($parameters, $moduleName, $parameterNames = array())
	{
		if (f_util_ArrayUtils::isEmpty($parameters)) {return array();} 
		$name = $moduleName . 'Param';
		$mParameters = isset($parameters[$name]) && is_array($parameters[$name]) ? $parameters[$name] : array();
		foreach ($parameters as $pn => $pval) 
		{
			if ($pn === $name) {continue;}
			if (count($parameterNames) && !in_array($pn, $parameterNames)) {continue;}
			$mParameters[$pn] = $pval;
		}
		$parameters[$name] = $mParameters;
		return $parameters;
	}
	
	/**
	 * @param f_persistentdocument_PersistentDocument $document
	 * @param website_persistentdocument_website $website
	 * @param string $lang
	 * @param string $actionName
	 * @return string
	 */
	public function getCustomPath($document, $website, $lang, $actionName = 'ViewDetail')
	{
		$websiteId = ($website->isNew()) ? 0 : $website->getId();
		return $this->getPersistentProvider()->getUrlRewriting($document->getId(), $lang, $websiteId, $actionName);
	}
	
	/**
	 * @param string $path
	 * @param f_persistentdocument_PersistentDocument $document
	 * @param website_persistentdocument_website $website
	 * @param string $lang
	 * @param string $moduleName
	 * @param string $actionName
	 * @param integer $origine
	 * @param string $oldPath
	 * @return string current rewrite path
	 */
	public function setCustomPath($path, $document, $website, $lang, $moduleName = null, $actionName = 'ViewDetail', $origine = 0)
	{
		$documentId = $document->getId();
		$oldPath = null;
		$websiteId = ($website === null || $website->isNew()) ? 0 : $website->getId();
		if ($moduleName === null)
		{
			$moduleName = $document->getPersistentModel()->getModulename();
		}		
		//rule_id, origine, modulename, actionname, document_id, website_lang, website_id, from_url, to_url, redirect_type
		$oldInfo = $this->getPersistentProvider()->getUrlRewritingDocument($documentId, $lang, $websiteId);
		foreach ($oldInfo as $row) 
		{
			if (intval($row['redirect_type']) === 200)
			{
				if ($row['origine'] == 0 && $origine != 0)
				{	
					return $row['from_url'];
				}
				else if ($row['from_url'] == $path)
				{
					return $path;
				}
				$oldPath = $row['from_url'];
			}
		}
		
		$tm = $this->getTransactionManager();
		try 
		{
			$tm->beginTransaction();
			$this->getPersistentProvider()->deleteUrlRewritingDocument($documentId, $lang, $websiteId);
			
			if ($path !== null)
			{
				$this->getPersistentProvider()->setUrlRewriting($documentId, $lang, $websiteId, $path, null, 200, $moduleName, $actionName, $origine);
							
				if ($oldPath !== null)
				{
					$this->getPersistentProvider()->setUrlRewriting($documentId, $lang, $websiteId,	$oldPath, $path, 301, $moduleName, $actionName, $origine);
				}
							
				foreach ($oldInfo as $row)
				{
					if ($path != $row['from_url'] && $oldPath != $row['from_url'])
					{
						$redirectType = intval($row['redirect_type']) === 200 ? 301 : intval($row['redirect_type']);
						$this->getPersistentProvider()->setUrlRewriting($documentId, $lang, $websiteId, 
							$row['from_url'], $path, $redirectType, $row['modulename'], $row['actionname'], $row['origine']);
					}
				}
			}	
			$tm->commit();
		}
		catch (Exception $e)
		{
			$path = null;
			$tm->rollBack($e);
		}
		return $path;
	}
	
	/**
	 * @param string $path
	 * @param f_persistentdocument_PersistentDocument $document
	 * @param website_persistentdocument_website $website
	 * @param string $lang
	 * @param string $moduleName
	 * @param string $actionName
	 * @param integer $origine
	 * @param string $oldPath
	 * @return string current rewrite path
	 */
	public function setCustomRedirectPath($path, $redirectType, $document, $website, $lang, $moduleName = null, $actionName = 'ViewDetail', $origine = 0)
	{
		$documentId = $document->getId();
		$websiteId = ($website === null || $website->isNew()) ? 0 : $website->getId();
		if ($moduleName === null) {$moduleName = $document->getPersistentModel()->getModulename();}
		if ($redirectType != 302) {$redirectType = 301;}	
		
		//rule_id, origine, modulename, actionname, document_id, website_lang, website_id, from_url, to_url, redirect_type
		$oldInfo = $this->getPersistentProvider()->getUrlRewritingDocument($documentId, $lang, $websiteId);
		$to_url = $this->getDocumentDefaultPath($document, $lang);
	
		foreach ($oldInfo as $row) 
		{
			if (intval($row['redirect_type']) === 200) 
			{
				$to_url =  $row['from_url'];
				break;
			}
		}
		if ($to_url == $path)
		{
			return $to_url;
		}
		
		$this->getTransactionManager()->beginTransaction();		
		$this->getPersistentProvider()->deleteUrlRewritingDocument($documentId, $lang, $websiteId);
		
		$this->getPersistentProvider()->setUrlRewriting($documentId, $lang, $websiteId, $path, $to_url, $redirectType, 
			$moduleName, $actionName, $origine);
									
		foreach ($oldInfo as $row)
		{
			if ($path != $row['from_url'])
			{
				$this->getPersistentProvider()->setUrlRewriting($documentId, $lang, $websiteId, $row['from_url'], $to_url, $redirectType, 
					$row['modulename'], $row['actionname'], $row['origine']);
			}
		}
		$this->getTransactionManager()->commit();
		return $to_url;
	}
	
	/**
	 * @param f_persistentdocument_PersistentDocument $document
	 */
	public function clearAllCustomPath($document)
	{
		if ($document instanceof f_persistentdocument_PersistentDocument)
		{
			$this->getTransactionManager()->beginTransaction();		
			$this->getPersistentProvider()->clearUrlRewriting($document->getId());
			$this->getTransactionManager()->commit();
		}
	}
	
	/**
	 * @param f_persistentdocument_PersistentDocument $document
	 * @param website_persistentdocument_website $website
	 * @param string $lang
	 * @param array $parameters
	 * @return string
	 */
	public function getDocumentRulePath($document, $website, $lang, $parameters)
	{
		$rewriteRules = $this->getRewriteRules();
		if (isset($rewriteRules['models'][$document->getDocumentModelName()]))
		{
			if ($document->isLocalized())
			{
				RequestContext::getInstance()->beginI18nWork($document->isLangAvailable($lang) ? $lang : $document->getLang());
			}
			
			$rule = $rewriteRules['models'][$document->getDocumentModelName()];
			$pathTemplate = str_replace('${lang}', $lang, $rule['lang'][$lang]);
			$matches = array();
			preg_match_all('/\$\{([a-zA-Z0-9]+)\}/', $pathTemplate, $matches, PREG_SET_ORDER);
			foreach ($matches as $match)
			{
				$pn = $match[1];
				if (isset($rule['parameters'][$pn]) && isset($rule['parameters'][$pn]['method']))
				{
					$getter = $rule['parameters'][$pn]['method'];
					$value = $document->{$getter}();
					if ($value === null) {$value = $pn;}
				}
				else
				{
					$value = $pn;
				}
				if (is_string($value)) {$value = $this->encodePathString($value);}
				$pathTemplate = str_replace($match[0], $value, $pathTemplate);
			}
			
			if ($document->isLocalized())
			{
				RequestContext::getInstance()->endI18nWork();
			}
			return $pathTemplate;
		}
		return null;
	}
	
	//----------------------------------------------------------------------------------------------------------
	
	/**
	 * @param string $urlToForward
	 * @param string $host
	 * @param change_Request $request
	 * @return array<$moduleName, $actionName>
	 */
	public function getActionToforward($urlToForward, $host, $request)
	{
		$urlToForward = $urlToForward[0] !== '/' ? '/' . $urlToForward : $urlToForward;
		if (Framework::isInfoEnabled())
		{
			Framework::info(__METHOD__ . '('. $urlToForward . ', '. $host . ')');
		}
		
		$moduleName = null; $actionName = null; $documentId = null; 
		$checkRedirect = true;
		try 
		{
			$path = $this->initCurrrentWebsite($host, $urlToForward);
			if (is_array($path))
			{
				return $path;
			}
			$website = website_WebsiteService::getInstance()->getCurrentWebsite();

			if ($path === '/')
			{
				//Home page
				$homePage = $website->getDocumentService()->getIndexPage($website, false);
				if ($homePage  !== null)
				{
					$request->setParameter('pageref', $homePage->getId());
					return array('website', 'Display');
				}
			}
			
			$lang = RequestContext::getInstance()->getLang();	
			RequestContext::getInstance()->setUILang($lang);
			
			$infos = $this->getPersistentProvider()->getUrlRewritingInfoByUrl($path, $website->getId(), $lang);
			if ($infos !== null)
			{
				if (intval($infos['redirect_type']) !== 200)
				{
					return $this->getRedirectAction($website, $lang, $infos['to_url'], intval($infos['redirect_type']), $request);
				}
				
				$moduleName = $infos['modulename'];
				$actionName = $infos['actionname'];
				if (intval($infos['origine']) === 0)
				{
					//Redirection saisie en backOffice pas 
					$checkRedirect = false;
				}
				
				if (intval($infos['document_id']) > 0)
				{
					$documentId = intval($infos['document_id']);
					$request->setModuleParameter($moduleName, 'cmpref', $documentId);
				}
				
				if (Framework::isInfoEnabled())
				{		
					Framework::info('Rewrite Document Rule ' .$moduleName . '/' . $actionName . ' cmpref: '. $documentId . ' ' .$checkRedirect);
				}
			}
			
			if ($moduleName === null)
			{
				//Default document redirection rule
				$pattern = '/^\/([a-z0-9]+)\/([a-zA-Z0-9_\-\.]+),([0-9]+)\.html$/';
				if (preg_match($pattern, $path, $matches))
				{
					$documentId = intval($matches[3]);
					$modelName = $this->getPersistentProvider()->getDocumentModelName($documentId);
					if ($modelName)
					{
						$document = $this->getPersistentProvider()->getDocumentInstance($documentId, $modelName, $lang);
						$defaultPath = $this->getDocumentDefaultPath($document, $lang);					
						if ($defaultPath !== $path)
						{
							if (Framework::isInfoEnabled())
							{
								Framework::info('Generic Document Rule not match path: ' .$defaultPath);
							}
							return $this->getRedirectAction($website, $lang, $defaultPath, 301, $request);
						}
						
						$moduleName = $matches[1];
						$actionName = 'ViewDetail';
						if (Framework::isInfoEnabled())
						{		
							Framework::info('Generic Document Rule ' .$moduleName . '/ViewDetail cmpref: ' . $documentId);
						}
						$request->setModuleParameter($moduleName, 'cmpref', $documentId);
					}
					else
					{
						if (Framework::isInfoEnabled())
						{
							Framework::info('Invalid Document Id: ' .$documentId);
						}
						$moduleName = 'website';
						$actionName = 'Error404';
						$documentId = null;
					}
				}
			}
			if ($moduleName === null)
			{
				//Default action redirection rule
				$pattern = '/^\/action\/([a-z0-9]+)\/([a-zA-Z0-9]+)$/';
				if (preg_match($pattern, $path, $matches))
				{
					$moduleName = $matches[1]; $actionName = $matches[2];
					if (Framework::isInfoEnabled())
					{		
						Framework::info('Generic Action Rule ' .$moduleName . '/' . $actionName);
					}
				}
			}
			
			if ($moduleName === null)
			{
				//All rewrited action rules				
				$this->getRewriteRules();
				$matches = array();
				foreach ($this->rewriteRules['actions'] as $moduleAction => $ruleData) 
				{
					if (preg_match($ruleData['regex'][$lang], $path, $matches))
					{
						$moduleName = $ruleData['module']; $actionName = $ruleData['action'];
						$matcheNames = array();
						preg_match_all('/\$\{([a-zA-Z0-9]+)\}/', $ruleData['lang'][$lang], $matcheNames, PREG_SET_ORDER);
						foreach ($matcheNames as $idx => $match) 
						{
							$paramName = $match[1];
							$paramValue = $matches[$idx + 1];
							$paramDatas = $ruleData['parameters'][$paramName];
							$this->setRequestParam($request, $moduleName, $paramName, $paramValue, $paramDatas['type']);
							if (isset($paramDatas['forwardTo']))
							{
								foreach (explode(',', $paramDatas['forwardTo']) as $extraName) 
								{
									if (preg_match('/^([a-z0-9A-z]+)Param\[([a-z0-9A-Z_]+)\]$/', $extraName, $extraMatch))
									{
										$this->setRequestParam($request, $extraMatch[1], $extraMatch[2], $paramValue, 'module');
									}
									else
									{
										$this->setRequestParam($request, $moduleName, $extraName, $paramValue, 'global');
									}
								}
							}
							
						}
						
						if (Framework::isInfoEnabled())
						{		
							Framework::info('Rewrite Action Rule ' .$moduleName . '/' . $actionName);
						}
						break;
					}
				}				
			}
			
			if ($checkRedirect)
			{
				if ($documentId !== null)
				{
					$document = DocumentHelper::getDocumentInstance($documentId);
					$parameters = $request->getModuleParameters($moduleName);
					$rewritePath = $document->getDocumentService()->generateRewritePath($this, $document, $website, $lang, $parameters);
					if ($rewritePath !== null && $rewritePath != $path)
					{
						$finalPath = $this->setCustomPath($rewritePath, $document, $website, $lang, $moduleName, $actionName, 1);
						if ($finalPath !== null && $finalPath !== $path)
						{
							return $this->getRedirectAction($website, $lang, $finalPath, 301, $request);
						}
					}	
				}
			}		
		} 
		catch (Exception $e) 
		{
			return $this->getUnavailableAction($e);	
		}
		
		if ($moduleName === null)
		{
			return array('website', 'Error404');
		}
		return array($moduleName, $actionName);
	}
	
	/**
	 * @param change_Request $request
	 * @param string $moduleName
	 * @param string $paramName
	 * @param string $paramValue
	 * @param string $type
	 */
	private function setRequestParam($request, $moduleName, $paramName, $paramValue, $type = 'all')
	{
		if ($type === 'out') {return;}
		if ($type !== 'global')
		{
			if (!$request->hasModuleParameter($moduleName, $paramName))
			{
				$request->setModuleParameter($moduleName, $paramName, $paramValue);
			}
		}
		if ($type !== 'module')
		{
			if (!$request->hasParameter($paramName))
			{
				$request->setParameter($paramName, $paramValue);
			}
		}
	}

	public function initCurrrentWebsite($host, $urlToForward = null)
	{
		$wsms = website_WebsiteService::getInstance();
		$websiteInfo  = $wsms->getWebsiteInfos($host);
		if ($websiteInfo === null)
		{
			throw new Exception('WEBSITE not found for host : ' . $host);
		}
		if ($websiteInfo['localizebypath'] && $urlToForward !== null)
		{
			$pattern = '/^\/('. implode('|', $websiteInfo['langs']) .')\//';
			$matches = null;
			if (!preg_match($pattern, $urlToForward, $matches))
			{
				$request = Change_Controller::getInstance()->getContext()->getRequest();
				$lang = $websiteInfo['langs'][0];
				$path = f_util_ArrayUtils::firstElement(explode('?', $urlToForward));
				$redirectType = 301;
				$website = website_persistentdocument_website::getInstanceById($websiteInfo['id']);
				return $this->getRedirectAction($website, $lang, $path, $redirectType, $request);	
			}
			$lang = $matches[1];
			$urlToForward = substr($urlToForward, strlen($lang) + 1);
		}
		else
		{
			$lang = $websiteInfo['langs'][0];
		}
		if (Framework::isInfoEnabled())
		{
			Framework::info(__METHOD__ . ', ' . $websiteInfo['id']. ', ' . $lang);
		}
		$rc = RequestContext::getInstance();
		$rc->setLang($lang);
		$website = website_persistentdocument_website::getInstanceById($websiteInfo['id']);
		if (!$website->isPublished() && $rc->getMode() === RequestContext::FRONTOFFICE_MODE)
		{
			throw new Exception('Website ' . $website->getLabel(). ' not published');
		}
		$wsms->setCurrentWebsite($website);
		return $urlToForward;
	}
	
	/**
	 * @param f_persistentdocument_PersistentDocument $document
	 * @param $website $website
	 * @param string $lang
	 * @return f_web_ParametrizedLink
	 */
	public function evaluateDocumentLink($document, $website, $lang)
	{
		$link = null;
		$rc = RequestContext::getInstance();
		$parameters = array();
		try 
		{
			$rc->beginI18nWork($lang);
			website_WebsiteService::getInstance()->setCurrentWebsite($website);
			$websiteId = $website->getId();
			$ds = $document->getDocumentService();
			$docWebsiteIds = $ds->getWebsiteIds($document);
			$model = $document->getPersistentModel();
			
			if ($model->hasURL() && ($docWebsiteIds === null || in_array($websiteId, $docWebsiteIds)))
			{
				$link = $ds->getWebLink($this, $document, $website, $lang, $parameters);
				if ($link === null)
				{
					$page = $ds->getDisplayPage($document);
					if ($page !== null)
					{	
						$lastRewritePath = null;
						$rules = $this->getPersistentProvider()->getUrlRewritingDocument($document->getId(), $lang, $websiteId);
						foreach ($rules as $ruleInfos) 
						{
							if ($ruleInfos['redirect_type'] == 200)
							{
								$lastRewritePath = $ruleInfos['from_url'];
								if ($ruleInfos['origine'] == 0)
								{
									$link = $this->getRewriteLink($website, $lang, $ruleInfos['from_url']);
								}
								break;
							}
						}
						
						if ($link === null)
						{
							$path = $ds->generateRewritePath($this, $document, $website, $lang, $parameters);
							if ($path !== null)
							{
								if ($path !== $lastRewritePath)
								{
									$path = $this->setCustomPath($path, $document, $website, $lang, null, 'ViewDetail', 1);
								}
							}
							
							if ($path === null)
							{
								$path = $this->getDocumentDefaultPath($document, $lang);
							}
							$link = $this->getRewriteLink($website, $lang, $path);
						}
					}
				}
			}
			$rc->endI18nWork();
		} 
		catch (Exception $e) 
		{
			Framework::exception($e);
			$rc->endI18nWork();
		}
		return $link;
	}
	
	/**
	 * @return string[]
	 * @throws Exception
	 */
	public function getModelNamesAllowURL()
	{
		$compiledFilePath = f_util_FileUtils::buildChangeBuildPath('allowedDocumentInfos.ser');
		if (!file_exists($compiledFilePath))
		{
			throw new Exception("File not found : $compiledFilePath. compile-documents needed");
		}
		$allowedInfos = unserialize(file_get_contents($compiledFilePath));
		return $allowedInfos['hasUrl'];
	}
	
	/**
	 * @return string[]
	 * @throws Exception
	 */
	public function getModelNamesUseRewriteURL()
	{
		$compiledFilePath = f_util_FileUtils::buildChangeBuildPath('allowedDocumentInfos.ser');
		if (!file_exists($compiledFilePath))
		{
			throw new Exception("File not found : $compiledFilePath. compile-documents needed");
		}
		$allowedInfos = unserialize(file_get_contents($compiledFilePath));
		return isset($allowedInfos['useRewriteUrl']) ? $allowedInfos['useRewriteUrl'] : array();
	}
	
	/**
	 * @param string[] $modelNames
	 * @param boolean $echoProgress
	 */
	public function refreshAllDocumentUrl($modelNames, $echoProgress = true)
	{
		$batchPath = 'modules/website/lib/bin/batchRefreshDocumentUrl.php';
		$modelNames = f_util_ArrayUtils::isEmpty($modelNames) ? $this->getModelNamesUseRewriteURL() : $modelNames;
		$websites = website_WebsiteService::getInstance()->getAll();
	
		foreach ($websites as $website) 
		{
			foreach ($modelNames as $modelName)
			{
				if ($echoProgress) {echo $website->getDomain() , ' ' ,  $modelName, ":";}			
				foreach ($website->getI18nInfo()->getLangs() as $lang) 
				{
					if ($echoProgress) {echo "\n", $lang , ':';}
					$offset = 0;
					do
					{
						$result = f_util_System::execScript($batchPath, array($website->getId(), $lang, $modelName, $offset));
						if (is_numeric($result))
						{
							$offset = intval($result);
							if ($echoProgress) {echo ' ', abs($offset);}
						}
						else
						{
							
							Framework::error(__METHOD__ . ' ' . $batchPath . ' unexpected result: "' . $result . '"');
							if ($echoProgress)  {echo $result;}
							$offset = -1;
						}
					}
					while ($offset >= 0);
				}
				if ($echoProgress)  {echo "\n";}
			}
		}
	}
	
	/**
	 * @param string $urlToForward
	 * @param string $domaine
	 * @param change_Request $request
	 * @return array<$moduleName, $actionName>
	 */
	private function getCompatibleActionToForward($urlToForward, $domaine, $request)
	{
		Framework::warn(__METHOD__ . " !!! $urlToForward, $domaine !!!");
		$request->setParameter('pagename', $urlToForward);
		return array('website', 'RewriteUrl');
	}
	
	/**
	 * @param Exception $e
	 * @return array<modulename, actionName>
	 */
	private function getUnavailableAction($e = null)
	{
		if ($e !== null)
		{
			Framework::exception($e);
		}
		return array('website', 'Unavailable');
	}
	
	/**
	 * @param website_persistentdocument_website $website
	 * @param string $lang
	 * @param string $path
	 * @param integer $redirectType
	 * @param change_Request $request
	 * @return array<modulename, actionName>
	 */
	private function getRedirectAction($website, $lang, $path, $redirectType, $request)
	{
		$uri = RequestContext::getInstance()->getPathURI();
		$queryParams = array();
		$queryParamsPos = strpos($uri, '?');
		if ($queryParamsPos)
		{
			parse_str(substr($uri, $queryParamsPos + 1), $queryParams);
		}
		$request->setParameter('location', $this->getRewriteLink($website, $lang, $path, $queryParams)->getUrl());
		$request->setParameter('redirectType', $redirectType);
		return array('website', 'Redirect');
	}
	
	//---------------------------------------------------------
	
	/**
	 * @param f_persistentdocument_PersistentDocument $document
	 * @return array
	 */
	public function getBoDocumentRewriteInfo($document)
	{
		$ds = $document->getDocumentService();
		$websiteIds = $ds->getWebsiteIds($document);
		if (is_array($websiteIds) && count($websiteIds) === 0)
		{
			throw new BaseException('Document has no defined Website', 'm.website.errors.no-defined-website');
		}
		
		$documentId = $document->getId();
		$result = array('documentId' => $documentId,
						'vo' => $document->getLang(),
						'isLocalized' => $document->isLocalized(),
						'langs' => array_values($document->getI18nInfo()->getLangs()),
						'definedrules' => array());
		
		//<nb_rules, website_id, website_lang
		$definedrules = $this->getPersistentProvider()->getUrlRewritingDocumentWebsiteInfo($document->getId());
		foreach ($definedrules as $row) 
		{
			$result['definedrules'][$row['website_id'] . '/' . $row['website_lang']]['nb_rules'] = intval($row['nb_rules']);
		}
		
		$rules = array();
		$redirections = array('0/' .  $document->getLang() => array());
		if (is_array($websiteIds))
		{
			$websites = DocumentHelper::getDocumentArrayFromIdArray($websiteIds);
		}
		else
		{
			$websites = website_WebsiteService::getInstance()->getAll();
		}
		
		$rc = RequestContext::getInstance();
		foreach ($websites as $website) 
		{
			foreach ($website->getI18nInfo()->getLangs() as $lang) 
			{
				$rc->setLang($lang);
				$key = $website->getId(). '/' . $lang;
				if (!isset($result['definedrules'][$key]))
				{
					$result['definedrules'][$key]['nb_rules'] = 0;
				}
				$result['definedrules'][$key]['base'] = $this->getRewriteLink($website, $lang, '')->getUrl();
				if ($lang == $document->getLang())
				{
					$result['definedrules'][$key]['vo'] = true;
				}
				$genericPath =  $this->getDocumentDefaultPath($document, $lang);
				$defaultPath = $ds->generateRewritePath($this, $document, $website, $lang, array());
				if ($defaultPath === null) {$defaultPath = $genericPath;}
				$sitemap =  ($document->hasMeta('sitemap_' . $key)) ? $document->getMeta('sitemap_' . $key) : '';	
				$rules[$key] = array('from_url' => $defaultPath, 'defaultpath' => $defaultPath, 'sitemap' => $sitemap);
				
				if ($result['definedrules'][$key]['nb_rules'] > 0)
				{
					//rule_id, origine, modulename, actionname, document_id, website_lang, website_id, from_url, to_url, redirect_type
					$datas = $this->getPersistentProvider()->getUrlRewritingDocument($documentId, $lang, $website->getId());
					foreach ($datas as $ruleData) 
					{
						if ($ruleData['redirect_type'] == 200)
						{
							$ruleData['defaultpath'] = $defaultPath;
							$ruleData['sitemap'] = $sitemap;
							$rules[$key] = $ruleData;
						}
						else
						{
							$ruleData['url'] = $this->getRewriteLink($website, $lang, $ruleData['from_url'])->getUrl();
							$redirections[$key][] = $ruleData;
						}
					}	
				}
			}
		}
		$result['rules'] = $rules;
		$result['redirections'] = $redirections;
		uasort ($result['definedrules'], array($this, 'boSortDefinedRules'));
		return $result;
	}
	
	private function boSortDefinedRules($a, $b)
	{
		if (isset($a['vo']) && isset($a['$b']))
		{
			if ($a['nb_rules'] == $b['nb_rules']) 
			{
				return 0;
			}
		}
		elseif (isset($a['vo']))
		{
			return -1;
		}
		elseif (isset($b['vo']))
		{
			return 1;
		}
		elseif ($a['nb_rules'] == $b['nb_rules'])
		{
			return 0;
		}
		return ($a['nb_rules'] > $b['nb_rules']) ? -1 : 1;
	}

	/**
	 * @param f_persistentdocument_PersistentDocument $document
	 * @param array $data
	 */
	public function setBoDocumentRewriteInfo($document, $data)
	{
		$documentId = $document->getId();
		$moduleName = $document->getPersistentModel()->getModulename();
		$actionName = 'ViewDetail';
		$saveMeta = false;
		try 
		{
			$this->getTransactionManager()->beginTransaction();
			$definedrules = array();
			foreach ($this->getPersistentProvider()->getUrlRewritingDocumentWebsiteInfo($documentId) as $row) 
			{
				$definedrules[$row['website_id'] . '/' . $row['website_lang']] = intval($row['nb_rules']);
			}			
			
			foreach ($data['definedrules'] as $key => $rawData) 
			{
				list($websiteId, $lang) = explode('/', $key);
				$website = website_persistentdocument_website::getInstanceById($websiteId);
				
				$defaultPath =  $this->getDocumentDefaultPath($document, $lang);
				$default = $document->getDocumentService()->generateRewritePath($this, $document, $website, $lang, array());
				if ($default === null) {$default = $defaultPath;}
				
				$oldRule = array('from_url' => $default, 'modulename' => $moduleName, 'actionname' => $actionName, 'origine' => 1);				
				$oldRedirections = array();
				
				
				if (isset($definedrules[$key]))
				{
					foreach ($this->getPersistentProvider()->getUrlRewritingDocument($documentId, $lang, $websiteId) as $ruleData) 
					{
						if ($ruleData['redirect_type'] == 200)
						{
							$oldRule = $ruleData;
							if ($oldRule['from_url'] == $default)
							{
								$oldRule['origine'] = 1;
							}
						}
						else
						{
							$oldRedirections[$ruleData['from_url']] = $ruleData;
						}
					}
					$this->getPersistentProvider()->deleteUrlRewritingDocument($documentId, $lang, $websiteId);	
				}
								
				$rulePath =  $data['rules'][$key]['from_url'];
				if ($rulePath !== $defaultPath)
				{
					if ($oldRule['from_url'] == $rulePath)
					{

						$this->getPersistentProvider()->setUrlRewriting($documentId, $lang, $websiteId, $rulePath, null, 200, 
							$oldRule['modulename'], $oldRule['actionname'], intval($oldRule['origine']));
					}
					else
					{			
						$this->getPersistentProvider()->setUrlRewriting($documentId, $lang, $websiteId, $rulePath, null, 200, 
							$moduleName, $actionName, 0);
					}
				}
				
				$sitemap = isset($data['rules'][$key]['sitemap']) ? $data['rules'][$key]['sitemap'] : '';
				$oldsitemap =  ($document->hasMeta('sitemap_' . $key)) ? $document->getMeta('sitemap_' . $key) : '';
				if ($sitemap != $oldsitemap)
				{
					$document->setMeta('sitemap_' . $key, $sitemap);
					$saveMeta = true;
				}	
				
				if (isset($data['redirections'][$key]))
				{
					foreach ($data['redirections'][$key] as $ruleData) 
					{
						$redirectionPath = $ruleData['from_url'];
						$redirectType = $ruleData['redirect_type'];
						if (isset($oldRedirections[$redirectionPath]))
						{
							$oldRule = $oldRedirections[$redirectionPath];
							$this->getPersistentProvider()->setUrlRewriting($documentId, $lang, $websiteId, $redirectionPath, $rulePath, $redirectType, 
								$oldRule['modulename'], $oldRule['actionname'], $oldRule['origine']);
						}
						else
						{
							$this->getPersistentProvider()->setUrlRewriting($documentId, $lang, $websiteId, $redirectionPath, $rulePath, $redirectType, 
								$moduleName, $actionName, 0);
						}
					}
				}
			}
			if ($saveMeta)
			{
				$document->saveMeta();
			}
			$this->getTransactionManager()->commit();
		} 
		catch (Exception $e) 
		{
			$this->getTransactionManager()->rollBack($e);
		}
	}
	
	//-----------------------------------------------------------------------------------------

	protected $rewriteRules;
	
	/**
	 * @param true $override
	 * @return integer
	 */
	public function buildRules($override = false)
	{
		$rules = $this->getXmlRules();		
		
		try 
		{
			foreach ($rules['models'] as $key => $ruleData) 
			{
				website_RewriteruleService::getInstance()->addDefinition($ruleData, $override);
			}
			
			foreach (website_RewriteruleService::getInstance()->getPublishedDocumentRules() as $ruleDoc) 
			{
				if ($ruleDoc instanceof website_persistentdocument_rewriterule)
				{
					$ruleData = $ruleDoc->getRuleData();
					$rules['models'][$ruleDoc->getModelName()] = $ruleData;		
				}
			} 			
		} 
		catch (Exception $e) 
		{
			Framework::exception($e);
		}

		$path = f_util_FileUtils::buildChangeBuildPath('rewritingRules.php');
		$content = '<?php
	$this->rewriteRules = ' . var_export($rules, true) . ';';
		f_util_FileUtils::writeAndCreateContainer($path, $content, f_util_FileUtils::OVERRIDE);
		
		return count($rules['models']) + count($rules['actions']);
	}
	
	/**
	 * 
	 * @see website_BaseRewritingService::getRules()
	 */
	protected function getRewriteRules()
	{
		if ($this->rewriteRules === null)
		{
			$path = f_util_FileUtils::buildChangeBuildPath('rewritingRules.php');
			if (is_readable($path))
			{
				include_once $path;
			}
			else
			{
				$this->rewriteRules = array('models' => array(), 'actions' => array());
			}
		}
		return $this->rewriteRules;
	}
	
	/**
	 * @return array
	 */
	private function getXmlRules()
	{
		$rules = array('models' => array(), 'actions' => array());
		$configs = $this->getXMLConfigFiles();
		foreach ($configs as $packageName => $content)
		{
			$this->parseXMLConfig($packageName, $content, $rules);
		}
		
		foreach ($rules['models'] as $key => $ruleData) 
		{
			$rules['models'][$key] = $this->cleanRuleParameters($this->cleanRuleTemplate($ruleData));
		}
		
		foreach ($rules['actions'] as $key => $ruleData) 
		{
			$rules['actions'][$key] =  $this->cleanRuleParameters($this->cleanRuleTemplate($ruleData));
		}
		return $rules;
	}
	
	/**
	 * @param string $packageName
	 * @param string $content
	 * @param array $rules
	 */
	private function parseXMLConfig($packageName, $content, &$rules)
	{
		$domDocument = f_util_DOMUtils::fromString($content);
		if (!$domDocument->documentElement)
		{
			echo 'Invalid urlrewriting file in package: ' . $packageName . "\n";
			return;
		}
		
		// Parse the rules
		foreach ($domDocument->getElementsByTagName('rule') as $rule)
		{
			// Get and sanitize template information
			$template = trim($rule->getElementsByTagName('template')->item(0)->textContent);
			if (strlen($template) == 0) {continue;}
			$lang = ($rule->hasAttribute('lang')) ? $rule->getAttribute('lang') : 'DEFAULT';
			if ($template[0] != '/') {$template = '/' . $template;}
			
			$parameters = array();
			$domParameters = $rule->getElementsByTagName('parameters')->item(0);
			if ($domParameters)
			{
				foreach ($domParameters->childNodes as $domParameter)
				{
					if ($domParameter->nodeType !== XML_ELEMENT_NODE) {continue;}
					$paramname = $domParameter->getAttribute('name');
					if (empty($paramname)) {continue;}
					
					if ($domParameter->tagName == 'globalParameter')
					{
						$parameterData = array('type' => 'global');
					} 
					else if ($domParameter->tagName == 'moduleParameter')
					{
						$parameterData = array('type' => 'module');
					}
					else if ($domParameter->tagName == 'parameter')
					{
						$parameterData = array('type' => 'all');
					}
					else
					{
						continue;
					}
					foreach ($domParameter->attributes as $domAttr) 
					{
						$parameterData[$domAttr->name] = $domAttr->value;
					}
					$parameters[$paramname] = $parameterData;
				}
			}
			
			if ($rule->hasAttribute('documentModel'))
			{
				$documentModel = $rule->getAttribute('documentModel');
				$action = 'ViewDetail';
				list(, $module) =  explode('_', $packageName);
				if (strpos($documentModel, '/') === false)
				{
					$documentName = $documentModel;
					$documentModel = $packageName.'/'.$documentModel;
				}
				else
				{
					list(, $documentName) = explode('/', $documentModel);
				}
				if ($rule->hasAttribute('module')) {$module = $rule->getAttribute('module');}
				$models = ModuleService::getInstance()->getDefinedDocumentNames($module);
				if (!in_array($documentName, $models))
				{
					echo "Invalid model <$documentModel> Ignore rule: " . $domDocument->saveXML($rule);
					continue;
				}
				
				if (!isset($rules['models'][$documentModel]))
				{
					$rules['models'][$documentModel] = array('module' => $module, 'action' => $action, 'model' => $documentModel, 'parameters' => array());
				}
				$rules['models'][$documentModel]['lang'][$lang] = $template;
				$rules['models'][$documentModel]['parameters'] = array_merge($rules['models'][$documentModel]['parameters'], $parameters);
			}
			else if ($rule->hasAttribute('redirection'))
			{
				$moduleAction = $rule->getAttribute('redirection');
				list($module, $action) = explode('/', $moduleAction);
				if (!isset($rules['actions'][$moduleAction]))
				{
					$rules['actions'][$moduleAction] = array('module' => $module, 'action' => $action, 'parameters' => array());
				}
				$rules['actions'][$moduleAction]['lang'][$lang] = $template;
				$rules['actions'][$moduleAction]['parameters'] = array_merge($rules['actions'][$moduleAction]['parameters'], $parameters);
			}
			else if ($rule->hasAttribute('pageTag'))
			{
				list(, $module) =  explode('_', $packageName);
				$action = 'ViewTag';
				$moduleAction = $module . '/' . $action;
				if (!isset($rules['actions'][$moduleAction]))
				{
					$rules['actions'][$moduleAction] = array('module' => $module, 'action' => $action, 'parameters' => array());
				}
				$rules['actions'][$moduleAction]['lang'][$lang] = $template;
				$rules['actions'][$moduleAction]['match'][$lang] = $template;
				$parameters['tagName'] = array('type' => 'all', 'name' => 'tagName', 'value' => $rule->getAttribute('pageTag'));
				$rules['actions'][$moduleAction]['parameters'] = array_merge($rules['actions'][$moduleAction]['parameters'], $parameters);
			}
		}
	}
	
	private function cleanRuleTemplate($ruleData)
	{
		$langs = RequestContext::getInstance()->getSupportedLanguages();
		$pattern = '/\$([a-zA-Z0-9]+)/';
		$defTemplate = null;
		foreach ($ruleData['lang'] as $lang => $template) 
		{
			if (isset($ruleData['model']) && strpos($template, '.html') === false && $template[strlen($template) -1] !== '/')
			{
				$template .= '.html';
			}
			$matches = array();
			if (preg_match_all($pattern, $template, $matches, PREG_SET_ORDER))
			{
				foreach ($matches as $match) 
				{
					if (!isset($ruleData['parameters'][$match[1]]))
					{
						$ruleData['parameters'][$match[1]] = array('type' => 'out');
					}
					$template = str_replace($match[0], '${' . $match[1] . '}', $template);
				}
			}
			
			$ruleData['lang'][$lang] = $template;
			if ($lang !== 'DEFAULT' && !isset($ruleData['model']))
			{
				$ruleData['regex'][$lang] = $this->buildRegEx($template);
			}
				
			if ($defTemplate === null || $lang === 'DEFAULT') {$defTemplate = $template;}
		}
		
		unset($ruleData['lang']['DEFAULT']);
		foreach ($langs as $lang) 
		{
			if (!isset($ruleData['lang'][$lang]))
			{
				$ruleData['lang'][$lang] = $defTemplate;
				if (!isset($ruleData['model']))
				{
					$ruleData['regex'][$lang] = $this->buildRegEx($defTemplate);
				}
			}
		}
		return $ruleData;
	}
	
	private function buildRegEx($template)
	{
		preg_match_all('/\$\{([a-zA-Z0-9]+)\}/', $template, $matches, PREG_SET_ORDER);
		foreach ($matches as $match)
		{
			$template = str_replace($match[0], '-STRING-', $template);
		}
		$search = array ('.', '(', ')', '{', '}', '[', ']', '-STRING-' );
		$replace = array ('\\.', '\(', '\)', '\{', '\}', '\[', '\]', '([a-zA-Z0-9_\-]+)' );
		return '"^' . str_replace($search, $replace, $template) . '$"';
		
	}
	
	private function cleanRuleParameters($ruleData)
	{
		$document = null;
		if (isset($ruleData['model']))
		{
			$document = f_persistentdocument_DocumentService::getInstanceByDocumentModelName($ruleData['model'])->getNewDocumentInstance();
		}
		$parameters = $ruleData['parameters'];
		foreach ($parameters as $name => $data) 
		{
			unset($ruleData['parameters'][$name]['format']);
			if ($document !== null)
			{
				$method = isset($data['method']) ? $data['method'] : 'get' . ucfirst($name);
				if (f_util_ClassUtils::methodExists($document, $method))
				{
					$ruleData['parameters'][$name]['method'] = $method;
				}
				else
				{
					Framework::warn(__METHOD__ . ' Invalid parameter method '. $ruleData['model'] .'->' . $method);
					unset($ruleData['parameters'][$name]);
				}
			}
		}
		return $ruleData;
	}
	
	private function getXMLConfigFiles()
	{
		$fileContent = array();
		$ms =  ModuleService::getInstance();
		foreach ($ms->getPackageNames() as $package)
		{
			$filePath = change_FileResolver::getNewInstance()->getPath('modules', $ms->getShortModuleName($package), 'config', 'urlrewriting.xml');
			if ($filePath)
			{
				$fileContent[$package] = f_util_FileUtils::read($filePath);
			}
		}
		return $fileContent;		
	}
}