<?php
/**
 * website_persistentdocument_menuitemfunction
 * @package website
 */
class website_persistentdocument_menuitemfunction extends website_persistentdocument_menuitemfunctionbase
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