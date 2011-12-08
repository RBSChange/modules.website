<?php
/**
 * website_WebsitetopicsfolderService
 * @package modules.website
 */
class website_WebsitetopicsfolderService extends generic_FolderService
{
	/**
	 * @var website_WebsitetopicsfolderService
	 */
	private static $instance;

	/**
	 * @return website_WebsitetopicsfolderService
	 */
	public static function getInstance()
	{
		if (self::$instance === null)
		{
			self::$instance = new self();
		}
		return self::$instance;
	}

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
		return $this->pp->createQuery('modules_website/websitetopicsfolder');
	}
	
	/**
	 * Create a query based on 'modules_website/websitetopicsfolder' model.
	 * Only documents that are strictly instance of modules_website/websitetopicsfolder
	 * (not children) will be retrieved
	 * @return f_persistentdocument_criteria_Query
	 */
	public function createStrictQuery()
	{
		return $this->pp->createQuery('modules_website/websitetopicsfolder', false);
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