<?php
/**
 * website_patch_0358
 * @package modules.website
 */
class website_patch_0358 extends patch_BasePatch
{
 
	/**
	 * Entry point of the patch execution.
	 */
	public function execute()
	{
		$list = list_ListService::getInstance()->getByListId('modules_iframe/scrolling');
		if ($list !== null)
		{
			$this->beginTransaction();
			$list->setListid('modules_website/iframescrolling');
			$this->getPersistentProvider()->updateDocument($list);
			$destId = ModuleService::getInstance()->getSystemFolderId('list', 'website');
			TreeService::getInstance()->moveToLastChild($list->getId(), $destId);
			
			$documentId = ModuleService::getInstance()->getSystemFolderId('list', 'iframe');
			$systemFolder = generic_persistentdocument_systemfolder::getInstanceById($documentId);
			$this->log('Delete : ' . $systemFolder->__toString());	
			$systemFolder->delete();
			
			$package = 'modules_list/modules_iframe';		
			$name = ModuleService::SETTING_SYSTEM_FOLDER_ID;
			$this->executeSQLQuery("DELETE FROM `f_settings` WHERE `package` = '$package' AND `name` = '$name' AND `userid` = 0");
			
			$this->commit();

			$this->execChangeCommand('compile-blocks');
		}
		else
		{
			$this->log('Already applied');	
		}
	}
}