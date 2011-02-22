<?php
/**
 * website_persistentdocument_menuitemfunction
 * @package website
 */
class website_persistentdocument_menuitemfunction extends website_persistentdocument_menuitemfunctionbase
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