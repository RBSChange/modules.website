<?php
/**
 * website_persistentdocument_pageexternal
 * @package website
 */
class website_persistentdocument_pageexternal extends website_persistentdocument_pageexternalbase implements website_PublishableElement
{
	/**
	 * @see website_PublishableElement::getNavigationURL()
	 * @return string
	 */
	public function getNavigationURL()
	{
		return $this->getUrl();
	}
}