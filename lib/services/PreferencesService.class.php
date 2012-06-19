<?php
/**
 * @package modules.website
 * @method website_PreferencesService getInstance()
 */
class website_PreferencesService extends f_persistentdocument_DocumentService
{
	/**
	 * @return website_persistentdocument_preferences
	 */
	public function getNewDocumentInstance()
	{
		return $this->getNewDocumentInstanceByModelName('modules_website/preferences');
	}

	/**
	 * Create a query based on 'modules_modules_website/preferences' model
	 * @return f_persistentdocument_criteria_Query
	 */
	public function createQuery()
	{
		return $this->getPersistentProvider()->createQuery('modules_website/preferences');
	}
	
	/**
	 * @param website_persistentdocument_preferences $document
	 * @param integer $parentNodeId Parent node ID where to save the document (optionnal => can be null !).
	 * @return void
	 */
	protected function preSave($document, $parentNodeId)
	{
		$document->setLabel('website');
	}
}