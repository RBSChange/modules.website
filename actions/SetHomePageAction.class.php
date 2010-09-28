<?php
class website_SetHomePageAction extends f_action_BaseJSONAction
{
	/**
	 * @param Context $context
	 * @param Request $request
	 */
	public function _execute($context, $request)
	{
		$page = $this->getDocumentInstanceFromRequest($request);
		try
		{
		    website_WebsiteModuleService::getInstance()->setHomePage($page);
		    $this->logAction($page);
		}
		catch (Exception  $e)
		{
			Framework::exception($e);
			return $this->sendJSONError(f_Locale::translateUI('&modules.website.bo.general.set-home-page-error;'));
		}
		
		return $this->sendJSON(array('cmpref' => $page->getId(), 'documentversion' => $page->getDocumentversion()));
	}

	protected function getSecureNodeIds()
	{
		$ids = parent::getSecureNodeIds();
		$page = $this->getDocumentInstanceFromRequest($this->getContext()->getRequest());
		if ($page !== null)
		{
			$websiteId = $page->getDocumentService()->getWebsiteId($page);
			if ($websiteId)
			{
				$site = DocumentHelper::getDocumentInstance($websiteId);
				$homePage = $site->getIndexPage();
				if($homePage !== null)
				{
					$ids[] = $homePage->getId();
				}
			}
		}
		return $ids;
	}
}