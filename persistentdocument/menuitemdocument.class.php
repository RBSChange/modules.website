<?php
/**
 * website_persistentdocument_menuitemdocument
 * @package modules.website
 */
class website_persistentdocument_menuitemdocument extends website_persistentdocument_menuitemdocumentbase
{
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
	
	// Deprecated.
	
	/**
	 * @deprecated (will be removed in 4.0) with no replacement
	 */
	public function getPopupParametersArray()
	{
		$popupParameters = array();
		$paramArray = explode(',', $this->getPopupParameters());
		foreach ($paramArray as $p)
		{
			list ($n, $v) = explode(':', $p);
			$popupParameters[trim($n)] = trim($v);
		}
		return $popupParameters;
	}
	
	/**
	 * @deprecated (will be removed in 4.0) use getDocument()->getNavigationtitle()
	 */
	public function getNavigationtitle()
	{
		return website_WebsiteModuleService::getNavigationTitleFor($this->getDocument());
	}
	
	/**
	 * @deprecated (will be removed in 4.0) use getDocument()->getNavigationVisibility()
	 */
	public function getNavigationVisibility()
	{
		return $this->getDocument()->getNavigationVisibility();
	}
}