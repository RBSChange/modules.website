<?php
/**
 * @date Thu Feb 08 09:49:22 CET 2007
 * @author INTbonjF
 */
class website_SetHomePageAction extends website_Action
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
			$request->setAttribute('message', f_Locale::translate('&modules.website.bo.general.set-home-page-error;'));
			return self::getErrorView();
		}
		return self::getSuccessView();
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