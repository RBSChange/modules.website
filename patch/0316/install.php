<?php
/**
 * website_patch_0315
 * @package modules.website
 */
class website_patch_0316 extends patch_BasePatch
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
		// Implement your patch here.
		f_util_FileUtils::writeAndCreateContainer(f_util_FileUtils::buildOverridePath('modules', 'website', 'lib', 'js', 'jquery-noconflict.js'), '// Override to turn off jQuery no-conflict');
		$this->execChangeCommand('compile-js-dependencies');	
		$this->execChangeCommand('clear-webapp-cache');		
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
		return '0316';
	}
}