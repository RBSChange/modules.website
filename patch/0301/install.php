<?php
class website_patch_0301 extends patch_BasePatch
{
	
	/**
	 * Entry point of the patch execution.
	 */
	public function execute()
	{
		$this->log("Compiling documents...");
		exec("change.php compile-documents");
		$this->log("Adding `alwaysappendtitle` column to `m_website_doc_website`...");
		$this->executeSQLQuery("ALTER TABLE  `m_website_doc_website` ADD  `alwaysappendtitle` TINYINT( 1 ) NOT NULL DEFAULT  '0'");
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
		return '0301';
	}
}