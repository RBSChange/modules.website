<?php
/**
 * website_patch_0308
 * @package modules.website
 */
class website_patch_0308 extends patch_BasePatch
{
	//  by default, isCodePatch() returns false.
	//  decomment the following if your patch modify code instead of the database structure or content.
	/**
	 * Returns true if the patch modify code that is versionned.
	 * If your patch modify code that is versionned AND database structure or content,
	 * you must split it into two different patches.
	 * @return Boolean true if the patch modify code that is versionned.
	 */
	//	public function isCodePatch()
	//	{
	//		return true;
	//	}
	


	/**
	 * Entry point of the patch execution.
	 */
	public function execute()
	{
		parent::execute();
		// Remove indexingstatus
		$archivePath = f_util_FileUtils::buildWebeditPath('modules/website/persistentdocument/old/pageexternal-1.xml');
		$oldModel = generator_PersistentModel::loadModelFromString(f_util_FileUtils::read($archivePath), 'website', 'pageexternal');
		$oldProp = $oldModel->getPropertyByName('indexingstatus');
		f_persistentdocument_PersistentProvider::getInstance()->delProperty('website', 'pageexternal', $oldProp);
		
		// Add useurl property
		$newPath = f_util_FileUtils::buildWebeditPath('modules/website/persistentdocument/pageexternal.xml');
		$newModel = generator_PersistentModel::loadModelFromString(f_util_FileUtils::read($newPath), 'website', 'pageexternal');
		$newProp = $newModel->getPropertyByName('useurl');
		f_persistentdocument_PersistentProvider::getInstance()->addProperty('website', 'pageexternal', $newProp);
		
		exec("change.php compile-documents");
		exec("change.php curl");
		exec("change.php ci18n website");
	}
	
	/**
	 * Returns the name of the module the patch belongs to.
	 *
	 * @return String
	 */
	protected final function getModuleName()
	{
		return 'website';
	}
	
	/**
	 * Returns the number of the current patch.
	 * @return String
	 */
	protected final function getNumber()
	{
		return '0308';
	}
}