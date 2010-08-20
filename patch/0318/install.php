<?php
/**
 * website_patch_0318
 * @package modules.website
 */
class website_patch_0318 extends patch_BasePatch
{
    /**
     * Returns true if the patch modify code that is versionned.
     * If your patch modify code that is versionned AND database structure or content,
     * you must split it into two different patches.
     * @return Boolean true if the patch modify code that is versionned.
     */
	public function isCodePatch()
	{
		return true;
	}
 
	/**
	 * Entry point of the patch execution.
	 */
	public function execute()
	{
		$this->execChangeCommand('compile-js-dependencies');
		$this->execChangeCommand('update-autoload', array('modules/website/actions'));
		$this->execChangeCommand('compile-htaccess');	
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
		return '0318';
	}
}