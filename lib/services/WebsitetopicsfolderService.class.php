<?php
/**
 * @package modules.website
 * @method website_WebsitetopicsfolderService getInstance()
 */
class website_WebsitetopicsfolderService extends generic_FolderService
{
	/**
	 * @return website_persistentdocument_websitetopicsfolder
	 */
	public function getNewDocumentInstance()
	{
		return $this->getNewDocumentInstanceByModelName('modules_website/websitetopicsfolder');
	}

	/**
	 * Create a query based on 'modules_website/websitetopicsfolder' model.
	 * Return document that are instance of modules_website/websitetopicsfolder,
	 * including potential children.
	 * @return f_persistentdocument_criteria_Query
	 */
	public function createQuery()
	{
		return $this->getPersistentProvider()->createQuery('modules_website/websitetopicsfolder');
	}
	
	/**
	 * Create a query based on 'modules_website/websitetopicsfolder' model.
	 * Only documents that are strictly instance of modules_website/websitetopicsfolder
	 * (not children) will be retrieved
	 * @return f_persistentdocument_criteria_Query
	 */
	public function createStrictQuery()
	{
		return $this->getPersistentProvider()->createQuery('modules_website/websitetopicsfolder', false);
	}
	
	/**
	 * @param website_persistentdocument_websitetopicsfolder $document
	 * @param array<string, string> $attributes
	 * @param integer $mode
	 * @param string $moduleName
	 */
	public function completeBOAttributes($document, &$attributes, $mode, $moduleName)
	{
		if ($document->getWebsite() !== null)
		{
			$attributes['websiteId'] = $document->getWebsite()->getId();
		}
	}
}