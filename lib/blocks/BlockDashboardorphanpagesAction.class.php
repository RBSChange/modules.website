<?php
class website_BlockDashboardorphanpagesAction extends  dashboard_BlockDashboardAction
{	
	/**
	 * @return string
	 */
	public function getTitle()
	{
		if ($this->hasWebsiteRestriction())
		{
			$websiteLabel = "- " . DocumentHelper::getDocumentInstance($this->getWebsiteId())->getLabel();
		}
		else
		{
			$websiteLabel = "";
		}
		return f_Locale::translateUI('&modules.website.bo.dashboard.OrphanpagesWithCount;', array('websiteLabel' => $websiteLabel, 'count' => $this->getOrphanPageCount()));
	}

	/**
	 * @param f_mvc_Request $request
	 * @param boolean $forEdition
	 */
	protected function setRequestContent($request, $forEdition)
	{
		if ($forEdition)
		{
			return;
		}
		
		if ($this->hasWebsiteRestriction())
		{
			$orphanPages = $this->getPageService()->getOrphanPagesForWebsiteId($this->getWebsiteId());
		}
		else
		{
			$orphanPages = $this->getPageService()->getOrphanPages();
		}
		
		if (count($orphanPages) > 0)
		{
			$orphanAttr = array();
			foreach ($orphanPages as $page)
			{
				$link = LinkHelper::getUIActionLink('website', 'BoDisplay')
					->setQueryParameter('cmpref', $page->getId())
					->setQueryParameter('lang', $page->getlang())
					->getUrl();
				
				$attr = array(
					'modificationDate' => date_Formatter::toDefaultDateTimeBO($page->getUIModificationdate()),
					'id' => $page->getId(), 
					'label' => $page->getLabelAsHtml(), 
					'thread' => f_util_HtmlUtils::textToHtml($page->getDocumentService()->getPathOf($page)), 
					'locate' => "locateDocumentInModule(" . $page->getId() . ", 'website');", 
					'link' => "window.open('$link', 'PreviewWindow', 'menubar=yes, location=yes, toolbar=yes, resizable=yes, scrollbars=yes, status=yes');"
				);
				$orphanAttr[] = $attr;
			
			}
			$request->setAttribute('orphanPages', $orphanAttr);
		}
	}
	
	/**
	 * @return website_PageService
	 */
	public function getPageService()
	{
		return website_PageService::getInstance();
	}
	
	private $orphanPageCount;
	
	private $websiteRestriction;
	
	private $websiteId;
	
	private function getWebsiteId()
	{
		if ($this->websiteId === null)
		{
			$website = $this->getConfiguration()->getWebsite();
			$this->websiteId = ($website !== null) ? $website->getId() : 0;
		}
		return $this->websiteId;
	}
	
	private function hasWebsiteRestriction()
	{
		return ($this->getConfiguration()->getWebsite() !== null);
	}
	
	/**
	 * @return unknown
	 */
	protected function getOrphanPageCount()
	{
		if ($this->orphanPageCount === null)
		{
			if ($this->hasWebsiteRestriction())
			{
				$this->orphanPageCount = $this->getPageService()->getOrphanPagesCountForWebsiteId($this->getWebsiteId());
			}
			else
			{
				$this->orphanPageCount = $this->getPageService()->getOrphanPagesCount();
			}
		}
		return $this->orphanPageCount;
	}
}