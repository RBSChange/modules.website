<?php

class website_BlockSwitchlanguageAction extends website_BlockAction
{
	private $detailId = null;
	
	/**
	 * @see f_mvc_Action::getCacheDependencies()
	 *
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
	
	private function getDetailId()
	{
		if ($this->detailId === null)
		{
			$params = change_Controller::getInstance()->getContext()->getRequest()->getParameters();
			
			if (isset($params['wemod']) 
				&& isset($params[$params['wemod'].'Param']) 
				&& is_array($params[$params['wemod'].'Param']) 
				&& isset($params[$params['wemod'].'Param']['cmpref']))
			{
				$this->detailId = intval($params[$params['wemod'].'Param']['cmpref']);
			}
			else 
			{
				$this->detailId = 0;
			}
		}
		return $this->detailId;
	}

	/**
	 * @see website_BlockAction::execute()
	 *
	 * @param website_BlockActionRequest $request
	 * @param website_BlockActionResponse $response
	 * @return string
	 */
	function execute($request, $response)
	{
		$viewall = $this->getConfiguration()->getViewall();
		$request->setAttribute('viewall', $viewall);
		$showflag = $this->getConfiguration()->getShowflag();
		
		$rc = RequestContext::getInstance();
		$currentLang = $rc->getLang();
		$page = $this->getContext()->getPersistentPage();
		
		
		$switchArray = array();
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
		
		$parameters = $this->getCleanGlobalParameters(change_Controller::getInstance()->getContext()->getRequest()->getParameters(), $detailDoc);
		$website = website_WebsiteService::getInstance()->getCurrentWebsite();
		$homePage = $website->getIndexPage();
		
		foreach ($rc->getSupportedLanguages() as $lang)
		{
			$rc->beginI18nWork($lang);
			$isPageLink = ($page->isContextLangAvailable() && $page->isPublished());
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
						$pageUrl = LinkHelper::getDocumentUrl($detailDoc ? $detailDoc : $page, $lang, $parameters);
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
	
	private function getLangLabel($lang)
	{
		$key = 'm.website.frontoffice.version-in-lang';
		$text = LocaleService::getInstance()->formatKey($lang, $key, array('ucf'));
		if ($key != $text)
		{
			return $text;
		}
		return strtoupper($lang);
	}
	
	private function getFlagIcon($lang)
	{
		return 'flags/' . $lang;
	}
	
	/**
	 * @param array $parameters
	 * @param f_persistentdocument_PersistentDocument $detailDoc
	 */
	private function getCleanGlobalParameters($parameters, $detailDoc)
	{
		unset($parameters['lang']);
		unset($parameters['pageref']);
		unset($parameters[change_Request::DOCUMENT_ID]);
		unset($parameters['pagename']);
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
