<?php
/**
 * @date Wed, 23 May 2007 09:14:53 +0200
 * @author intcours
 */
class website_PreferencesService extends f_persistentdocument_DocumentService
{
	/**
	 * @var website_PreferencesService
	 */
	private static $instance;

	/**
	 * @return website_PreferencesService
	 */
	public static function getInstance()
	{
		if (self::$instance === null)
		{
			self::$instance = self::getServiceClassInstance(get_class());
		}
		return self::$instance;
	}

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
		return $this->pp->createQuery('modules_website/preferences');
	}
	
	/**
	 * @param website_persistentdocument_preferences $document
	 * @param Integer $parentNodeId Parent node ID where to save the document (optionnal => can be null !).
	 * @return void
	 */
	protected function preSave($document, $parentNodeId)
	{
		$document->setLabel('&modules.website.bo.general.Module-name;');
	}
}