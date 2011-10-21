<?php
/**
 * @package modules.website
 */
class website_Error401Action extends website_ErrorAction
{
	/**
	 * @return string
	 */
	protected function getStatus()
	{
		return 401;
	}

	/**
	 * @return website_persistentdocument_page
	 */
	protected function getPage()
	{
		return TagService::getInstance()->getDocumentByContextualTag(WebsiteConstants::TAG_ERROR_401_PAGE, website_WebsiteService::getInstance()->getCurrentWebsite());
	}
}