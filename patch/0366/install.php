<?php
/**
 * website_patch_0366
 * @package modules.website
 */
class website_patch_0366 extends patch_BasePatch
{
	/**
	 * Entry point of the patch execution.
	 */
	public function execute()
	{
		$newPath = f_util_FileUtils::buildWebeditPath('modules/website/persistentdocument/topic.xml');
		$newModel = generator_PersistentModel::loadModelFromString(f_util_FileUtils::read($newPath), 'website', 'topic');
		$newProp = $newModel->getPropertyByName('visual');
		f_persistentdocument_PersistentProvider::getInstance()->addProperty('website', 'topic', $newProp);
		$this->execChangeCommand('compile-db-schema');
		
		$this->executeLocalXmlScript('list-menutags.xml');
		
		foreach (website_MenufolderService::getInstance()->createQuery()->find() as $folder)
		{
			$folder->setLabel('m.website.bo.general.menu-folder-label');
			$folder->save();
		}
	}
}