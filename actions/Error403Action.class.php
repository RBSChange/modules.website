<?php
/**
 * @package modules.website
 */
class website_Error403Action extends website_ErrorAction
{
	/**
	 * @return integer 403
	 */
	protected function getStatus()
	{
		return 403;
	}

	/**
	 * @return website_persistentdocument_page
	 */
	protected function getPage()
	{
		$page = TagService::getInstance()->getDocumentByContextualTag('contextual_website_website_error403', 
			website_WebsiteModuleService::getInstance()->getCurrentWebsite(), false);
		
		//deprecated for compatibility only
		if ($page === null)
		{
			$page = TagService::getInstance()->getDocumentByContextualTag('contextual_website_website_error401-1', website_WebsiteModuleService::getInstance()->getCurrentWebsite());
		}
		
		return $page;
	}
}