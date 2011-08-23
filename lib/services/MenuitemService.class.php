<?php
/**
 * @date Mon, 11 Jun 2007 15:30:34 +0200
 * @author intbonjf
 */
class website_MenuitemService extends f_persistentdocument_DocumentService
{
	/**
	 * @var website_MenuitemService
	 */
	private static $instance;

	/**
	 * @return website_MenuitemService
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
	 * @return website_persistentdocument_menuitem
	 */
	public function getNewDocumentInstance()
	{
		return $this->getNewDocumentInstanceByModelName('modules_website/menuitem');
	}

	/**
	 * Create a query based on 'modules_website/menuitem' model
	 * @return f_persistentdocument_criteria_Query
	 */
	public function createQuery()
	{
		return $this->pp->createQuery('modules_website/menuitem');
	}
}