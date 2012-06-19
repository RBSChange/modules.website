<?php
/**
 * @package modules.website
 * @method website_MenuitemService getInstance()
 */
class website_MenuitemService extends f_persistentdocument_DocumentService
{
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
		return $this->getPersistentProvider()->createQuery('modules_website/menuitem');
	}
}