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
			self::$instance = new self();
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
	
	/**
	 * @return array
	 */
	public function checkInitModuleInfos()
	{
		$result = array();
		$defaultWebsite = website_WebsiteService::getInstance()->getDefaultWebsite();
		$result['websites'] = ($defaultWebsite->isNew()) ? array() : array($defaultWebsite->getId());		
		$result['createwebsite'] = count($result['websites']) == 0;
		if ($result['createwebsite'])
		{
			$result['createwebsite'] = change_PermissionService::getInstance()
			->hasPermission(users_UserService::getInstance()->getCurrentBackEndUser(), 
				'modules_website.Insert.website',
				ModuleService::getInstance()->getRootFolderId('websites'));
		}
		return $result;
	}
	
	/**
	 * @param f_persistentdocument_PersistentDocument $container
	 * @param string $module
	 * @param array $attributes
	 * @param string $script
	 */
	public function inititalizeStructure($container, $module, $attributes, $script)
	{
		$scriptFile = $script . (!f_util_StringUtils::endsWith($script, '.xml') ? '.xml' : '');
		$scriptPath = FileResolver::getInstance()->setPackageName('modules_'.$module)->setDirectory('setup')->getPath($scriptFile);
		if ($scriptPath === null)
		{
			throw new BaseException('Import script not found!', 'modules.website.bo.general.Import-script-not-found');
		}
		
		$ms = ModuleBaseService::getInstanceByModuleName($module);
		if (f_util_ClassUtils::methodExists($ms, 'getStructureInitializationAttributes'))
		{
			$attributes = $ms->getStructureInitializationAttributes($container, $attributes, $script);
		}
		
		$tm = $this->getTransactionManager();
		try 
		{
			$tm->beginTransaction();			
			$scriptReader = import_ScriptReader::getInstance();
			$scriptReader->execute($scriptPath, $attributes);
			$tm->commit();
		}
		catch (Exception $e)
		{
			$tm->rollBack($e);
			throw $e;
		}
	}
	
	/**
	 * @param f_peristentdocument_PersistentDocument $container
	 * @param array $attributes
	 * @param string $script
	 * @return array
	 */
	public function getStructureInitializationAttributes($container, $attributes, $script)
	{
		// Check container.
		if (!$container instanceof website_persistentdocument_website)
		{
			throw new BaseException('Invalid website', 'modules.website.bo.actions.Invalid-website');
		}
		else
		{
			$node = TreeService::getInstance()->getInstanceByDocument($container);
			if (count($node->getChildren('modules_website/topic')) > 0)
			{
				throw new BaseException('Website is not empty', 'modules.website.bo.actions.Website-is-not-empty');
			}
		}		
		website_WebsiteService::getInstance()->setCurrentWebsite($container);
		
		// Set atrtibutes.
		$attributes['byDocumentId'] = $container->getId();
		$attributes['label'] = $container->getLabel();
		$attributes['protocol'] = $container->getProtocol();
		$attributes['localizebypath'] = $container->getLocalizebypath();
		return $attributes;
	}
}