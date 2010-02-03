<?php
/**
 * @date Thu Feb 08 09:49:22 CET 2007
 * @author INTbonjF
 */
class website_SetTopicIndexPageAction extends website_Action
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
			website_WebsiteModuleService::getInstance()->setIndexPage($page, true);
			$this->logAction($page);
		}
		catch (Exception $e)
		{
		    Framework::exception($e);
			$request->setAttribute('message', f_Locale::translate('&modules.website.bo.general.set-index-page-error;'));
			return self::getErrorView();
		}

		return self::getSuccessView();
	}
}