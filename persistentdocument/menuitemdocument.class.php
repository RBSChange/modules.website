<?php
/**
 * website_persistentdocument_menuitemdocument
 * @package modules.website
 */
class website_persistentdocument_menuitemdocument extends website_persistentdocument_menuitemdocumentbase
{
	/**
	 * Returns the title used in the navigation elements on the website.
	 *
	 * @return string
	 */
	public function getNavigationtitle()
	{
		return website_WebsiteModuleService::getNavigationTitleFor($this->getDocument());
	}
	
	/**
	 * @see WebsiteHelper, WebsiteConstants
	 */
	public function getNavigationVisibility()
	{
		return $this->getDocument()->getNavigationVisibility();
	}
		
	/**
	 * @return array The popup parameters as an array.
	 */
	public function getPopupParametersArray()
	{
		$popupParameters = array();
		$paramArray = explode(',', $this->getPopupParameters());
		foreach ($paramArray as $p)
		{
			if (strpos($p, ':') !== false)
			{
				list ($n, $v) = explode(':', $p);
				$popupParameters[trim($n)] = trim($v);
			}
		}
		return $popupParameters;
	}
	
	/**
	 * @return String
	 */
	public function getPublicationstatus()
	{
		$doc = $this->getDocument();
		if ($doc !== null)
		{
			return $doc->getPublicationstatus();
		}
		return parent::getPublicationstatus();
	}
}