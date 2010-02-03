<?php
class website_RemoveIndexPageAction extends website_Action
{
	/**
	 * @param Context $context
	 * @param Request $request
	 */
	public function _execute($context, $request)
	{
		$topic = $this->getDocumentInstanceFromRequest($request);
		try
		{
		    website_WebsiteModuleService::getInstance()->removeIndexPage($topic, true);
		    $this->logAction($topic);
		}
		catch (Exception $e)
		{
		    Framework::exception($e);
			$request->setAttribute('message', f_Locale::translate('&modules.website.bo.general.remove-index-page-error;'));
			return self::getErrorView();
		}
		return self::getSuccessView();
	}
}