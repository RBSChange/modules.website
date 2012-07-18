<?php
class website_BlockSwitchlanguageAction extends website_BlockAction
{
	/**
	 * @var integer
	 */
	private $detailId = null;
	
	/**
	 * @return string[string]
	 */ 
	public function getCacheDependencies()
	{
		if ($this->getDetailId())
		{
			return array($this->getDetailId());
		}
		return null;
	}
	
	/**
	 * @param website_BlockActionRequest $request
	 * @return array<mixed>
	 */	
	public function getCacheKeyParameters($request)
	{
		return array("detailId" => $this->getDetailId());
	}
	
	/**
	 * @return integer
	 */
	private function getDetailId()
	{
		if ($this->detailId === null)
		{
			$globalRequest = Controller::getInstance()->getContext()->getRequest();
			if ($globalRequest->hasParameter('detail_cmpref'))
			{
				$this->detailId = intval($globalRequest->getParameter('detail_cmpref'));
			}
			else 
			{
				$this->detailId = 0;
			}
		}
		return $this->detailId;
	}

	/**
	 * @param website_BlockActionRequest $request
	 * @param website_BlockActionResponse $response
	 * @return String
	 */
	public function execute($request, $response)
	{
		$viewall = $this->getConfiguration()->getViewall();
		$request->setAttribute('viewall', $viewall);
		$showflag = $this->getConfiguration()->getShowflag();
		
		$rc = RequestContext::getInstance();
		$currentLang = $rc->getLang();
		$page = $this->getPage()->getPersistentPage();
		
		$hasLink = false;
		$detailId = $this->getDetailId($request);
		$detailDoc = null;
		if (intval($detailId) > 0)
		{
			try 
			{
				$detailDoc = DocumentHelper::getDocumentInstance($detailId);
			}
			catch (Exception $e)
			{
				Framework::warn($e->getMessage());
			}
		}
		$parameters = $this->getCleanGlobalParameters(Controller::getInstance()->getContext()->getRequest()->getParameters(), $detailDoc);
		$website = website_WebsiteModuleService::getInstance()->getCurrentWebsite();
		$homePage = $website->getIndexPage();
		$generateLinks = Controller::getInstance()->getContext()->getRequest()->getMethod() === Request::GET;
		$switchArray = array();
		$otherLangsInfos = array();
		$currentLangInfos = null;
		foreach ($rc->getSupportedLanguages() as $lang)
		{
			$rc->beginI18nWork($lang);
			$isPageLink = ($generateLinks && $page->isContextLangAvailable() && $page->isPublished());
			if ($detailDoc && $isPageLink)
			{
				$isPageLink = $detailDoc->isContextLangAvailable() && $detailDoc->isPublished();
			}
			
			if ($website->isPublished() && ($isPageLink || ($viewall && $homePage->isPublished())))
			{
				$langInfos = array();
				$langInfos = array();
				$langInfos['label'] = strtoupper($lang);
				$langInfos['title'] = $this->getLangLabel($lang);
				
				if (!empty($showflag))
				{
					$langInfos['flagicon'] = MediaHelper::getIcon($this->getFlagIcon($lang), $showflag);
				}
				
				if ($lang != $currentLang)
				{
					$hasLink = true;
					if ($isPageLink)
					{
						$pageUrl = LinkHelper::getDocumentUrl($detailDoc ? $detailDoc : $page, $lang, $parameters);
						$langInfos['url'] = $pageUrl;
						$this->getPage()->addLink("alternate", "text/html", $pageUrl, LocaleService::getInstance()->transFO("m.website.frontoffice.this-page-in-mylang", array(), null, $lang), $lang);
					}
					else
					{
						$langInfos['url'] = LinkHelper::getDocumentUrl($homePage, $lang);
					}
					$otherLangsInfos[$lang] = $langInfos; 
				}
				else
				{
					$currentLangInfos = $langInfos;
				}
				$switchArray[$lang] = $langInfos;
			}
			$rc->endI18nWork();
		}
		if (!$hasLink) 
		{
			return website_BlockView::NONE;
		}
		
		$request->setAttribute('switchArray', $switchArray);
		$request->setAttribute('currentLangInfos', $currentLangInfos);
		$request->setAttribute('otherLangsInfos', $otherLangsInfos);
		return $this->getConfiguration()->getDisplayMode();
	}
	
	/**
	 * @param string $lang
	 */
	private function getLangLabel($lang)
	{
		/* @deprected (will be removed in 4.0) use the locale instead */
		if (Framework::hasConfiguration('languages/' . $lang . '/label'))
		{
			return Framework::getConfiguration('languages/' . $lang . '/label');
		}
		
		$key = 'm.website.frontoffice.version-in-lang';
		$text = LocaleService::getInstance()->formatKey($lang, $key, array('ucf'));
		if ($key != $text)
		{
			return $text;
		}
		return strtoupper($lang);
	}
	
	/**
	 * @param string $lang
	 */	
	private function getFlagIcon($lang)
	{
		/* @deprected (will be removed in 4.0) override the icons instead */
		if (Framework::hasConfiguration('languages/' . $lang . '/flag'))
		{
			return Framework::getConfiguration('languages/' . $lang . '/flag');
		}
		
		return 'flags/' . $lang;
	}
	
	/**
	 * @param array $parameters
	 * @param f_persistentdocument_PersistentDocument $detailDoc
	 */
	private function getCleanGlobalParameters($parameters, $detailDoc)
	{
		unset($parameters[K::LANG_ACCESSOR]);
		unset($parameters[K::PAGE_REF_ACCESSOR]);
		unset($parameters[K::COMPONENT_ID_ACCESSOR]);
		unset($parameters[K::URL_REWRITE_PAGE_NAME_ACCESSOR]);
		unset($parameters['websiteParam']);
		unset($parameters['module']);
		unset($parameters['action']);
		if ($detailDoc)
		{
			unset($parameters[$parameters['wemod'].'Param']);
			unset($parameters['wemod']);
		}
		return $parameters;
	}
}
