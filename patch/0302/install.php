<?php
class website_patch_0302 extends patch_BasePatch
{
	/**
	 * Entry point of the patch execution.
	 */
	public function execute()
	{
		// Remove the following line and implement the patch here.
		parent::execute();
		$this->log('Compilation des documents ...');
		exec('change.php compile-documents');
		
		$this->log('Mutation des pages en pagegoup ...');
		$this->executeSQLQuery("UPDATE m_website_doc_page SET `document_model` = 'modules_website/pagegroup' WHERE `document_id` IN (SELECT DISTINCT `document_id` FROM `m_website_doc_page_i18n` WHERE `currentversionid_i18n` > 0)");
		$this->executeSQLQuery("UPDATE f_document SET `document_model` = 'modules_website/pagegroup' WHERE `document_id` IN (SELECT DISTINCT `document_id` FROM `m_website_doc_page_i18n` WHERE `currentversionid_i18n` >0)");
		$this->executeSQLQuery("UPDATE f_relation SET `document_model_id1` = 'modules_website/pagegroup' WHERE `relation_id1` IN (SELECT DISTINCT `document_id` FROM `m_website_doc_page_i18n` WHERE `currentversionid_i18n` >0)");
		$this->executeSQLQuery("UPDATE f_relation SET `document_model_id2` = 'modules_website/pagegroup' WHERE `relation_id2` IN (SELECT DISTINCT `document_id` FROM `m_website_doc_page_i18n` WHERE `currentversionid_i18n` >0)");
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
		return '0302';
	}

}