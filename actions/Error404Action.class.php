<?php
class website_Error404Action extends website_ErrorAction
{
	protected function getStatus()
	{
		return 404;
	}

	/**
	 * @return website_persistentdocument_page
	 */
	protected function getPage()
	{
		$website = website_WebsiteModuleService::getInstance()->getCurrentWebsite();
		return TagService::getInstance()->getDocumentByContextualTag(WebsiteConstants::TAG_ERROR_404_PAGE, $website);
	}
}