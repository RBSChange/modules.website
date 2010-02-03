<?php
class website_Error500Action extends website_ErrorAction
{
	protected function getStatus()
	{
		return 500;
	}

	/**
	 * @return website_persistentdocument_page
	 */
	protected function getPage()
	{
		return TagService::getInstance()->getDocumentByContextualTag(WebsiteConstants::TAG_ERROR_PAGE , website_WebsiteModuleService::getInstance()->getCurrentWebsite());
	}
}