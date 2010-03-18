<?php
/**
 * website_patch_0313
 * @package modules.website
 */
class website_patch_0313 extends patch_BasePatch
{
	/**
	 * Entry point of the patch execution.
	 */
	public function execute()
	{
		try 
		{
		   $this->executeSQLQuery("ALTER TABLE `m_generic_doc_folder` ADD   `website` int(11) default NULL");
		   
		   //Defined by rootfolder
		   //$this->executeSQLQuery("ALTER TABLE `m_generic_doc_folder` ADD   `topics` int(11) default '0'");
		}
		catch (Exception $e)
		{
			$this->log($e->getMessage());
		}
		
		$this->log("Compilation des documents...");
		exec("change.php compile-documents");

		$this->log("Migration des modules à rubrique...");
		foreach (ModuleService::getInstance()->getModulesObj() as $module) 
		{
			if ($module->isVisible() && $module->isTopicBased() && $module->hasPerspectiveConfigFile())
			{
				$this->log("Update: " .$module->getName() ."...");
				$rootFolder = $this->getRootFolder($module);
				$topics = $rootFolder->getTopicsArray();
				if (count($topics))
				{
					$rootFolder->removeAllTopics();
					$rootFolder->setTopicsArray($topics);
					$rootFolder->save();
				}
			}
		}
		
		$this->log("Compilation des locales du module...");
		exec("change.php compile-locales website");
		
		$this->log("Compilation des documents editors...");
		exec("change.php compile-editors-config");
	}
	
	/**
	 * @param c_Module $module
	 * @return generic_persistentdocument_rootfolder
	 */
	private function getRootFolder($module)
	{
		$rootfolderId = ModuleService::getInstance()->getRootFolderId($module->getName());
		return DocumentHelper::getDocumentInstance($rootfolderId, 'modules_generic/rootfolder');	
	}
	
	/**
	 * @return String
	 */
	protected final function getModuleName()
	{
		return 'website';
	}

	/**
	 * @return String
	 */
	protected final function getNumber()
	{
		return '0313';
	}
	
	
}