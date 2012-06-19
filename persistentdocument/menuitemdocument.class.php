<?php
/**
 * website_persistentdocument_menuitemdocument
 * @package modules.website
 */
class website_persistentdocument_menuitemdocument extends website_persistentdocument_menuitemdocumentbase
{
	/**
	 * @return string
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