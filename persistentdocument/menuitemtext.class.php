<?php
/**
 * website_persistentdocument_menuitemtext
 * @package website
 */
class website_persistentdocument_menuitemtext extends website_persistentdocument_menuitemtextbase
{
	// Deprecated.

	/**
	 * @deprecated use getNavigationLabel
	 */
	public function getNavigationtitle()
	{
		return $this->getNavigationLabel();
	}
}