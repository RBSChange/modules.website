<?php
/**
 * website_patch_0320
 * @package modules.website
 */
class website_patch_0320 extends patch_BasePatch
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
		$this->executeSQLQuery("DELETE FROM f_relation WHERE document_model_id1 = 'modules_basemarker/markerrelation';");
		$this->executeSQLQuery("DELETE FROM f_relation WHERE document_model_id2 = 'modules_basemarker/markerrelation';");
		$this->executeSQLQuery("DELETE FROM f_document WHERE document_model = 'modules_basemarker/markerrelation';");
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
		return '0320';
	}
}