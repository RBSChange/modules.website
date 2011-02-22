<?php
/**
 * website_persistentdocument_menu
 * @package website
 */
class website_persistentdocument_menu extends website_persistentdocument_menubase
{
	/**
	 * Adds a menuitem to the menu.
	 * If the given $newValue is not a website_persistentdocument_menuitem,
	 * then a new website_persistentdocument_menuitem is created
	 * poiting to the given document. This menuitem is then appended to the menu.
	 *
	 * @param website_persistentdocument_menuitemdocument $newValue  Can't not be null
	 * @return void
	 */
	public function addMenuItem($newValue)
	{
		if ($newValue instanceof website_persistentdocument_menuitem)
		{
			parent::addMenuItem($newValue);
		}
		else
		{
			throw new Exception('Invalid document type');
		}
	}
}