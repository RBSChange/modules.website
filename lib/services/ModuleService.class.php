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
	
	/**
	 * @return array
	 */
	public function checkInitModuleInfos()
	{
		$result = array();
		$defaultWebsite = website_WebsiteModuleService::getInstance()->getDefaultWebsite();
		$result['websites'] = ($defaultWebsite->isNew()) ? array() : array($defaultWebsite->getId());		
		$result['createwebsite'] = count($result['websites']) == 0;
		if ($result['createwebsite'])
		{
			$result['createwebsite'] = f_permission_PermissionService::getInstance()
			->hasPermission(users_UserService::getInstance()->getCurrentBackEndUser(), 
				'modules_website.Insert.website',
				ModuleService::getInstance()->getRootFolderId('websites'));
		}
		return $result;
	}
	
	/**
	 * @param f_peristentdocument_PersistentDocument $container
	 * @param string $module
	 * @param string $pageTemplate
	 * @param string $script
	 */
	public function inititalizeStructure($container, $module, $pageTemplate, $script)
	{
		$scriptPath = FileResolver::getInstance()->setPackageName('modules_'.$module)->setDirectory('setup')->getPath($script.'.xml');
		if ($scriptPath === null)
		{
			throw new BaseException('Import script not found!', 'modules.website.bo.general.Import-script-not-found');
		}
		
		$scriptContent = f_util_FileUtils::read($scriptPath);
		$scriptContent = str_replace('"webfactory/tplTwo"', '"'.$pageTemplate.'"', $scriptContent);
		
		$scriptDom = new DOMDocument('1.0', 'UTF-8');
		$scriptDom->loadXML($scriptContent);
				
		$ms = ModuleBaseService::getInstanceByModuleName($module);
		$ms->updateStructureInitializationScript($container, $pageTemplate, $script, $scriptDom);
		
		$tmpFile = f_util_FileUtils::getTmpFile('Script_');
		$scriptDom->save($tmpFile);
	
		$tm = $this->getTransactionManager();
		try 
		{
			$tm->beginTransaction();			
			$scriptReader = import_ScriptReader::getInstance();
			Framework::info('Import Default Struct : ' . $tmpFile);
			$scriptReader->execute($tmpFile);
			@unlink($tmpFile);
			$tm->commit();
		}
		catch (Exception $e)
		{
			$tm->rollBack($e);
			@unlink($tmpFile);
			throw $e;
		}
	}
	
	/**
	 * @param f_peristentdocument_PersistentDocument $container
	 * @param string $pageTemplate
	 * @param string $script
	 * @param DOMDocument $scriptPath
	 */
	public function updateStructureInitializationScript($container, $pageTemplate, $script, $scriptDom)
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
		
		// Fix script content.
		$xmlWebsite = $scriptDom->getElementsByTagName('website')->item(0);
		$xmlWebsite->setAttribute('documentid', $container->getId());
		$xmlWebsite->setAttribute('domain', $container->getDomain());
		$xmlWebsite->setAttribute('url', $container->getUrl());
		
		$xmlWebsite->removeAttribute('label');
		$xmlWebsite->removeAttribute('label-en');
		$xmlWebsite->removeAttribute('protocol');
		$xmlWebsite->removeAttribute('localizebypath');
		$xmlWebsite->removeAttribute('byTag');
	}
}