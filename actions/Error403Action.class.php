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
		$website = website_WebsiteService::getInstance()->getCurrentWebsite();
		return TagService::getInstance()->getDocumentByContextualTag('contextual_website_website_error403', $website, false);
	}
}