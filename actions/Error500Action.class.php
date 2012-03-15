<?php
/**
 * @package modules.website
 */
class website_Error500Action extends website_ErrorAction
{
	/**
	 * @return string
	 */
	protected function getStatus()
	{
		return 500;
	}

	/**
	 * @return website_persistentdocument_page
	 */
	protected function getPage()
	{
		return TagService::getInstance()->getDocumentByContextualTag('contextual_website_website_server-error', website_WebsiteModuleService::getInstance()->getCurrentWebsite());
	}
}