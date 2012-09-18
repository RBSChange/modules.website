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
		$p['detailId'] = $detailId;
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
	 * @return string
	 */
	public function execute($request, $response)
	{
		$viewall = $this->getConfiguration()->getViewall();
		$request->setAttribute('viewall', $viewall);
		$showflag = $this->getConfiguration()->getShowflag();
		
		$rc = RequestContext::getInstance();
		$currentLang = $rc->getLang();
		$page = $this->getContext()->getPersistentPage();
		
		
		$switchArray = array();
		$hasLink = false;
		$detailId = $this->getDetailId();
		$detailDoc =  DocumentHelper::getDocumentInstanceIfExists($detailId);
		$website = website_WebsiteService::getInstance()->getCurrentWebsite();
		$homePage = $website->getIndexPage();
		$generateLinks = change_Controller::getInstance()->getContext()->getRequest()->getMethod() === change_Request::GET;
		
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
				$switchArray[$lang] = array();
				$switchArray[$lang]['label'] = strtoupper($lang);
				$switchArray[$lang]['title'] = $this->getLangLabel($lang);
				
				if (!empty($showflag))
				{
					$switchArray[$lang]['flagicon'] = MediaHelper::getIcon($this->getFlagIcon($lang), $showflag);
				}
				
				if ($lang != $currentLang)
				{
					$hasLink = true;
					if ($isPageLink)
					{
						$pageUrl = LinkHelper::getDocumentUrlForWebsite($detailDoc ? $detailDoc : $page, $website, $lang, $this->getCleanGlobalParameters($detailId));
						$switchArray[$lang]['url'] = $pageUrl;
						$this->getContext()->addLink("alternate", "text/html", $pageUrl, LocaleService::getInstance()->trans("m.website.frontoffice.this-page-in-mylang", array(), null, $lang), $lang);
					}
					else
					{
						$switchArray[$lang]['url'] = LinkHelper::getDocumentUrl($homePage, $lang);
					}
				}
			}	
			$rc->endI18nWork();
		}
		if (!$hasLink) 
		{
			return website_BlockView::NONE;
		}
		
		$request->setAttribute('switchArray', $switchArray);
		return website_BlockView::SUCCESS;
	}
	
	/**
	 * @param string $lang
	 * @return string
	 */
	protected function getLangLabel($lang)
	{
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
	 * @return string
	 */
	protected function getFlagIcon($lang)
	{
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