<?php
/**
 * website_patch_0305
 * @package modules.website
 */
class website_patch_0305 extends patch_BasePatch
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
		$this->executeSQLQuery("ALTER TABLE m_website_doc_page ADD `robotsmeta` varchar(20)");
		$this->executeSQLQuery("update m_website_doc_page set `robotsmeta` = 'index,follow'");
		$scriptReader = import_ScriptReader::getInstance();
		$scriptReader->executeModuleScript("website", "init.xml");
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
		return '0305';
	}
}