<?php
/**
 * website_patch_0310
 * @package modules.website
 */
class website_patch_0310 extends patch_BasePatch
{
 
	/**
	 * Entry point of the patch execution.
	 */
	public function execute()
	{
		try 
		{
			$this->log('Patching m_website_doc_website_i18n...');
			
			$this->executeSQLQuery("ALTER TABLE `m_website_doc_website_i18n` ADD `document_publicationstatus_i18n` ENUM('DRAFT', 'CORRECTION', 'ACTIVE', 'PUBLICATED', 'DEACTIVATED', 'FILED', 'DEPRECATED', 'TRASH', 'WORKFLOW') NULL DEFAULT NULL");
			$this->executeSQLQuery("UPDATE `m_website_doc_website_i18n` SET `document_publicationstatus_i18n` = 'PUBLICATED'");
			$this->executeSQLQuery("UPDATE `m_website_doc_website` SET `document_publicationstatus` = 'PUBLICATED'");
			
			$this->log('Compiling documents...');
			exec("change.php compile-documents");
			
			$this->log('Compiling roles...');
			exec("change.php compile-roles");
			
			$this->log('Compiling permissions...');
			exec("change.php compile-permissions");
		}
		catch (Exception $e)
		{
			Framework::info($e->getMessage());
			$this->logWarning('Table m_website_doc_website_i18n already patched.');
		}
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
		return '0310';
	}
}