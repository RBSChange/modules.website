<?php
/**
 * @date Thu, 12 Jul 2007 12:23:48 +0200
 * @author intbonjf
 */
class website_MenuitemtextService extends website_MenuitemService
{
	/**
	 * @var website_MenuitemtextService
	 */
	private static $instance;

	/**
	 * @return website_MenuitemtextService
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
		return $this->pp->createQuery('modules_website/menuitemtext');
	}
}