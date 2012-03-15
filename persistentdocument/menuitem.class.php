<?php
/**
 * website_persistentdocument_menuitem
 * @package website
 */
class website_persistentdocument_menuitem extends website_persistentdocument_menuitembase
{
	/**
	 * @return website_ModuleService::VISIBLE
	 */
	public function getNavigationVisibility()
	{
		return website_ModuleService::VISIBLE;
	}
}