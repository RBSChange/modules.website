<?php
/**
 * website_persistentdocument_pageexternal
 * @package website
 */
class website_persistentdocument_pageexternal extends website_persistentdocument_pageexternalbase implements website_PublishableElement
{
	
	
	//DEPRECATED
	
	/**
	 * @deprecated
	 */
	public function getNavigationURL()
	{
		return $this->getUrl();
	}
}