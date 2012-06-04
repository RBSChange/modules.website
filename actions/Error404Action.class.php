<?php
/**
 * @package modules.website
 */
class website_Error404Action extends website_ErrorAction
{
	/**
	 * @return string
	 */
	protected function getStatus()
	{
		return 404;
	}

	/**
	 * @return website_persistentdocument_page
	 */
	protected function getPage()
	{
		$website = website_WebsiteService::getInstance()->getCurrentWebsite();
		return TagService::getInstance()->getDocumentByContextualTag('contextual_website_website_error404', $website);
	}
}