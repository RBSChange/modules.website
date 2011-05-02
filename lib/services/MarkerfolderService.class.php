<?php
/**
 * website_MarkerfolderService
 * @package website
 */
class website_MarkerfolderService extends generic_FolderService
{
	/**
	 * @var website_MarkerfolderService
	 */
	private static $instance;

	/**
	 * @return website_MarkerfolderService
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
	 * @return website_persistentdocument_markerfolder
	 */
	public function getNewDocumentInstance()
	{
		return $this->getNewDocumentInstanceByModelName('modules_website/markerfolder');
	}

	/**
	 * Create a query based on 'modules_website/markerfolder' model.
	 * Return document that are instance of modules_website/markerfolder,
	 * including potential children.
	 * @return f_persistentdocument_criteria_Query
	 */
	public function createQuery()
	{
		return $this->pp->createQuery('modules_website/markerfolder');
	}
	
	/**
	 * Create a query based on 'modules_website/markerfolder' model.
	 * Only documents that are strictly instance of modules_website/markerfolder
	 * (not children) will be retrieved
	 * @return f_persistentdocument_criteria_Query
	 */
	public function createStrictQuery()
	{
		return $this->pp->createQuery('modules_website/markerfolder', false);
	}
}