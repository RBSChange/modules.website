<?php
class website_MenufolderService extends f_persistentdocument_DocumentService
{
	/**
	 * @var website_MenufolderService
	 */
	private static $instance;

	/**
	 * @return website_MenufolderService
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
	 * @return website_persistentdocument_menufolder
	 */
	public function getNewDocumentInstance()
	{
		return $this->getNewDocumentInstanceByModelName('modules_website/menufolder');
	}

	/**
	 * Create a query based on 'modules_website/menufolder' model
	 * @return f_persistentdocument_criteria_Query
	 */
	public function createQuery()
	{
		return $this->pp->createQuery('modules_website/menufolder');
	}

}