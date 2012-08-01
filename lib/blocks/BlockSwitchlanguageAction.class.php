<?php
class website_BlockSwitchlanguageAction extends website_BlockAction
{
	/**
	 * @var integer
	 */
	protected $detailId = null;
	
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
		$detailId = $this->getDetailId();
		$p = $this->getCleanGlobalParameters($detailId);
		$p['detailId']=  $detailId;
		return $p;
	}
	
	/**
	 * @return integer
	 */
	protected function getDetailId()
	{
		if ($this->detailId === null)
		{
			$this->detailId = intval($this->getContext()->getDetailDocumentId());
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
		$detailId = $this->getDetailId();
		$detailDoc =  DocumentHelper::getDocumentInstanceIfExists($detailId);
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
						$pageUrl = LinkHelper::getDocumentUrlForWebsite($detailDoc ? $detailDoc : $page, $website, $lang, $this->getCleanGlobalParameters($detailId));
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
	protected function getLangLabel($lang)
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
	protected function getFlagIcon($lang)
	{
		/* @deprected (will be removed in 4.0) override the icons instead */
		if (Framework::hasConfiguration('languages/' . $lang . '/flag'))
		{
			return Framework::getConfiguration('languages/' . $lang . '/flag');
		}
		
		return 'flags/' . $lang;
	}
	
	/**
	 * @param integer $detailId
	 * @return array
	 */
	protected function getCleanGlobalParameters($detailId)
	{
		return array();
	}
}
