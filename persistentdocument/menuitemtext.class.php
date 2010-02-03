<?php
/**
 * website_persistentdocument_menuitemtext
 * @package website
 */
class website_persistentdocument_menuitemtext
	extends website_persistentdocument_menuitemtextbase
{
	/**
	 * Returns the title used in the navigation elements on the website.
	 *
	 * @return string
	 */
	public function getNavigationtitle()
	{
		return $this->getLabel();
	}
}