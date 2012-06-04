<?php
/**
 * website_persistentdocument_topic
 * @package website
 */
class website_persistentdocument_topic extends website_persistentdocument_topicbase
{
	/**
	 * @return integer or null
	 */
	public function getIndexPageId()
	{
		$indexPage = $this->getIndexPage();
		if ( ! is_null($indexPage) )
		{
			return $indexPage->getId();
		}
		return null;
	}
	
	/**
	 * @return boolean
	 */
	public function hasPublishedIndexPage()
	{
		return $this->getIndexPage() && $this->getIndexPage()->isPublished();
	}
	
	/**
	 * @return string
	 */
	public function getPathOf()
	{
		return $this->getDocumentService()->getPathOf($this);
	}
}