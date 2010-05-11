<?php
/**
 * website_patch_0314
 * @package modules.website
 */
class website_patch_0314 extends patch_BasePatch
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
		$this->log("Execute: compile-locales website...");
		$this->execChangeCommand("compile-locales", array("website"));
		
		$scriptReader = import_ScriptReader::getInstance();
		$path = f_util_FileUtils::buildAbsolutePath(dirname(__FILE__), 'list.xml');	
		$scriptReader->execute($path);
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
		return '0314';
	}
}