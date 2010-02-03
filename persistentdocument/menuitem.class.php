<?php
/**
 * website_persistentdocument_menuitem
 * @package website
 */
class website_persistentdocument_menuitem extends website_persistentdocument_menuitembase
{
	/**
	 * @return WebsiteConstants::VISIBILITY_VISIBLE
	 */
	public function getNavigationVisibility()
	{
		return WebsiteConstants::VISIBILITY_VISIBLE;
	}
}