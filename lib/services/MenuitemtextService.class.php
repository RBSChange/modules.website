<?php
/**
 * @package modules.website
 * @method website_MenuitemtextService getInstance()
 */
class website_MenuitemtextService extends website_MenuitemService
{
	/**
	 * @return website_persistentdocument_menuitemtext
	 */
	public function getNewDocumentInstance()
	{
		return $this->getNewDocumentInstanceByModelName('modules_website/menuitemtext');
	}

	/**
	 * Create a query based on 'modules_website/menuitemtext' model
	 * @return f_persistentdocument_criteria_Query
	 */
	public function createQuery()
	{
		return $this->getPersistentProvider()->createQuery('modules_website/menuitemtext');
	}
	
	/**
	 * @param website_persistentdocument_menuitemtext $document
	 * @return website_MenuEntry|null
	 */
	public function getMenuEntry($document)
	{
		$entry = website_MenuEntry::getNewInstance();
		$entry->setDocument($document);
		$entry->setLabel($document->getLabel());
		return $entry;
	}
}