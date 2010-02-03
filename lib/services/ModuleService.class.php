<?php
/**
 * website_ModuleService
 * @package modules.website.lib.services
 */
class website_ModuleService extends ModuleBaseService
{
	/**
	 * Singleton
	 * @var website_ModuleService
	 */
	private static $instance = null;

	/**
	 * @return website_ModuleService
	 */
	public static function getInstance()
	{
		if (is_null(self::$instance))
		{
			self::$instance = self::getServiceClassInstance(get_class());
		}
		return self::$instance;
	}
	
	/**
	 * @see ModuleBaseService::getParentNodeForPermissions()
	 *
	 * @param Integer $documentId
	 * @return f_persistentdocument_PersistentTreeNode
	 */
	public function getParentNodeForPermissions($documentId)
	{
		$parent = $this->getVirtualParentForBackoffice(DocumentHelper::getDocumentInstance($documentId));
		if ($parent !== null)
		{
			return TreeService::getInstance()->getInstanceByDocument($parent);
		}
		return null;
	}
	
	/**
	 * @see ModuleBaseService::getVirtualParentForBackoffice()
	 *
	 * @param f_persistentdocument_PersistentDocument $document
	 * @return f_persistentdocument_PersistentDocument
	 */
	public function getVirtualParentForBackoffice($document)
	{
		if ($document instanceof website_persistentdocument_menuitem ) 
		{
			return website_MenuService::getInstance()->createQuery()
				->add(Restrictions::eq('menuItem', $document))->findUnique();
		}
		return null;
	}
}