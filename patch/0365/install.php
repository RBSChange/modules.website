<?php
/**
 * website_patch_0365
 * @package modules.website
 */
class website_patch_0365 extends patch_BasePatch
{
//  by default, isCodePatch() returns false.
//  decomment the following if your patch modify code instead of the database structure or content.
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
		$this->addProjectConfigurationEntry('tal/prefix/alternateclass', 'website_TalesAlternateClass');
		$this->addProjectConfigurationEntry('tal/prefix/url', 'website_TalesUrl');
		$this->addProjectConfigurationEntry('tal/prefix/actionurl', 'website_TalesUrl');
		$this->addProjectConfigurationEntry('tal/prefix/currenturl', 'website_TalesUrl');
		
		$this->execChangeCommand("compile-config");
		
		$this->execChangeCommand("compile-phptal");
	}
}