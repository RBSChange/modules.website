<?php
/**
 * website_patch_0315
 * @package modules.website
 */
class website_patch_0315 extends patch_BasePatch
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
		try 
		{
			$newPath = f_util_FileUtils::buildWebeditPath('modules/website/persistentdocument/website.xml');
			$newModel = generator_PersistentModel::loadModelFromString(f_util_FileUtils::read($newPath), 'website', 'website');
			$newProp = $newModel->getPropertyByName('allowedpagetemplate');
			f_persistentdocument_PersistentProvider::getInstance()->addProperty('website', 'website', $newProp);
		} 
		catch (BaseException $e)
		{
			if ($e->getAttribute('sqlstate') != '42S21' || $e->getAttribute('errorcode') != '1060')
			{
				throw $e;
			}
		}
		
		try 
		{
			$newPath = f_util_FileUtils::buildWebeditPath('modules/website/persistentdocument/topic.xml');
			$newModel = generator_PersistentModel::loadModelFromString(f_util_FileUtils::read($newPath), 'website', 'topic');
			$newProp = $newModel->getPropertyByName('allowedpagetemplate');
			f_persistentdocument_PersistentProvider::getInstance()->addProperty('website', 'topic', $newProp);
		} 
		catch (BaseException $e)
		{
			if ($e->getAttribute('sqlstate') != '42S21' || $e->getAttribute('errorcode') != '1060')
			{
				throw $e;
			}
		}		
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
		return '0315';
	}
}