<?php
/**
 * website_patch_0352
 * @package modules.website
 */
class website_patch_0352 extends patch_BasePatch
{
	/**
	 * Entry point of the patch execution.
	 */
	public function execute()
	{
		$this->execChangeCommand('compile-locales', array('website'));
		
		$newPath = f_util_FileUtils::buildWebeditPath('modules/website/persistentdocument/marker.xml');
		$newModel = generator_PersistentModel::loadModelFromString(f_util_FileUtils::read($newPath), 'website', 'marker');
		$newProp = $newModel->getPropertyByName('website');
		f_persistentdocument_PersistentProvider::getInstance()->addProperty('website', 'marker', $newProp);
		$this->execChangeCommand('compile-db-schema');
		
		$this->executeModuleScript('markers.xml', 'website');
		
		try 
		{
			$this->beginTransaction();
			
			$markerFolders = website_MarkerfolderService::getInstance()->createQuery()->find();
			foreach ($markerFolders as $markerFolder) 
			{
				$this->migrateMarkerFolder($markerFolder);
			}		
				
			$this->commit();
		} 
		catch (Exception $e) 
		{
			$this->rollBack($e);
			throw $e;
		}
	}
	
	/**
	 * @param website_persistentdocument_markerfolder $markerFolder
	 */
	private function migrateMarkerFolder($markerFolder)
	{
		$website = website_WebsiteService::getInstance()->createQuery()->add(Restrictions::ancestorOf($markerFolder->getId()))->findUnique();	
		$markers = $markerFolder->getDocumentService()->getChildrenOf($markerFolder);
		foreach ($markers as $marker) 
		{
			if ($marker instanceof  website_persistentdocument_marker)
			{
				if ($marker->getTreeId())
				{
					TreeService::getInstance()->deleteNodeById($marker->getId());
				}
				$marker->setWebsite($website);
				$marker->save();
			}
		}
		$markerFolder->delete();
	}
}